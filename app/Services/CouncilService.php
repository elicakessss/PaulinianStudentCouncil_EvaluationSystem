<?php

namespace App\Services;

use App\Models\Council;
use App\Models\Position;
use App\Models\CouncilPosition;
use App\Models\Administrator;
use App\Models\Adviser;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class CouncilService
{
    /**
     * Create a new council based on user role and data
     *
     * @param array $data
     * @param mixed $createdBy (Administrator or Adviser instance)
     * @return Council
     */
    public function createCouncil(array $data, $createdBy): Council
    {
        DB::beginTransaction();

        try {
            $council = new Council();
            $council->name = $data['name'];
            $council->academic_year = $data['academic_year'];
            $council->adviser_id = $data['adviser_id'];
            $council->status = 'active';

            // If created by admin, it's university-wide (department_id remains null)
            // If created by adviser, assign to adviser's department
            if ($createdBy instanceof Administrator) {
                $council->department_id = null; // University-wide
            } else if ($createdBy instanceof Adviser) {
                $council->department_id = $createdBy->department_id;
            }

            $council->save();

            // Create positions for this council
            $this->createDefaultPositions($council);

            DB::commit();
            return $council;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing council
     *
     * @param Council $council
     * @param array $data
     * @return Council
     */
    public function updateCouncil(Council $council, array $data): Council
    {
        DB::beginTransaction();

        try {
            $council->name = $data['name'] ?? $council->name;
            $council->academic_year = $data['academic_year'] ?? $council->academic_year;
            $council->adviser_id = $data['adviser_id'] ?? $council->adviser_id;

            // Department ID should only be updated if it's a departmental council
            if (!$council->isUniversityWide() && isset($data['department_id'])) {
                $council->department_id = $data['department_id'];
            }

            $council->save();

            DB::commit();
            return $council;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Activate or deactivate a council
     *
     * @param Council $council
     * @param string $status
     * @return Council
     */
    public function updateStatus(Council $council, string $status): Council
    {
        if (!in_array($status, ['active', 'deactivated'])) {
            throw new \InvalidArgumentException("Invalid status: $status");
        }

        $council->status = $status;
        $council->save();

        return $council;
    }

    /**
     * Delete a council and all associated positions
     *
     * @param Council $council
     * @return bool
     */
    public function deleteCouncil(Council $council): bool
    {
        DB::beginTransaction();

        try {
            // This will cascade delete all council positions due to foreign key constraint
            $result = $council->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get all councils with optional filtering
     *
     * @param array $filters
     * @return Collection
     */
    public function getAllCouncils(array $filters = []): Collection
    {
        $query = Council::with(['adviser', 'department', 'councilPositions.position']);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (isset($filters['academic_year'])) {
            $query->where('academic_year', $filters['academic_year']);
        }

        if (isset($filters['adviser_id'])) {
            $query->where('adviser_id', $filters['adviser_id']);
        }

        // Check if we only want university-wide councils
        if (isset($filters['university_wide']) && $filters['university_wide']) {
            $query->whereNull('department_id');
        }

        return $query->get();
    }

    /**
     * Get councils accessible to a specific user
     *
     * @param mixed $user (Administrator, Adviser, or Student)
     * @return Collection
     */
    public function getCouncilsForUser($user): Collection
    {
        if ($user instanceof Administrator) {
            // Admins can see all councils
            return $this->getAllCouncils();
        } elseif ($user instanceof Adviser) {
            // Advisers can only see their own councils
            return Council::where('adviser_id', $user->id)->get();
        } elseif ($user instanceof Student) {
            // Students can see councils they're part of
            return Council::whereHas('councilPositions', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })->get();
        }

        return new Collection(); // Empty collection if no match
    }

    /**
     * Assign a student to a council position
     *
     * @param Council $council
     * @param int $positionId
     * @param int $studentId
     * @return CouncilPosition
     */
    public function assignStudentToPosition(Council $council, int $positionId, int $studentId): CouncilPosition
    {
        $student = Student::findOrFail($studentId);

        // Check if student already has a position in this council
        $existingPosition = CouncilPosition::where('council_id', $council->id)
            ->where('student_id', $studentId)
            ->first();

        if ($existingPosition) {
            throw new \Exception("Student already has a position in this council.");
        }

        // For departmental councils, check that student belongs to the same department
        if ($council->department_id) {
            if ($student->department_name !== $council->department->name) {
                throw new \Exception("Student must belong to the same department as the council.");
            }
        }

        // Find the council position to assign
        $councilPosition = CouncilPosition::where('council_id', $council->id)
            ->where('position_id', $positionId)
            ->first();

        if (!$councilPosition) {
            throw new \Exception("Position not found in this council.");
        }

        if ($councilPosition->student_id) {
            throw new \Exception("This position is already assigned to another student.");
        }

        $councilPosition->student_id = $studentId;
        $councilPosition->save();

        return $councilPosition;
    }

    /**
     * Remove a student from a council position
     *
     * @param CouncilPosition $councilPosition
     * @return CouncilPosition
     */
    public function removeStudentFromPosition(CouncilPosition $councilPosition): CouncilPosition
    {
        $councilPosition->student_id = null;
        $councilPosition->save();

        return $councilPosition;
    }

    /**
     * Create default positions for a new council
     *
     * @param Council $council
     * @return void
     */
    private function createDefaultPositions(Council $council): void
    {
        // Get all defined positions
        $positions = Position::all();

        foreach ($positions as $position) {
            CouncilPosition::create([
                'council_id' => $council->id,
                'position_id' => $position->id,
                'student_id' => null, // Initially no student assigned
            ]);
        }
    }

    /**
     * Check if a student can be assigned to a council
     *
     * @param Student $student
     * @param Council $council
     * @return bool
     */
    public function canAssignStudentToCouncil(Student $student, Council $council): bool
    {
        // Check if student already belongs to another council in the same academic year
        $isAlreadyAssigned = CouncilPosition::whereHas('council', function ($query) use ($council) {
            $query->where('academic_year', $council->academic_year);
        })->where('student_id', $student->id)->exists();

        if ($isAlreadyAssigned) {
            return false;
        }

        // For departmental councils, student must belong to the same department
        if ($council->department_id && $student->department_name !== $council->department->name) {
            return false;
        }

        return true;
    }
}
