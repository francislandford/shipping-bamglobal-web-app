<?php

namespace App\Livewire\Permissions;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Form extends Component
{
    public ?Permission $permissionModel = null;

    public string $name = '';

    public function mount(?Permission $permissionModel = null): void
    {
        $this->permissionModel = $permissionModel;

        if ($this->permissionModel?->exists) {
            abort_unless(auth()->user()->can('edit permissions'), 403);
            $this->name = $this->permissionModel->name;
        } else {
            abort_unless(auth()->user()->can('create permissions'), 403);
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($this->permissionModel?->id),
            ],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->permissionModel?->exists) {
            $this->permissionModel->update($validated);
            session()->flash('success', 'Permission updated successfully.');
        } else {
            Permission::create($validated);
            session()->flash('success', 'Permission created successfully.');
        }

        return $this->redirect(route('permissions.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.permissions.form')
            ->title($this->permissionModel?->exists ? 'Edit Permission' : 'Add Permission');
    }
}
