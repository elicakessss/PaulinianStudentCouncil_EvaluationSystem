<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run()
    {
        $positions = [
            // Executive Branch
            [
                'name' => 'President',
                'description' => 'Chief executive officer of the student council',
                'branch' => 'Executive',
                'level' => 1
            ],
            [
                'name' => 'Vice President',
                'description' => 'Second in command, assists the president',
                'branch' => 'Executive',
                'level' => 2
            ],
            [
                'name' => 'Secretary',
                'description' => 'Keeps records and manages communications',
                'branch' => 'Executive',
                'level' => 3
            ],
            [
                'name' => 'Treasurer',
                'description' => 'Manages finances and budget',
                'branch' => 'Executive',
                'level' => 4
            ],
            [
                'name' => 'Auditor',
                'description' => 'Reviews financial records and transactions',
                'branch' => 'Executive',
                'level' => 5
            ],

            // Legislative Branch
            [
                'name' => 'Senator - Year Level 1',
                'description' => 'Represents first year students',
                'branch' => 'Legislative',
                'level' => 1
            ],
            [
                'name' => 'Senator - Year Level 2',
                'description' => 'Represents second year students',
                'branch' => 'Legislative',
                'level' => 2
            ],
            [
                'name' => 'Senator - Year Level 3',
                'description' => 'Represents third year students',
                'branch' => 'Legislative',
                'level' => 3
            ],
            [
                'name' => 'Senator - Year Level 4',
                'description' => 'Represents fourth year students',
                'branch' => 'Legislative',
                'level' => 4
            ],

            // Committee Heads
            [
                'name' => 'Committee Head - Academic Affairs',
                'description' => 'Oversees academic-related activities and programs',
                'branch' => 'Committee',
                'level' => 1
            ],
            [
                'name' => 'Committee Head - Student Affairs',
                'description' => 'Manages student welfare and activities',
                'branch' => 'Committee',
                'level' => 1
            ],
            [
                'name' => 'Committee Head - Finance',
                'description' => 'Assists in financial planning and oversight',
                'branch' => 'Committee',
                'level' => 1
            ],
            [
                'name' => 'Committee Head - External Affairs',
                'description' => 'Manages external partnerships and relations',
                'branch' => 'Committee',
                'level' => 1
            ],
            [
                'name' => 'Committee Head - Sports and Recreation',
                'description' => 'Organizes sports and recreational activities',
                'branch' => 'Committee',
                'level' => 1
            ],
            [
                'name' => 'Committee Head - Arts and Culture',
                'description' => 'Promotes arts and cultural activities',
                'branch' => 'Committee',
                'level' => 1
            ],

            // Special Positions
            [
                'name' => 'Public Information Officer',
                'description' => 'Manages public relations and communications',
                'branch' => 'Special',
                'level' => 1
            ],
            [
                'name' => 'Sergeant at Arms',
                'description' => 'Maintains order during meetings and events',
                'branch' => 'Special',
                'level' => 1
            ],
            [
                'name' => 'Business Manager',
                'description' => 'Oversees business operations and partnerships',
                'branch' => 'Special',
                'level' => 1
            ]
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(
                ['name' => $position['name']],
                $position
            );
        }
    }
}
