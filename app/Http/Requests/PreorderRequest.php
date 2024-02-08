<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreorderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'device_id' => ['required', 'integer', 'min:1', 'exists:devices,id'],
            'customer_identity_number' => ['required', 'string', 'size:16'],
            'customer_name' => ['required', 'string', 'min:3', 'max:100'],
            'customer_phone_number' => ['required', 'string', 'min:9', 'max:15', 'regex:/^(\+62|0)\d{8,15}$/'],
        ];
    }
}
