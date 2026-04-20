<?php

namespace App\Services;

use App\Models\User;

class CargoTallyPermissionService
{
    public const SECTIONS = [
        'basic_information',
        'cargo_details',
    ];

    public function build(User $user): array
    {
        $sections = [];

        foreach (self::SECTIONS as $section) {
            $sections[$section] = [
                'can_view' => $user->can("cargo_tally.{$section}.view"),
                'can_edit' => $user->can("cargo_tally.{$section}.edit"),
            ];
        }

        return [
            'can_view_form' => $user->can('view cargo tally entries'),
            'can_create' => $user->can('create cargo tally entries'),
            'can_edit_form' => $user->can('edit cargo tally entries'),
            'can_submit_final' => $user->can('submit cargo tally entries'),
            'sections' => $sections,
        ];
    }
}
