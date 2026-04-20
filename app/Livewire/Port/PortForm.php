<?php

namespace App\Livewire\Port;

use App\Models\Port;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PortForm extends Component
{
    public ?Port $port = null;

    public string $name = '';

    public string $code = '';

    public string $country = '';

    public string $city = '';

    public string $location = '';

    public string $contact_person = '';

    public string $email = '';

    public string $phone = '';

    public string $notes = '';

    public bool $is_active = true;

    public function mount(?Port $port = null): void
    {
        $this->port = $port;

        if ($this->port?->exists) {
            abort_unless(auth()->user()->can('edit ports'), 403);

            $this->name = $this->port->name ?? '';
            $this->code = $this->port->code ?? '';
            $this->country = $this->port->country ?? '';
            $this->city = $this->port->city ?? '';
            $this->location = $this->port->location ?? '';
            $this->contact_person = $this->port->contact_person ?? '';
            $this->email = $this->port->email ?? '';
            $this->phone = $this->port->phone ?? '';
            $this->notes = $this->port->notes ?? '';
            $this->is_active = (bool) $this->port->is_active;
        } else {
            abort_unless(auth()->user()->can('create ports'), 403);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('ports', 'code')->ignore($this->port?->id),
            ],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->port?->exists) {
            $this->port->update($validated);
            session()->flash('success', 'Port updated successfully.');
        } else {
            Port::create($validated);
            session()->flash('success', 'Port created successfully.');
        }

        return $this->redirect(route('ports.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.port.port-form')
            ->title($this->port?->exists ? 'Edit Port' : 'Add Port');
    }
}
