<?php

namespace App\Livewire\Ships;

use App\Models\Ship;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ShipForm extends Component
{
    public ?Ship $ship = null;

    public string $name = '';
    public string $imo_number = '';
    public string $call_sign = '';
    public string $flag = '';
    public string $type = '';
    public string $owner = '';
    public bool $is_active = true;

    public function mount(?Ship $ship = null): void
    {
        $this->ship = $ship;

        if ($this->ship?->exists) {
            abort_unless(auth()->user()->can('edit ships'), 403);

            $this->name = $this->ship->name ?? '';
            $this->imo_number = $this->ship->imo_number ?? '';
            $this->call_sign = $this->ship->call_sign ?? '';
            $this->flag = $this->ship->flag ?? '';
            $this->type = $this->ship->type ?? '';
            $this->owner = $this->ship->owner ?? '';
            $this->is_active = (bool) $this->ship->is_active;
        } else {
            abort_unless(auth()->user()->can('create ships'), 403);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'imo_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('ships', 'imo_number')->ignore($this->ship?->id),
            ],
            'call_sign' => ['nullable', 'string', 'max:255'],
            'flag' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'owner' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->ship?->exists) {
            $this->ship->update($validated);
            session()->flash('success', 'Ship updated successfully.');
        } else {
            Ship::create($validated);
            session()->flash('success', 'Ship created successfully.');
        }

        return $this->redirect(route('ships.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.ships.form')
            ->title($this->ship?->exists ? 'Edit Ship' : 'Add Ship');
    }
}
