@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard')

@section('content')

@php

    use App\Models\Transaction;
    use App\Models\Customer;
    use App\Models\Product;

    /*
    |--------------------------------------------------------------------------
    | DATA DASHBOARD
    |--------------------------------------------------------------------------
    */

    // Transaksi hari ini
    $todayTransactions = Transaction::whereDate(
        'created_at',
        now()->toDateString()
    )
    ->where('status', 'completed')
    ->count();


    // Pendapatan hari ini
    $todayRevenue = Transaction::whereDate(
        'created_at',
        now()->toDateString()
    )
    ->where('status', 'completed')
    ->sum('total_amount');


    // Total customer
    $totalCustomers = Customer::count();


    // Produk yang stoknya <= minimum_stock
    $lowStockProducts = Product::whereColumn(
        'stock',
        '<=',
        'minimum_stock'
    )
    ->orderBy('stock')
    ->get();


    $lowStockCount = $lowStockProducts->count();


    // Transaksi terbaru
    $latestTransactions = Transaction::with([
        'customer',
        'vehicle',
        'user'
    ])
    ->latest()
    ->take(5)
    ->get();

@endphp


{{-- ================================================= --}}
{{-- WELCOME --}}
{{-- ================================================= --}}

<div class="mb-8">

    <h1 class="text-2xl font-bold text-gray-900">
        Dashboard
    </h1>

    <p class="mt-1 text-sm text-gray-500">
        Ringkasan aktivitas Bengkel Sempoeloer.
    </p>

</div>



{{-- ================================================= --}}
{{-- STATISTICS --}}
{{-- ================================================= --}}

<div
    class="grid grid-cols-1
           sm:grid-cols-2
           lg:grid-cols-4
           gap-5"
>


    {{-- TRANSAKSI HARI INI --}}

    <div
        class="bg-white rounded-xl
               border border-gray-200
               p-6"
    >

        <div
            class="flex items-center
                   justify-between"
        >

            <div>

                <p class="text-sm text-gray-500">
                    Transaksi Hari Ini
                </p>

                <p
                    class="mt-2 text-3xl
                           font-bold text-gray-900"
                >
                    {{ $todayTransactions }}
                </p>

            </div>

            <div
                class="w-11 h-11
                       rounded-lg bg-gray-100
                       flex items-center
                       justify-center text-xl"
            >
                💰
            </div>

        </div>

    </div>



    {{-- PENDAPATAN HARI INI --}}

    <div
        class="bg-white rounded-xl
               border border-gray-200
               p-6"
    >

        <div
            class="flex items-center
                   justify-between"
        >

            <div>

                <p class="text-sm text-gray-500">
                    Pendapatan Hari Ini
                </p>

                <p
                    class="mt-2 text-3xl
                           font-bold text-gray-900"
                >
                    Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                </p>

            </div>

            <div
                class="w-11 h-11
                       rounded-lg bg-gray-100
                       flex items-center
                       justify-center text-xl"
            >
                📈
            </div>

        </div>

    </div>



    {{-- TOTAL CUSTOMER --}}

    <div
        class="bg-white rounded-xl
               border border-gray-200
               p-6"
    >

        <div
            class="flex items-center
                   justify-between"
        >

            <div>

                <p class="text-sm text-gray-500">
                    Total Customer
                </p>

                <p
                    class="mt-2 text-3xl
                           font-bold text-gray-900"
                >
                    {{ $totalCustomers }}
                </p>

            </div>

            <div
                class="w-11 h-11
                       rounded-lg bg-gray-100
                       flex items-center
                       justify-center text-xl"
            >
                👥
            </div>

        </div>

    </div>



    {{-- STOK MENIPIS --}}

    <div
        class="bg-white rounded-xl
               border border-gray-200
               p-6"
    >

        <div
            class="flex items-center
                   justify-between"
        >

            <div>

                <p class="text-sm text-gray-500">
                    Stok Menipis
                </p>

                <p
                    class="mt-2 text-3xl
                           font-bold text-gray-900"
                >
                    {{ $lowStockCount }}
                </p>

            </div>

            <div
                class="w-11 h-11
                       rounded-lg bg-gray-100
                       flex items-center
                       justify-center text-xl"
            >
                📦
            </div>

        </div>

    </div>

