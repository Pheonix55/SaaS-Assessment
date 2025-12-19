<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Company;
use Spatie\Permission\Models\{Permission, Role};

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            'manage-company',
            'manage-billing',
            'invite-users',
            'view-support',
            'reply-support',
            'upload-files',
            'view-dashboard',
        ];

        // Create permissions globally
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Define roles and associated permissions
        $rolesPermissions = [
            'admin' => ['manage-company','manage-billing','invite-users','view-support','reply-support','upload-files','view-dashboard'],
            'support' => ['view-support','reply-support','upload-files'],
            'user' => ['upload-files','view-dashboard'],
        ];

        // Assign roles and permissions per company
        $companies = Company::all();

        foreach ($companies as $company) {
            foreach ($rolesPermissions as $roleName => $perms) {

                // Create role for this company
                $role = Role::firstOrCreate(
                    [
                        'name' => $roleName,
                        'company_id' => $company->id,
                        'guard_name' => 'web', // always include guard
                    ]
                );

                // Assign permissions
                $role->syncPermissions($perms);
            }
        }
    }
}
