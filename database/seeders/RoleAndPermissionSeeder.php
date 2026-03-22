<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

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
        $this->command->info('Reading permissions from JSON file...');
        $permissions = $this->getPermissionsFromJson();
        $permissionCount = $permissions->count();
        $this->command->info("Found {$permissionCount} permission(s) in JSON file");
        $this->command->line('');

        if ($permissionCount === 0) {
            $this->command->warn('No permissions found in JSON file!');
        } else {
            $this->command->info('Creating permissions from JSON and syncing to super_admin role...');
            $createdPermissions = [];

            foreach ($permissions as $permissionData) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionData['name'], 'guard_name' => 'web']
                );
                $createdPermissions[] = $permission;

                if ($permission->wasRecentlyCreated) {
                    $this->command->info("Created permission: {$permissionData['name']}");
                }
            }

            $role->syncPermissions($createdPermissions);

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            $this->command->info("Assigned {$permissionCount} permission(s) to role '{$superAdminRoleName}'");
        }

        $this->command->line('');
        $this->command->info('=== Role & Permissions seeded successfully! ===');
        $this->command->line('');
    }

    /**
     * Get permissions from JSON file.
     */
    protected function getPermissionsFromJson(): Collection
    {
        $jsonPath = database_path('data/permissions.json');

        if (! File::exists($jsonPath)) {
            return collect([]);
        }

        $jsonContent = File::get($jsonPath);
        $permissions = json_decode($jsonContent, true);

        return collect($permissions ?? []);
    }
}
