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
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|between:1,5',
            'product_review' => 'required|string|min:4',


        ];
    }
    public function messages():array
    {
        return [
            'rating.between' => 'Оценка должна быть от 1 до 5',
            'product_review.min' => 'Отзыв должен быть минимум 4 символов'
        ];
    }
}
