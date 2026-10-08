<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'integer', 'exists:services,id'],

            'customer_name' => ['required', 'string', 'min:3', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\s]+$/', 'min:8', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],

            'pet_name' => ['required', 'string', 'min:1', 'max:60'],
            'pet_type' => ['nullable', 'string', 'max:40', 'in:anjing,kucing,burung,ikan,kelinci,lainnya'],
            'pet_breed' => ['nullable', 'string', 'max:100'],
            'pet_weight' => ['nullable', 'numeric', 'min:0.1', 'max:150'],

            'booking_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addMonths(3)->toDateString()],
            'booking_time' => ['required', 'string', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'booking_date.before_or_equal' => 'Tanggal booking maksimal 3 bulan ke depan.',
            'booking_time.regex' => 'Format jam booking harus HH:MM (24 jam).',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, dan tanda +.',
            'pet_type.in' => 'Jenis hewan tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'service_id' => 'layanan',
            'customer_name' => 'nama Anda',
            'pet_name' => 'nama hewan',
            'pet_type' => 'jenis hewan',
            'pet_weight' => 'berat hewan',
        ];
    }
}