</div>



{{-- ================================================= --}}
{{-- CONTENT GRID --}}
{{-- ================================================= --}}

<div
    class="grid grid-cols-1
           lg:grid-cols-3
           gap-6 mt-6"
>


    {{-- ================================================= --}}
    {{-- TRANSAKSI TERBARU --}}
    {{-- ================================================= --}}

    <div
        class="lg:col-span-2
               bg-white rounded-xl
               border border-gray-200"
    >

        <div
            class="px-6 py-5
                   border-b border-gray-200
                   flex items-center
                   justify-between"
        >

            <div>

                <h2
                    class="font-semibold text-gray-900"
                >
                    Transaksi Terbaru
                </h2>

                <p
                    class="text-sm text-gray-500 mt-1"
                >
                    Aktivitas transaksi terakhir.
                </p>

            </div>

            <span
                class="text-sm font-medium
                       text-gray-400"
            >
                5 transaksi terakhir
            </span>

        </div>


        <div class="p-6">

            @if($latestTransactions->count() > 0)

                <div class="space-y-4">

                    @foreach($latestTransactions as $transaction)

                        <div
                            class="flex items-center
                                   justify-between
                                   gap-4
                                   border-b
                                   border-gray-100
                                   pb-4
                                   last:border-0
                                   last:pb-0"
                        >

                            <div class="min-w-0">

                                <p
                                    class="font-semibold
                                           text-gray-900"
                                >
                                    {{ $transaction->transaction_code }}
                                </p>


                                <p
                                    class="text-sm
                                           text-gray-500
                                           mt-1"
                                >

                                    {{ $transaction->customer->name ?? 'Umum' }}

                                    @if($transaction->vehicle)

                                        •
                                        {{ $transaction->vehicle->plate_number }}

                                    @endif

                                </p>


                                <p
                                    class="text-xs
                                           text-gray-400
                                           mt-1"
                                >
                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                </p>

                            </div>


                            <div
                                class="text-right
                                       shrink-0"
                            >

                                <p
                                    class="font-semibold
                                           text-gray-900"
                                >
                                    Rp {{ number_format(
                                        $transaction->total_amount,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </p>


                                @if($transaction->status === 'completed')

                                    <span
                                        class="inline-flex
                                               mt-1
                                               px-2 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-green-100
                                               text-green-700"
                                    >
                                        Selesai
                                    </span>

                                @elseif($transaction->status === 'cancelled')

                                    <span
                                        class="inline-flex
                                               mt-1
                                               px-2 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-red-100
                                               text-red-700"
                                    >
                                        Dibatalkan
                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               mt-1
                                               px-2 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-100
                                               text-gray-700"
                                    >
                                        {{ ucfirst($transaction->status) }}
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center py-12">

                    <div class="text-4xl mb-3">
                        🧾
                    </div>

                    <p
                        class="font-medium
                               text-gray-700"
                    >
                        Belum ada transaksi
                    </p>

                    <p
                        class="text-sm
                               text-gray-500
                               mt-1"
                    >
                        Transaksi dari aplikasi kasir
                        akan muncul di sini.
                    </p>

                </div>

            @endif

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- STOK MENIPIS --}}
    {{-- ================================================= --}}

    <div
        class="bg-white rounded-xl
               border border-gray-200"
    >

        <div
            class="px-6 py-5
                   border-b border-gray-200"
        >

            <h2
                class="font-semibold text-gray-900"
            >
                Stok Menipis
            </h2>

            <p
                class="text-sm text-gray-500 mt-1"
            >
                Produk yang perlu diperhatikan.
            </p>

        </div>


        <div class="p-6">

            @if($lowStockProducts->count() > 0)

                <div class="space-y-4">

                    @foreach($lowStockProducts->take(5) as $product)

                        <div
                            class="flex items-center
                                   justify-between
                                   gap-3"
                        >

                            <div class="min-w-0">

                                <p
                                    class="font-medium
                                           text-gray-900
                                           truncate"
                                >
                                    {{ $product->name }}
                                </p>

                                <p
                                    class="text-xs
                                           text-gray-500
                                           mt-1"
                                >
                                    {{ $product->code }}
                                </p>

                            </div>


                            <div
                                class="text-right
                                       shrink-0"
                            >

                                <p
                                    class="font-semibold
                                           text-red-600"
                                >
                                    {{ $product->stock }}
                                    {{ $product->unit }}
                                </p>

                                <p
                                    class="text-xs
                                           text-gray-400"
                                >
                                    Min. {{ $product->minimum_stock }}
                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center py-12">

                    <div class="text-4xl mb-3">
                        📦
                    </div>

                    <p
                        class="font-medium
                               text-gray-700"
                    >
                        Tidak ada data
                    </p>

                    <p
                        class="text-sm
                               text-gray-500
                               mt-1"
                    >
                        Semua stok dalam kondisi aman.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>



{{-- ================================================= --}}
{{-- QUICK ACCESS --}}
{{-- ================================================= --}}

<div class="mt-6">

    <h2
        class="text-lg font-semibold
               text-gray-900 mb-4"
    >
        Akses Cepat
    </h2>


    <div
        class="grid grid-cols-1
               sm:grid-cols-2
               lg:grid-cols-4
               gap-4"
    >


        {{-- ================================================= --}}
        {{-- PRODUK --}}
        {{-- ================================================= --}}

        <a
            href="{{ route('admin.products.index') }}"
            class="bg-white border
                   border-gray-200
                   rounded-xl p-5
                   block
                   hover:border-gray-300
                   hover:shadow-sm
                   transition"
        >

            <div class="text-2xl mb-3">
                📦
            </div>

            <p class="font-semibold text-gray-900">
                Produk
            </p>

            <p
                class="text-sm
                       text-gray-500 mt-1"
            >
                Kelola barang dan stok.
            </p>

        </a>



        {{-- ================================================= --}}
        {{-- JASA --}}
        {{-- ================================================= --}}

        <a
            href="{{ route('admin.services.index') }}"
            class="bg-white border
                   border-gray-200
                   rounded-xl p-5
                   block
                   hover:border-gray-300
                   hover:shadow-sm
                   transition"
        >

            <div class="text-2xl mb-3">
                🔧
            </div>

            <p class="font-semibold text-gray-900">
                Jasa
            </p>

            <p
                class="text-sm
                       text-gray-500 mt-1"
            >
                Kelola jasa servis.
            </p>

        </a>



        {{-- ================================================= --}}
        {{-- ACTIVITY LOG --}}
        {{-- ================================================= --}}

        <a
            href="{{ route('admin.activity-logs.index') }}"
            class="bg-white border
                   border-gray-200
                   rounded-xl p-5
                   block
                   hover:border-gray-300
                   hover:shadow-sm
                   transition"
        >

            <div class="text-2xl mb-3">
                📋
            </div>

            <p class="font-semibold text-gray-900">
                Activity Log
            </p>

            <p
                class="text-sm
                       text-gray-500 mt-1"
            >
                Lihat riwayat aktivitas pengguna.
            </p>

        </a>



        {{-- ================================================= --}}
        {{-- LAPORAN PENJUALAN --}}
        {{-- ================================================= --}}

        <a
            href="{{ route('admin.reports.sales') }}"
            class="bg-white border
                   border-gray-200
                   rounded-xl p-5
                   block
                   hover:border-gray-300
                   hover:shadow-sm
                   transition"
        >

            <div class="text-2xl mb-3">
                📈
            </div>

            <p class="font-semibold text-gray-900">
                Laporan Penjualan
            </p>

            <p
                class="text-sm
                       text-gray-500 mt-1"
            >
                Lihat laporan transaksi dan penjualan.
            </p>

        </a>


    </div>

</div>


@endsection