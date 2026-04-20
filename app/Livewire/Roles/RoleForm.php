<?php

namespace App\Livewire\Roles;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleForm extends Component
{
    public ?Role $role = null;

    public string $name = '';

    public array $selectedPermissions = [];

    public function mount(?Role $role = null): void
    {
        $this->role = $role;

        if ($this->role?->exists) {
            abort_unless(auth()->user()->can('edit roles'), 403);

            $this->name = $this->role->name;
            $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
        } else {
            abort_unless(auth()->user()->can('create roles'), 403);
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->role?->id),
            ],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['exists:permissions,name'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->role?->exists) {
            $this->role->update([
                'name' => $validated['name'],
            ]);
            $this->role->syncPermissions($this->selectedPermissions);
            session()->flash('success', 'Role updated successfully.');
        } else {
            $role = Role::create([
                'name' => $validated['name'],
            ]);
            $role->syncPermissions($this->selectedPermissions);
            session()->flash('success', 'Role created successfully.');
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $this->redirect(route('roles.index'), navigate: true);
    }

    public function getPermissionsProperty()
    {
        return Permission::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.roles.form', [
            'permissions' => $this->permissions,
        ])->title($this->role?->exists ? 'Edit Role' : 'Add Role');
    }
}
