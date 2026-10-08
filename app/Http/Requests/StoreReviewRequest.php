<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.between' => 'Rating harus antara 1 sampai 5.',
            'comment.min' => 'Ulasan minimal 10 karakter agar membantu pembeli lain.',
        ];
    }

    public function attributes(): array
    {
        return [
            'product_id' => 'produk',
            'comment' => 'ulasan',
        ];
    }
}