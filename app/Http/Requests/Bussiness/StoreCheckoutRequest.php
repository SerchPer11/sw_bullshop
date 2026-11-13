<?php

namespace App\Http\Requests\Bussiness;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'timezone' => ['nullable', 'string', 'timezone'],
            'create_account' => ['boolean'],
            'password' => ['string', 'nullable', 'required_if:create_account,true', Password::defaults()],
            'password_confirmation' => ['string', 'nullable', 'required_if:create_account,true', 'same:password'],
            'payment_method' => ['required', 'integer', 'in:1,2'],
            'coupon_code_id' => ['nullable', 'integer', 'exists:coupons,id'],
            'shipping' => ['required', 'array'],
            'shipping.name' => ['required', 'string', 'max:255'],
            'shipping.lastname' => ['required', 'string', 'max:255'],
            'shipping.street' => ['nullable', 'string', 'max:255'],
            'shipping.outdoor_number' => ['nullable', 'string', 'max:255'],
            'shipping.indoor_number' => ['nullable', 'string', 'max:255'],
            'shipping.colony' => ['nullable', 'string', 'max:255'],
            'shipping.state' => ['nullable', 'string', 'max:255'],
            'shipping.city' => ['nullable', 'string', 'max:255'],
            'shipping.zip' => ['nullable', 'string', 'max:255'],
            'shipping.phone' => ['nullable', 'string', 'max:20'],
            'cart_items' => ['required', 'array'],
            'terms_accepted' => ['required', 'boolean', 'accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Correo Electrónico',
            'create_account' => 'Crear Cuenta',
            'password' => 'Contraseña',
            'password_confirmation' => 'Confirmación de Contraseña',
            'payment_method' => 'Método de Pago',
            'coupon_code_id' => 'Código de Cupón',
            'shipping.name' => 'Nombre',
            'shipping.lastname' => 'Apellido',
            'shipping.street' => 'Calle',
            'shipping.outdoor_number' => 'Número Exterior',
            'shipping.indoor_number' => 'Número Interior',
            'shipping.colony' => 'Colonia',
            'shipping.state' => 'Estado',
            'shipping.city' => 'Ciudad',
            'shipping.zip' => 'Código Postal',
            'shipping.phone' => 'Teléfono',
        ];
    }
}
