<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', 'alpha_dash', Rule::unique('categories', 'slug')->ignore($categoryId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:60'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ];
    }

    public function attributes(): array
    {
        return [
            'sort_order' => 'urutan',
            'icon' => 'ikon',
        ];
    }
}