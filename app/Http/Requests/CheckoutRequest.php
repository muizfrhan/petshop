<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\s]+$/', 'min:8', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'regex:/^[0-9+\s]+$/', 'max:20'],

            'street' => ['required', 'string', 'max:500'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'regex:/^[0-9]{5}$/'],

            'shipping_method' => ['required', 'string', 'in:reguler,express,same_day'],
            'payment_method' => ['required', 'string', 'in:bank_transfer,qris,ewallet,cod'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'coupon_code' => ['nullable', 'string', 'max:40'],
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.regex' => 'Kode pos harus terdiri dari 5 angka.',
            'shipping_method.in' => 'Metode pengiriman tidak valid.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, dan tanda +.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'postal_code' => 'kode pos',
            'shipping_method' => 'metode pengiriman',
            'payment_method' => 'metode pembayaran',
            'whatsapp' => 'nomor WhatsApp',
        ];
    }
}