<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DecreaseProductRequest extends FormRequest
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
            'product_id' => 'integer|exists:products,id',
        ];
    }
    public function messages()
    {
        return [
            'product_id.integer' => 'id продукта может содержать только цифры',
            'product_id.exists' => 'продукта c таким id не существует',

        ];
    }
}
