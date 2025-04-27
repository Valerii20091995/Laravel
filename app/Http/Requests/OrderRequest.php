<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'name' => 'required|string|min:4|max:255',
            'phone' => 'required|string|min:11|regex:/^([0-9\s\-\+\(\)]*)$/',
            'comment' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'phone.regex' => 'Номер телефона имеет неверный формат',
            'phone.min' => 'Номер должен быть минимум из 11 цифр',
            'name.min' => 'Имя должен состоять минимум из 4 букв'
        ];
    }
}
