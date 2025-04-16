<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'rating' => 'required|integer|between:1,5',
            'product_review' => 'required|string|min:4',
            // Убрали правило для author, так как он больше не приходит из формы
        ];
    }
    public function messages()
    {
        return [
            'rating.between' => 'Оценка должна быть от 1 до 5',
            'product_review.min' => 'Отзыв должен быть минимум 4 символов' // Исправил опечатку в сообщении
        ];
    }
}
