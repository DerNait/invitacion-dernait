<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRsvpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $attending = $this->boolean('attending');
        $guests = $attending ? (int) $this->input('guests_count', 0) : 0;

        // Deja el arreglo de nombres con exactamente "guests" elementos (recortados).
        $names = collect($this->input('guest_names', []))
            ->take($guests)
            ->map(fn ($n) => is_string($n) ? trim($n) : $n)
            ->values()
            ->all();

        $this->merge([
            'email' => is_string($this->email) ? strtolower(trim($this->email)) : $this->email,
            'guests_count' => $guests,
            'guest_names' => $names,
        ]);
    }

    public function rules(): array
    {
        $maxGuests = (int) config('event.rsvp.max_guests', 5);
        $needsNames = $this->boolean('attending') && (int) $this->input('guests_count', 0) > 0;

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'attending' => ['required', 'boolean'],
            'guests_count' => ['nullable', 'integer', 'min:0', 'max:' . $maxGuests],
            'guest_names' => [$needsNames ? 'required' : 'nullable', 'array'],
            'guest_names.*' => ['required', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Por favor escribe tu nombre.',
            'email.required' => 'Por favor escribe tu correo.',
            'email.email' => 'Escribe un correo válido.',
            'attending.required' => 'Indica si podrás asistir.',
            'guests_count.max' => 'El máximo de acompañantes es :max.',
            'guest_names.required' => 'Escribe el nombre de tus acompañantes.',
            'guest_names.*.required' => 'Falta el nombre de un acompañante.',
        ];
    }

    public function attributes(): array
    {
        return [
            'guest_names.0' => 'nombre del acompañante 1',
            'guest_names.1' => 'nombre del acompañante 2',
            'guest_names.2' => 'nombre del acompañante 3',
            'guest_names.3' => 'nombre del acompañante 4',
            'guest_names.4' => 'nombre del acompañante 5',
        ];
    }
}
