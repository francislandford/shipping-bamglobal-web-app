<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'export users',
            'print users',
            'toggle users',

            // add more module permissions here later
            'view ships',
            'create ships',
            'edit ships',
            'delete ships',
            'export ships',
            'print ships',
            'toggle ships',

            'view agencies',
            'create agencies',
            'edit agencies',
            'delete agencies',
            'export agencies',
            'print agencies',
            'toggle agencies',

            'view ports',
            'create ports',
            'edit ports',
            'delete ports',
            'export ports',
            'print ports',
            'toggle ports',

            'view piers',
            'create piers',
            'edit piers',
            'delete piers',
            'export piers',
            'print piers',
            'toggle piers',

            'view cargo tally entries',
            'create cargo tally entries',
            'edit cargo tally entries',
            'delete cargo tally entries',
            'export cargo tally entries',
            'print cargo tally entries',
            'toggle cargo tally entries',

            'view cargo tally entries',
            'create cargo tally entries',
            'edit cargo tally entries',
            'submit cargo tally entries',

            'cargo_tally.basic_information.view',
            'cargo_tally.basic_information.edit',

            'cargo_tally.cargo_details.view',
            'cargo_tally.cargo_details.edit',

            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'export roles',
            'print roles',

            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'export permissions',
            'print permissions',

            'view statement of facts',
            'create statement of facts',
            'edit statement of facts',
            'delete statement of facts',
            'export statement of facts',
            'print statement of facts',
            'toggle statement of facts',

            'view cargos',
            'create cargos',
            'edit cargos',
            'delete cargos',
            'export cargos',
            'print cargos',
            'toggle cargos',

            'view statement of facts',
            'create statement of facts',
            'edit statement of facts',
            'submit statement of facts',

            'sof.basic_information.view',
            'sof.basic_information.edit',

            'sof.quantity_summary.view',
            'sof.quantity_summary.edit',

            'sof.events.view',
            'sof.events.edit',

            'sof.loading_shifts.view',
            'sof.loading_shifts.edit',

            'sof.loading_method.view',
            'sof.loading_method.edit',

            'sof.tides.view',
            'sof.tides.edit',

            'sof.delays.view',
            'sof.delays.edit',

            'sof.drafts.view',
            'sof.drafts.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Give Admin all permissions
        $adminRole->syncPermissions(Permission::all());

        // Create or update admin user
        $user = User::updateOrCreate(
            ['email' => 'francislandford466@gmail.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );

        // Assign Admin role to user
        $user->syncRoles([$adminRole->name]);

        // Optional: direct permissions too, but usually role is enough
        // $user->syncPermissions(Permission::all());

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
