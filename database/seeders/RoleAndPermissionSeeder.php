<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates the super_admin role and assigns all available permissions to it.
     * It pulls the role name from the Filament Shield configuration.
     */
    public function run(): void
    {
        $this->command->line('');
        $this->command->info('=== Starting Role & Permission Seeder ===');
        $this->command->line('');

        $superAdminRoleName = config('filament-shield.super_admin.name', 'super_admin');
        $this->command->info("Getting super_admin role name from config: {$superAdminRoleName}");
        $this->command->line('');

        $this->command->info('Checking if super_admin role exists...');
        $role = Role::firstOrCreate(
            ['name' => $superAdminRoleName, 'guard_name' => 'web']
        );

        if ($role->wasRecentlyCreated) {
            $this->command->info("Created new role: {$superAdminRoleName}");
        } else {
            $this->command->info("Role '{$superAdminRoleName}' already exists");
        }

        $this->command->line('');
        $this->command->info('Fetching all permissions from database...');
        $permissions = $this->getAllPermissions();
        $permissionCount = $permissions->count();
        $this->command->info("Found {$permissionCount} permission(s)");
        $this->command->line('');

        if ($permissionCount === 0) {
            $this->command->warn('No permissions found! Run "php artisan shield:generate --all" first to generate permissions.');
        } else {
            $this->command->info('Syncing all permissions to super_admin role...');
            $role->syncPermissions($permissions);
            $this->command->info("Assigned {$permissionCount} permission(s) to role '{$superAdminRoleName}'");
        }

        $this->command->line('');
        $this->command->info('=== Role & Permissions seeded successfully! ===');
        $this->command->line('');
    }

    /**
     * Get all existing permissions from the database.
     *
     * @return Collection|Permission[]
     */
    protected function getAllPermissions(): Collection
    {
        return Permission::all();
    }
}
