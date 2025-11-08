<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $level10Role = Role::where('name', 'Level 10')->first();
        $level8Role = Role::where('name', 'Level 8')->first();
        $level7Role = Role::where('name', 'Level 7')->first();
        $level1Role = Role::where('name', 'Level 1')->first();

        // Create Super Admin User (Level 10) - No creator
        $superAdmin = User::firstOrCreate(
            ['email' => 'esanet.id@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('kanggawe123#$'),
                'role_id' => $level10Role->id,
                'created_by' => null,
            ]
        );

        // Create Level 8 User (Created by Super Admin)
        $level8User = User::firstOrCreate(
            ['email' => 'bagencocok@gmail.com'],
            [
                'name' => 'Level 8 User',
                'password' => Hash::make('password'),
                'role_id' => $level8Role->id,
                'created_by' => $superAdmin->id,
            ]
        );

        // Create another Level 8 User (Created by Super Admin)
        $level8User2 = User::firstOrCreate(
            ['email' => 'bundasextra@gmail.com'],
            [
                'name' => 'Level 8 User 2',
                'password' => Hash::make('password'),
                'role_id' => $level8Role->id,
                'created_by' => $superAdmin->id,
            ]
        );

        // Create Manager User (Level 7) - Created by Super Admin
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'role_id' => $level7Role->id,
                'created_by' => $superAdmin->id,
            ]
        );

        // Create Regular User (Level 1) - Created by Level 8 User
        User::firstOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'User 1 (by Level 8-1)',
                'password' => Hash::make('password'),
                'role_id' => $level1Role->id,
                'created_by' => $level8User->id, // Created by first Level 8
            ]
        );

        // Create another Regular User (Level 1) - Created by second Level 8 User
        User::firstOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'User 2 (by Level 8-2)',
                'password' => Hash::make('password'),
                'role_id' => $level1Role->id,
                'created_by' => $level8User2->id, // Created by second Level 8
            ]
        );

        // Create another Regular User (Level 1) - Created by Manager
        User::firstOrCreate(
            ['email' => 'user3@example.com'],
            [
                'name' => 'User 3 (by Manager)',
                'password' => Hash::make('password'),
                'role_id' => $level1Role->id,
                'created_by' => $managerUser->id, // Created by Manager
            ]
        );
    }
}
