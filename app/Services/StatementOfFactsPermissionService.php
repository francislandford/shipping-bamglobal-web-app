<?php

namespace App\Services;

use App\Models\User;

class StatementOfFactsPermissionService
{
    public const SECTIONS = [
        'basic_information',
        'quantity_summary',
        'events',
        'loading_shifts',
        'loading_method',
        'tides',
        'delays',
        'drafts',
    ];

    public function build(User $user): array
    {
        $sections = [];

        foreach (self::SECTIONS as $section) {
            $sections[$section] = [
                'can_view' => $user->can("sof.{$section}.view"),
                'can_edit' => $user->can("sof.{$section}.edit"),
            ];
        }

        return [
            'can_view_form' => $user->can('view statement of facts'),
            'can_create' => $user->can('create statement of facts'),
            'can_edit_form' => $user->can('edit statement of facts'),
            'can_submit_final' => $user->can('submit statement of facts'),
            'sections' => $sections,
        ];
    }
}
