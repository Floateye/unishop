<?php

namespace Database\Seeders;

use App\Enum\PermissionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PermissionType::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => 'web',
            ]);
        }

        $rolesWithPermissions = [
            'Admin' => PermissionType::cases(),
            'Customer' => [
                PermissionType::ProductList,
                PermissionType::ProductView,
                PermissionType::CartList,
                PermissionType::CartView,
                PermissionType::CartEdit,
                PermissionType::CartCreate,
                PermissionType::CartDelete,
                PermissionType::OrderList,
                PermissionType::OrderView,
                PermissionType::OrderCreate,
                PermissionType::OrderEdit,
                PermissionType::OrderDelete,
            ]
        ];

        foreach ($rolesWithPermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();

            if (! $role) {
                $this->command->warn("Role '{$roleName}' not found. Skipping.");
                continue;
            }

            $role->syncPermissions(array_map(fn ($permission) => $permission->value ?? $permission, $permissions));
        }

        $this->command->info('Roles and permissions have been seeded successfully.');
    }
}
