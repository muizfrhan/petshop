<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orders) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $query = Order::query()
            ->with('items')
            ->withCount('items')
            ->latest();

        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            // Guest hanya boleh melihat pesanan yang tercatat di sesinya.
            $guestOrder = $request->session()->get('guest_order');

            abort_if(! $guestOrder, 403, 'Silakan masuk untuk melihat riwayat pesanan Anda.');

            $query->where('order_number', $guestOrder);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', [
            'orders' => $orders,
            'statuses' => config('petshop.order_statuses'),
            'currentStatus' => $status,
        ]);
    }

    public function show(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        $order->load('items.product.primaryImage', 'payment');

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function pay(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($request, $order);

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Pesanan ini tidak menunggu pembayaran.');
        }

        // Pembayaran manual dikonfirmasi admin, jadi tandai "menunggu verifikasi".
        $order->payment()->update(['status' => 'pending']);

        return back()->with('success', 'Bukti pembayaran akan diverifikasi oleh admin. Pesanan Anda sudah tercatat.');
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($request, $order);

        if (! $order->isCancellable()) {
            return back()->with('error', 'Pesanan ini sudah tidak bisa dibatalkan.');
        }

        $this->orders->cancel($order);

        return back()->with('success', 'Pesanan '.$order->order_number.' telah dibatalkan.');
    }

    /**
     * Cegah user mengintip pesanan orang lain lewat menebak URL.
     */
    private function authorizeOrder(Request $request, Order $order): void
    {
        $user = $request->user();

        if ($user) {
            abort_if(
                $order->user_id !== $user->id && ! $user->isAdmin(),
                403,
                'Pesanan ini bukan milik Anda.',
            );

            return;
        }

        abort_if(
            $request->session()->get('guest_order') !== $order->order_number,
            403,
            'Silakan masuk untuk melihat pesanan ini.',
        );
    }
}