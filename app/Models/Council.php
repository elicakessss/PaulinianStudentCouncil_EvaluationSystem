<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Council extends Model
{

    protected $fillable = [
        'name',
        'academic_year',
        'status',
        'adviser_id',
        'department_id',
        'description'
    ];



    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the adviser that manages this council
     */
    public function adviser()
    {
        return $this->belongsTo(Adviser::class);
    }

    /**
     * Get the department this council belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all council positions for this council
     */
    public function councilPositions()
    {
        return $this->hasMany(CouncilPosition::class);
    }

    /**
     * Get all students in this council through positions
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'council_positions')
                    ->withPivot('position_id')
                    ->withTimestamps();
    }

    /**
     * Get all positions in this council
     */
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'council_positions')
                    ->withPivot('student_id')
                    ->withTimestamps();
    }

    /**
     * Check if this is a university-wide council
     */
    public function isUniversityWide()
    {
        return is_null($this->department_id);
    }

    /**
     * Check if the council is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get the council's full name with academic year
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->academic_year . ')';
    }

    /**
     * Get filled positions count
     */
    public function getFilledPositionsCountAttribute()
    {
        return $this->councilPositions()->whereNotNull('student_id')->count();
    }

    /**
     * Get total positions count
     */
    public function getTotalPositionsCountAttribute()
    {
        return $this->councilPositions()->count();
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by academic year
     */
    public function scopeByAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope to filter by department
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope to get university-wide councils
     */
    public function scopeUniversityWide($query)
    {
        return $query->whereNull('department_id');
    }

    /**
     * Scope to get active councils
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get councils by adviser
     */
    public function scopeByAdviser($query, $adviserId)
    {
        return $query->where('adviser_id', $adviserId);
    }

    /**
     * Get the council type (University-wide or Department)
     */
    public function getTypeAttribute()
    {
        return $this->isUniversityWide() ? 'University-wide' : 'Department';
    }

    /**
     * Get council status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' ? 'bg-success' : 'bg-secondary';
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // When deleting a council, also delete its positions
        static::deleting(function ($council) {
            $council->councilPositions()->delete();
        });
    }
}
