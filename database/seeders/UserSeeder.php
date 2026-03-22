<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder reads user data from database/data/users.json and creates/updates
     * users in the database. Each user can optionally be assigned a role using
     * Filament Shield's role system. If no role is specified, the super_admin
     * role is assigned by default.
     *
     * Expected users.json format:
     * [
     *     {
     *         "name": "John Doe",
     *         "email": "john@example.com",
     *         "password": "password123",
     *         "role": "super_admin"  // optional - defaults to super_admin
     *     }
     * ]
     */
    public function run(): void
    {
        $this->command->line('');
        $this->command->info('=== Starting User Seeder ===');
        $this->command->line('');

        $defaultRole = config('filament-shield.super_admin.name', 'super_admin');
        $this->command->info("Default role from config: {$defaultRole}");
        $this->command->line('');

        $this->command->info('Reading users from database/data/users.json...');
        $users = $this->getUsers();

        if (empty($users)) {
            $this->command->warn('No users found to seed.');

            return;
        }

        $this->command->info('Found '.count($users).' user(s) to seed.');
        $this->command->line('');

        foreach ($users as $userData) {
            $this->command->info("Processing user: {$userData['email']}");
            $this->command->line("  - Name: {$userData['name']}");

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                ]
            );

            $createdOrUpdated = $user->wasRecentlyCreated ? 'Created' : 'Updated';
            $this->command->info("  - User {$createdOrUpdated}: ID {$user->id}");

            $roleName = $userData['role'] ?? $defaultRole;
            $this->command->info("  - Assigning role: {$roleName}");

            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $user->assignRole($role);
                $this->command->info('  - Role assigned successfully!');
            } else {
                $this->command->warn("  - Role '{$roleName}' not found. Skipping role assignment.");
            }

            $this->command->line('');
        }

        $this->command->info('=== Users seeded successfully! ===');
        $this->command->line('');
    }

    /**
     * Read and parse the users.json file.
     *
     * @return array Array of user data from the JSON file
     */
    protected function getUsers(): array
    {
        $path = database_path('data/users.json');

        if (! File::exists($path)) {
            $this->command->error('users.json file not found in database/data/');

            return [];
        }

        $content = File::get($path);
        $users = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Failed to parse users.json: '.json_last_error_msg());

            return [];
        }

        return $users;
    }
}
