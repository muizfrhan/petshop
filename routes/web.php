<?php

use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Publik
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('beranda');
Route::post('/langganan', NewsletterController::class)->name('langganan.store');

/* ---------- Auth (guest) ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/masuk', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:6,1');

    Route::get('/daftar', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/daftar', [RegisteredUserController::class, 'store'])->middleware('throttle:6,1');

    Route::get('/lupa-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/lupa-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
});

/* ---------- Auth (terdaftar) ---------- */
Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/verifikasi-email', [VerifyEmailController::class, 'notice'])
        ->name('verification.notice');
    Route::get('/verifikasi-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/verifikasi-email/kirim', [VerifyEmailController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');

    Route::get('/reset-password/{token}', [PasswordResetLinkController::class, 'edit'])->name('password.edit');
    Route::post('/reset-password', [PasswordResetLinkController::class, 'update'])->name('password.update');
});

/* ---------- Shop ---------- */
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('produk.show');
Route::get('/kategori/{slug}', [ProductController::class, 'category'])->name('kategori.show');

/* ---------- Layanan ---------- */
Route::get('/layanan', [ServiceController::class, 'index'])->name('layanan.index');
Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('layanan.show');

/* ---------- Artikel ---------- */
Route::get('/artikel', [ArticleController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('artikel.show');

/* ---------- Static ---------- */
Route::view('/tentang-kami', 'pages.tentang-kami')->name('tentang-kami');
Route::view('/kontak', 'pages.kontak')->name('kontak');
Route::view('/syarat-ketentuan', 'pages.syarat-ketentuan')->name('syarat-ketentuan');
Route::view('/kebijakan-privasi', 'pages.kebijakan-privasi')->name('kebijakan-privasi');
Route::view('/bantuan-faq', 'pages.bantuan-faq')->name('bantuan-faq');

/* ---------- Cart (guest & login) ---------- */
Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang', [CartController::class, 'store'])->name('keranjang.store');
Route::patch('/keranjang/{id}', [CartController::class, 'update'])->name('keranjang.update');
Route::delete('/keranjang/{id}', [CartController::class, 'destroy'])->name('keranjang.destroy');
Route::delete('/keranjang', [CartController::class, 'clear'])->name('keranjang.clear');
    Route::post('/keranjang/kupon', [CartController::class, 'applyCoupon'])->name('keranjang.kupon');
    Route::get('/keranjang/ringkasan', [CartController::class, 'summary'])->name('keranjang.summary');
Route::post('/keranjang/kerjakan-sekarang/{id}', [CartController::class, 'buyNow'])->name('keranjang.buy-now');

    /* Beli sekarang langsung dari halaman detail produk */
    Route::get('/beli-sekarang/{product}', [CartController::class, 'buyNowFromProduct'])
        ->name('keranjang.buy-now.index');

/* ---------- Booking (guest & login) ---------- */
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');

/* ---------- Checkout (guest boleh, divalidasi ketat di backend) ---------- */
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/ringkasan', [CheckoutController::class, 'summary'])->name('checkout.summary');

/* ---------- Pesanan ---------- */
Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('pesanan.show');
Route::post('/pesanan/{order}/bayar', [OrderController::class, 'pay'])->name('pesanan.pay');
Route::post('/pesanan/{order}/batal', [OrderController::class, 'cancel'])->name('pesanan.cancel');

/* ---------- Area member (wajib login) ---------- */
Route::middleware('auth')->group(function () {
    Route::get('/akun', [AccountController::class, 'index'])->name('akun.index');
    Route::get('/akun/profil', [AccountController::class, 'edit'])->name('akun.edit');
    Route::put('/akun/profil', [AccountController::class, 'update'])->name('akun.update');
    Route::put('/akun/password', [AccountController::class, 'updatePassword'])->name('akun.password');
    Route::delete('/akun', [AccountController::class, 'destroy'])->name('akun.destroy');

    Route::get('/akun/pesanan', [OrderController::class, 'index'])->name('akun.pesanan');
    Route::get('/akun/notifikasi', [AccountController::class, 'notifications'])->name('akun.notifikasi');

    /* Wishlist */
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/wishlist/ke-keranjang', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');

    /* Ulasan: hanya untuk pembeli yang order-nya completed */
    Route::post('/ulasan', [ReviewController::class, 'store'])->name('review.store');
    Route::delete('/ulasan/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');
});

/*
|--------------------------------------------------------------------------
| Panel Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        Route::resource('products', AdminProductController::class)->except('show');
        Route::get('products/{product}/lihat', [AdminProductController::class, 'show'])->name('products.show');

        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::resource('brands', AdminBrandController::class)->except('show');

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');

        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::patch('users/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.status');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::resource('services', AdminServiceController::class)->except('show');

        Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
        Route::patch('bookings/{booking}/reschedule', [AdminBookingController::class, 'reschedule'])->name('bookings.reschedule');

        Route::resource('articles', AdminArticleController::class)->except('show');

        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.status');
        Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::resource('coupons', AdminCouponController::class)->except('show');

        Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('reports/{type}/export', [AdminReportController::class, 'export'])->name('reports.export');
    });