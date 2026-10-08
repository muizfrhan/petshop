<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceBooking;
use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $bookings) {}

    public function create(Request $request): View
    {
        $services = Service::active()->ordered()->get();

        $selected = null;

        if ($slug = $request->query('layanan')) {
            $selected = $services->firstWhere('slug', $slug);
        }

        return view('booking.create', [
            'services' => $services,
            'selectedService' => $selected,
            'user' => $request->user(),
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = $this->bookings->create(
            $request->validated(),
            $request->user()?->id,
        );

        return redirect()->route('booking.show', $booking)
            ->with('success', 'Booking berhasil dibuat. Tim kami akan menghubungi Anda untuk konfirmasi.');
    }

    public function show(Request $request, ServiceBooking $booking): View
    {
        $user = $request->user();

        abort_if(
            $booking->user_id !== $user?->id && ! $user?->isAdmin(),
            403,
            'Booking ini bukan milik Anda.',
        );

        $booking->load('service');

        return view('booking.show', [
            'booking' => $booking,
        ]);
    }
}