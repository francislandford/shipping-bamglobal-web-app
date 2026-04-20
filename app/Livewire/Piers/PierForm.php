<?php

namespace App\Livewire\Piers;

use App\Models\Pier;
use App\Models\Port;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PierForm extends Component
{
    public ?Pier $pier = null;

    public string $port_id = '';
    public string $name = '';
    public string $code = '';
    public string $location = '';
    public string $capacity = '';
    public string $contact_person = '';
    public string $email = '';
    public string $phone = '';
    public string $notes = '';
    public bool $is_active = true;

    public function mount(?Pier $pier = null): void
    {
        $this->pier = $pier;

        if ($this->pier?->exists) {
            abort_unless(auth()->user()->can('edit piers'), 403);

            $this->port_id = (string) ($this->pier->port_id ?? '');
            $this->name = $this->pier->name ?? '';
            $this->code = $this->pier->code ?? '';
            $this->location = $this->pier->location ?? '';
            $this->capacity = $this->pier->capacity ? (string) $this->pier->capacity : '';
            $this->contact_person = $this->pier->contact_person ?? '';
            $this->email = $this->pier->email ?? '';
            $this->phone = $this->pier->phone ?? '';
            $this->notes = $this->pier->notes ?? '';
            $this->is_active = (bool) $this->pier->is_active;
        } else {
            abort_unless(auth()->user()->can('create piers'), 403);
        }
    }

    public function rules(): array
    {
        return [
            'port_id' => ['required', 'exists:ports,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('piers', 'code')->ignore($this->pier?->id),
            ],
            'location' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:0'],
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

        if ($this->pier?->exists) {
            $this->pier->update($validated);
            session()->flash('success', 'Pier updated successfully.');
        } else {
            Pier::create($validated);
            session()->flash('success', 'Pier created successfully.');
        }

        return $this->redirect(route('piers.index'), navigate: true);
    }

    public function getPortsProperty()
    {
        return Port::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.piers.pier-form', [
            'ports' => $this->ports,
        ])->title($this->pier?->exists ? 'Edit Pier' : 'Add Pier');
    }
}
