<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceBooking;
use App\Services\BookingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function __construct(private readonly BookingService $bookings) {}

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:pending,confirmed,rescheduled,completed,cancelled'],
            'q' => ['nullable', 'string', 'max:100'],
            'date' => ['nullable', 'date'],
        ], [], ['date' => 'tanggal']);

        $bookings = ServiceBooking::query()
            ->with(['service', 'user'])
            ->when($validated['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($validated['date'] ?? null, fn ($q, $date) => $q->whereDate('booking_date', $date))
            ->when($validated['q'] ?? null, fn ($q, $keyword) => $q->where(function ($sub) use ($keyword) {
                $sub->where('customer_name', 'ilike', '%'.$keyword.'%')
                    ->orWhere('pet_name', 'ilike', '%'.$keyword.'%')
                    ->orWhere('phone', 'ilike', '%'.$keyword.'%');
            }))
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'statuses' => config('petshop.booking_statuses'),
            'counts' => ServiceBooking::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'filters' => $validated,
        ]);
    }

    public function show(ServiceBooking $booking): View
    {
        $booking->load(['service', 'user']);

        return view('admin.bookings.show', [
            'booking' => $booking,
            'statuses' => config('petshop.booking_statuses'),
        ]);
    }

    public function updateStatus(Request $request, ServiceBooking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:confirmed,completed,cancelled'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ], [], ['status' => 'status', 'admin_note' => 'catatan']);

        $this->bookings->changeStatus($booking, $validated['status'], $validated['admin_note'] ?? null);

        return back()->with('success', 'Status booking diperbarui menjadi "'.$booking->fresh()->statusLabel().'".');
    }

    public function reschedule(Request $request, ServiceBooking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'string', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'booking_date' => 'tanggal baru',
            'booking_time' => 'jam baru',
            'admin_note' => 'catatan',
        ]);

        $this->bookings->reschedule(
            $booking,
            $validated['booking_date'],
            $validated['booking_time'],
            $validated['admin_note'] ?? null,
        );

        return back()->with('success', 'Booking dijadwalkan ulang.');
    }
}