<?php

namespace App\Http\Requests\Bussiness;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrackOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_number' => ['required', 'string', 'max:14', 'min:14'],
            'email' => Auth::check()
                ? ['nullable', 'email', 'max:255']
                : ['required', 'email', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'order_number' => is_string($this->order_number)
                ? trim($this->order_number)
                : $this->order_number,
            'email' => is_string($this->email)
                ? strtolower(trim($this->email))
                : $this->email,
        ]);
    }
}
