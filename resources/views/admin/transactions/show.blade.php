@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('page-title', 'Detail Transaksi')

@section('content')

<div class="max-w-6xl">

    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <div
        class="mb-6 flex items-center
               justify-between"
    >

        <div>

            <h1
                class="text-2xl font-bold
                       text-gray-900"
            >
                Detail Transaksi
            </h1>

            <p
                class="mt-1 text-sm
                       text-gray-500"
            >
                {{ $transaction->transaction_code }}
            </p>

        </div>


        <a
            href="{{ route('admin.transactions.index') }}"
            class="rounded-lg
                   border border-gray-300
                   px-5 py-2.5
                   text-sm font-medium
                   text-gray-700
                   hover:bg-gray-50
                   transition"
        >
            ← Kembali
        </a>

    </div>



    {{-- ================================================= --}}
    {{-- TRANSACTION HEADER --}}
    {{-- ================================================= --}}

    <div
        class="rounded-xl
               border border-gray-200
               bg-white p-6"
    >

        <div
            class="grid grid-cols-1
                   md:grid-cols-4 gap-6"
        >

            <div>

                <p
                    class="text-xs
                           uppercase
                           tracking-wide
                           text-gray-400"
                >
                    Kode Transaksi
                </p>

                <p
                    class="mt-2
                           font-semibold
                           text-gray-900"
                >
                    {{ $transaction->transaction_code }}
                </p>

            </div>


            <div>

                <p
                    class="text-xs
                           uppercase
                           tracking-wide
                           text-gray-400"
                >
                    Tanggal
                </p>

                <p
                    class="mt-2
                           font-medium
                           text-gray-900"
                >
                    {{ $transaction->created_at?->format('d M Y H:i') }}
                </p>

            </div>


            <div>

                <p
                    class="text-xs
                           uppercase
                           tracking-wide
                           text-gray-400"
                >
                    Kasir
                </p>

                <p
                    class="mt-2
                           font-medium
                           text-gray-900"
                >
                    {{ $transaction->user?->name ?? '-' }}
                </p>

            </div>


            <div>

                <p
                    class="text-xs
                           uppercase
                           tracking-wide
                           text-gray-400"
                >
                    Status
                </p>

                <div class="mt-2">

                    @if($transaction->status === 'completed')

                        <span
                            class="inline-flex
                                   rounded-full
                                   bg-green-100
                                   px-3 py-1
                                   text-xs
                                   font-medium
                                   text-green-700"
                        >
                            Selesai
                        </span>

                    @elseif($transaction->status === 'cancelled')

                        <span
                            class="inline-flex
                                   rounded-full
                                   bg-red-100
                                   px-3 py-1
                                   text-xs
                                   font-medium
                                   text-red-700"
                        >
                            Dibatalkan
                        </span>

                    @else

                        <span
                            class="inline-flex
                                   rounded-full
                                   bg-gray-100
                                   px-3 py-1
                                   text-xs
                                   font-medium
                                   text-gray-700"
                        >
                            {{ ucfirst($transaction->status) }}
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- CUSTOMER + VEHICLE --}}
    {{-- ================================================= --}}

    <div
        class="mt-6 grid grid-cols-1
               md:grid-cols-2 gap-6"
    >

        {{-- CUSTOMER --}}

        <div
            class="rounded-xl
                   border border-gray-200
                   bg-white p-6"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Customer
            </h2>


            <div class="mt-5 space-y-4">

                <div>

                    <p
                        class="text-xs
                               text-gray-400"
                    >
                        Nama
                    </p>

                    <p
                        class="mt-1
                               font-medium
                               text-gray-900"
                    >
                        {{ $transaction->customer?->name ?? '-' }}
                    </p>

                </div>


                <div>

                    <p
                        class="text-xs
                               text-gray-400"
                    >
                        Telepon
                    </p>

                    <p
                        class="mt-1
                               text-gray-700"
                    >
                        {{ $transaction->customer?->phone ?? '-' }}
                    </p>

                </div>


                <div>

                    <p
                        class="text-xs
                               text-gray-400"
                    >
                        Alamat
                    </p>

                    <p
                        class="mt-1
                               text-gray-700"
                    >
                        {{ $transaction->customer?->address ?? '-' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- VEHICLE --}}

        <div
            class="rounded-xl
                   border border-gray-200
                   bg-white p-6"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Kendaraan
            </h2>


            <div class="mt-5 space-y-4">

                <div>

                    <p
                        class="text-xs
                               text-gray-400"
                    >
                        Nomor Polisi
                    </p>

                    <p
                        class="mt-1
                               font-medium
                               text-gray-900"
                    >
                        {{ $transaction->vehicle?->plate_number ?? '-' }}
                    </p>

                </div>


                <div>

                    <p
                        class="text-xs
                               text-gray-400"
                    >
                        Kendaraan
                    </p>

                    <p
                        class="mt-1
                               text-gray-700"
                    >
                        {{ $transaction->vehicle?->brand ?? '' }}
                        {{ $transaction->vehicle?->model ?? '' }}
                    </p>

                </div>


                <div>

                    <p
                        class="text-xs
                               text-gray-400"
                    >
                        Tahun
                    </p>

                    <p
                        class="mt-1
                               text-gray-700"
                    >
                        {{ $transaction->vehicle?->year ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- ITEMS --}}
    {{-- ================================================= --}}

    <div
        class="mt-6 overflow-hidden
               rounded-xl
               border border-gray-200
               bg-white"
    >

        <div
            class="border-b
                   border-gray-200
                   px-6 py-5"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Detail Item
            </h2>

        </div>


        <div class="overflow-x-auto">

            <table
                class="w-full text-sm"
            >

                <thead
                    class="bg-gray-50
                           border-b
                           border-gray-200"
                >

                    <tr>

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Item
                        </th>

                        <th
                            class="px-6 py-4
                                   text-center
                                   font-semibold
                                   text-gray-600"
                        >
                            Qty
                        </th>

                        <th
                            class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-600"
                        >
                            Harga
                        </th>

                        <th
                            class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-600"
                        >
                            Subtotal
                        </th>

                    </tr>

                </thead>


                <tbody
                    class="divide-y
                           divide-gray-100"
                >

                    @foreach($transaction->items as $item)

                        <tr>

                            <td
                                class="px-6 py-4"
                            >

                                @if($item->service)

                                    <p
                                        class="font-medium
                                               text-gray-900"
                                    >
                                        {{ $item->service->name }}
                                    </p>

                                    <p
                                        class="mt-1
                                               text-xs
                                               text-gray-400"
                                    >
                                        Jasa
                                    </p>

                                @elseif($item->product)

                                    <p
                                        class="font-medium
                                               text-gray-900"
                                    >
                                        {{ $item->product->name }}
                                    </p>

                                    <p
                                        class="mt-1
                                               text-xs
                                               text-gray-400"
                                    >
                                        Barang
                                    </p>

                                @else

                                    <p
                                        class="text-gray-500"
                                    >
                                        Item tidak ditemukan
                                    </p>

                                @endif

                            </td>


                            <td
                                class="px-6 py-4
                                       text-center"
                            >
                                {{ $item->quantity }}
                            </td>


                            <td
                                class="px-6 py-4
                                       text-right"
                            >
                                Rp {{ number_format(
                                    $item->price,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>


                            <td
                                class="px-6 py-4
                                       text-right
                                       font-medium"
                            >
                                Rp {{ number_format(
                                    $item->subtotal,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- PAYMENT --}}
    {{-- ================================================= --}}

    <div
        class="mt-6 grid grid-cols-1
               md:grid-cols-2 gap-6"
    >

        {{-- PAYMENT DETAIL --}}

        <div
            class="rounded-xl
                   border border-gray-200
                   bg-white p-6"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Pembayaran
            </h2>


            <div
                class="mt-5 space-y-4"
            >

                <div
                    class="flex
                           justify-between"
                >

                    <span
                        class="text-gray-500"
                    >
                        Total
                    </span>

                    <span
                        class="font-semibold
                               text-gray-900"
                    >
                        Rp {{ number_format(
                            $transaction->total_amount,
                            0,
                            ',',
                            '.'
                        ) }}
                    </span>

                </div>


                <div
                    class="flex
                           justify-between"
                >

                    <span
                        class="text-gray-500"
                    >
                        Dibayar
                    </span>

                    <span
                        class="text-gray-900"
                    >
                        Rp {{ number_format(
                            $transaction->paid_amount,
                            0,
                            ',',
                            '.'
                        ) }}
                    </span>

                </div>


                <div
                    class="flex
                           justify-between"
                >

                    <span
                        class="text-gray-500"
                    >
                        Kembalian
                    </span>

                    <span
                        class="text-gray-900"
                    >
                        Rp {{ number_format(
                            $transaction->change_amount,
                            0,
                            ',',
                            '.'
                        ) }}
                    </span>

                </div>


                <div
                    class="border-t
                           border-gray-200
                           pt-4
                           flex
                           justify-between"
                >

                    <span
                        class="text-gray-500"
                    >
                        Metode Pembayaran
                    </span>

                    <span
                        class="font-medium
                               text-gray-900"
                    >
                        {{ strtoupper(
                            $transaction->payment_method
                        ) }}
                    </span>

                </div>

            </div>

        </div>


        {{-- NOTES --}}

        <div
            class="rounded-xl
                   border border-gray-200
                   bg-white p-6"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Catatan
            </h2>


            @if($transaction->notes)

                <p
                    class="mt-5
                           whitespace-pre-line
                           text-sm
                           leading-6
                           text-gray-700"
                >
                    {{ $transaction->notes }}
                </p>

            @else

                <p
                    class="mt-5
                           text-sm
                           text-gray-400"
                >
                    Tidak ada catatan transaksi.
                </p>

            @endif

        </div>

    </div>

</div>

@endsection