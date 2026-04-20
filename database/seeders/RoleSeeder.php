<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $supervisor = Role::findOrCreate('SOF Supervisor');
        $operations = Role::findOrCreate('Operations Officer');
        $marine = Role::findOrCreate('Marine Officer');
        $delayOfficer = Role::findOrCreate('Delay Officer');
        $viewer = Role::findOrCreate('SOF Viewer');
        $tallyClerk = Role::findOrCreate('Tally Clerk');

        $supervisor->syncPermissions([
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
        ]);

        $operations->syncPermissions([
            'sof.basic_information.view',
            'sof.basic_information.edit',
            'view statement of facts',
            'create statement of facts',
            'edit statement of facts',
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

            'view cargo tally entries',
            'create cargo tally entries',
            'edit cargo tally entries',
            'submit cargo tally entries',
            'cargo_tally.basic_information.view',
            'cargo_tally.basic_information.edit',
            'cargo_tally.cargo_details.view',
            'cargo_tally.cargo_details.edit',
        ]);

        $marine->syncPermissions([
            'sof.basic_information.view',
            'sof.basic_information.edit',
            'view statement of facts',
            'sof.tides.view',
            'sof.tides.edit',
            'sof.drafts.view',
            'sof.drafts.edit',
        ]);

        $delayOfficer->syncPermissions([
            'sof.basic_information.view',
            'sof.basic_information.edit',
            'view statement of facts',
            'sof.delays.view',
            'sof.delays.edit',
        ]);

        $tallyClerk->syncPermissions([
            'view cargo tally entries',
            'create cargo tally entries',
            'edit cargo tally entries',
            'cargo_tally.basic_information.view',
            'cargo_tally.basic_information.edit',
            'cargo_tally.cargo_details.view',
            'cargo_tally.cargo_details.edit',
        ]);

        $viewer->syncPermissions([
            'view statement of facts',
            'sof.basic_information.view',
            'sof.quantity_summary.view',
            'sof.events.view',
            'sof.loading_shifts.view',
            'sof.loading_method.view',
            'sof.tides.view',
            'sof.delays.view',
            'sof.drafts.view',
            'view cargo tally entries',
            'cargo_tally.basic_information.view',
            'cargo_tally.cargo_details.view',
        ]);
    }
}
