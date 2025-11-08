<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Level 1',
                'level' => 1,
                'description' => 'Level 1 - Basic Access',
            ],
            [
                'name' => 'Level 2',
                'level' => 2,
                'description' => 'Level 2 - Standard User',
            ],
            [
                'name' => 'Level 3',
                'level' => 3,
                'description' => 'Level 3 - Advanced User',
            ],
            [
                'name' => 'Level 4',
                'level' => 4,
                'description' => 'Level 4 - Team Member',
            ],
            [
                'name' => 'Level 5',
                'level' => 5,
                'description' => 'Level 5 - Team Leader',
            ],
            [
                'name' => 'Level 6',
                'level' => 6,
                'description' => 'Level 6 - Supervisor',
            ],
            [
                'name' => 'Level 7',
                'level' => 7,
                'description' => 'Level 7 - Manager',
            ],
            [
                'name' => 'Level 8',
                'level' => 8,
                'description' => 'Level 8 - Senior Manager',
            ],
            [
                'name' => 'Level 9',
                'level' => 9,
                'description' => 'Level 9 - Administrator',
            ],
            [
                'name' => 'Level 10',
                'level' => 10,
                'description' => 'Level 10 - Super Administrator',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
