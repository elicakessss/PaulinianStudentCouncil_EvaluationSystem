<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'School of Arts, Sciences and Teacher Education', 'abbreviation' => 'SASTE'],
            ['name' => 'School of Business, Accountancy and Hospitality Management', 'abbreviation' => 'SBAHM'],
            ['name' => 'School of Information Technology Education', 'abbreviation' => 'SITE'],
            ['name' => 'School of Nursing and Allied Health Sciences', 'abbreviation' => 'SNAHS'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
