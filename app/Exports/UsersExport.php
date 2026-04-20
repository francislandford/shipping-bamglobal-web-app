<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        public ?string $search = null,
        public ?string $role = null,
        public ?string $status = null
    ) {
    }

    public function query()
    {
        return User::query()
            ->with('roles')
            ->when($this->search, function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->role, function (Builder $query) {
                $query->role($this->role);
            })
            ->when($this->status, function (Builder $query) {
                $query->where('is_active', $this->status === 'active');
            })
            ->select('users.*');
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Roles',
            'Status',
            'Created At',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->roles->pluck('name')->implode(', '),
            $user->is_active ? 'Active' : 'Inactive',
            optional($user->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
