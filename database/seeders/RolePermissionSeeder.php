<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;
use Spatie\Permission\Models\{Permission, Role};
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage-companies',
            'approve-company',
            'suspend-company',
            'view-all-companies',
            'view-system-dashboard',
            'manage-users',
            'invite-users',
            'view-dashboard',
            'manage-plans',
            'manage-subscriptions',
            'view-audit-logs',
            'create-threads',
            'view-threads',
            'reply-threads',
            'manage-threads',
            'delete-threads',
            'manage-transactions',
            'upload-files',
            'manage-files',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
        Feature::insert([
            ['key' => 'invite_users', 'name' => 'Invite Users'],
            ['key' => 'support', 'name' => 'Support'],
            ['key' => 'billing', 'name' => 'Billing'],
        ]);
        $superAdminRole = Role::firstOrCreate([
            'name' => 'SUPER_ADMIN',
            'guard_name' => 'web',
        ]);

        $superAdminRole->syncPermissions($permissions);
    }
}
