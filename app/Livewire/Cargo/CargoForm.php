<?php

namespace App\Livewire\Cargo;

use App\Models\Cargo;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CargoForm extends Component
{
    public ?Cargo $cargo = null;

    public string $name = '';
    public string $code = '';
    public string $type = '';
    public string $uom = 'WMT';
    public string $description = '';
    public bool $is_active = true;

    public function mount(?Cargo $cargo = null): void
    {
        $this->cargo = $cargo;

        if ($this->cargo?->exists) {
            abort_unless(auth()->user()->can('edit cargos'), 403);

            $this->name = $this->cargo->name ?? '';
            $this->code = $this->cargo->code ?? '';
            $this->type = $this->cargo->type ?? '';
            $this->uom = $this->cargo->uom ?? 'WMT';
            $this->description = $this->cargo->description ?? '';
            $this->is_active = (bool) $this->cargo->is_active;
        } else {
            abort_unless(auth()->user()->can('create cargos'), 403);
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
                Rule::unique('cargos', 'code')->ignore($this->cargo?->id),
            ],
            'type' => ['nullable', 'string', 'max:255'],
            'uom' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->cargo?->exists) {
            $this->cargo->update($validated);
            session()->flash('success', 'Cargo updated successfully.');
        } else {
            Cargo::create($validated);
            session()->flash('success', 'Cargo created successfully.');
        }

        return $this->redirect(route('cargos.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cargo.cargo-form')
            ->title($this->cargo?->exists ? 'Edit Cargo' : 'Add Cargo');
    }
}
