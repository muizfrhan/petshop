<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceBooking;
use App\Notifications\BookingConfirmedNotification;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function create(array $data, ?int $userId = null): ServiceBooking
    {
        $service = Service::findOrFail($data['service_id']);

        $booking = ServiceBooking::create([
            'user_id' => $userId,
            'service_id' => $service->id,
            'customer_name' => $data['customer_name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'pet_name' => $data['pet_name'],
            'pet_type' => $data['pet_type'] ?? null,
            'pet_breed' => $data['pet_breed'] ?? null,
            'pet_weight' => $data['pet_weight'] ?? null,
            'booking_date' => $data['booking_date'],
            'booking_time' => $data['booking_time'],
            'notes' => $data['notes'] ?? null,
            'status' => ServiceBooking::STATUS_PENDING,
            'price' => $service->price,
        ]);

        return $booking;
    }

    public function changeStatus(ServiceBooking $booking, string $status, ?string $adminNote = null): ServiceBooking
    {
        $booking->status = $status;

        if ($adminNote !== null) {
            $booking->admin_note = $adminNote;
        }

        $booking->save();

        if ($status === ServiceBooking::STATUS_CONFIRMED && $booking->user) {
            $booking->user->notify(new BookingConfirmedNotification($booking));
        }

        return $booking->refresh();
    }

    public function reschedule(ServiceBooking $booking, string $date, string $time, ?string $adminNote = null): ServiceBooking
    {
        $booking->booking_date = $date;
        $booking->booking_time = $time;
        $booking->status = ServiceBooking::STATUS_RESCHEDULED;

        if ($adminNote !== null) {
            $booking->admin_note = $adminNote;
        }

        return $booking->refresh();
    }
}