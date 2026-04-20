<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Form extends Component
{
    public ?User $user = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $is_active = true;
    public array $selectedRoles = [];

    public function mount(?User $user = null): void
    {
        $this->user = $user;

        if ($this->user?->exists) {
            abort_unless(auth()->user()->can('edit users'), 403);

            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->is_active = (bool) $this->user->is_active;
            $this->selectedRoles = $this->user->roles->pluck('name')->toArray();
        } else {
            abort_unless(auth()->user()->can('create users'), 403);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user?->id),
            ],
            'password' => [
                $this->user?->exists ? 'nullable' : 'required',
                'nullable',
                'string',
                'min:6',
                'same:password_confirmation',
            ],
            'is_active' => ['boolean'],
            'selectedRoles' => ['array'],
            'selectedRoles.*' => ['exists:roles,name'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $validated['is_active'],
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        if ($this->user?->exists) {
            $this->user->update($payload);
            $this->user->syncRoles($this->selectedRoles);
            session()->flash('success', 'User updated successfully.');
        } else {
            $user = User::create($payload);
            $user->syncRoles($this->selectedRoles);
            session()->flash('success', 'User created successfully.');
        }

        return $this->redirect(route('users.index'), navigate: true);
    }

    public function getRolesProperty()
    {
        return Role::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.users.form', [
            'roles' => $this->roles,
        ])->title($this->user?->exists ? 'Edit User' : 'Add User');
    }
}
