<?php

namespace App\Livewire\Agency;

use App\Models\Agency;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AgencyForm extends Component
{
    public ?Agency $agency = null;

    public string $name = '';
    public string $code = '';
    public string $contact_person = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $notes = '';
    public bool $is_active = true;

    public function mount(?Agency $agency = null): void
    {
        $this->agency = $agency;

        if ($this->agency?->exists) {
            abort_unless(auth()->user()->can('edit agencies'), 403);

            $this->name = $this->agency->name ?? '';
            $this->code = $this->agency->code ?? '';
            $this->contact_person = $this->agency->contact_person ?? '';
            $this->email = $this->agency->email ?? '';
            $this->phone = $this->agency->phone ?? '';
            $this->address = $this->agency->address ?? '';
            $this->notes = $this->agency->notes ?? '';
            $this->is_active = (bool) $this->agency->is_active;
        } else {
            abort_unless(auth()->user()->can('create agencies'), 403);
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
                Rule::unique('agencies', 'code')->ignore($this->agency?->id),
            ],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->agency?->exists) {
            $this->agency->update($validated);
            session()->flash('success', 'Agency updated successfully.');
        } else {
            Agency::create($validated);
            session()->flash('success', 'Agency created successfully.');
        }

        return $this->redirect(route('agencies.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.agency.agency-form')
            ->title($this->agency?->exists ? 'Edit Agency' : 'Add Agency');
    }
}
