<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:unpaid,pending,paid,failed,refunded'],
            'method' => ['nullable', 'string', 'in:bank_transfer,qris,ewallet,cod'],
            'q' => ['nullable', 'string', 'max:100'],
        ], [], ['method' => 'metode']);

        $payments = Payment::query()
            ->with('order')
            ->when($validated['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($validated['method'] ?? null, fn ($q, $method) => $q->where('method', $method))
            ->when($validated['q'] ?? null, fn ($q, $keyword) => $q->whereHas('order', fn ($o) => $o
                ->where('order_number', 'ilike', '%'.$keyword.'%')
                ->orWhere('customer_name', 'ilike', '%'.$keyword.'%')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', [
            'payments' => $payments,
            'filters' => $validated,
            'methods' => config('petshop.payment_methods'),
            'counts' => Payment::query()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(Payment $payment): View
    {
        $payment->load('order.items');

        return view('admin.payments.show', ['payment' => $payment]);
    }
}