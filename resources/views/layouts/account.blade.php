@extends('layouts.app')

@section('content')
    <main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
        <section class="w-full py-8 lg:py-10">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

                    <aside class="lg:col-span-3 min-w-0 lg:sticky lg:top-28">
                        @include('layouts.partials.account-sidebar')
                    </aside>

                    <div class="lg:col-span-9 min-w-0">
                        @yield('account-content')
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
