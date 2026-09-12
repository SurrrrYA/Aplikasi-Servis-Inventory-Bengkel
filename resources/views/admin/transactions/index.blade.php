@extends('layouts.admin')

@section('title', 'Transaksi')

@section('page-title', 'Transaksi')

@section('content')

<div>

    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <div class="mb-8">

        <h1
            class="text-2xl font-bold text-gray-900"
        >
            Transaksi
        </h1>

        <p
            class="mt-1 text-sm text-gray-500"
        >
            Daftar transaksi yang dilakukan melalui aplikasi kasir.
        </p>

    </div>


    {{-- ================================================= --}}
    {{-- FILTER --}}
    {{-- ================================================= --}}

    <div
        class="mb-6 rounded-xl
               border border-gray-200
               bg-white p-5"
    >

        <form
            method="GET"
            action="{{ route('admin.transactions.index') }}"
            class="grid grid-cols-1
                   md:grid-cols-4 gap-4"
        >

            {{-- SEARCH --}}

            <div class="md:col-span-2">

                <label
                    for="search"
                    class="block text-sm
                           font-medium text-gray-700"
                >
                    Cari Transaksi
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Kode transaksi, customer, atau plat nomor"
                    class="mt-2 block w-full
                           rounded-lg
                           border border-gray-300
                           px-4 py-2.5 text-sm
                           focus:border-gray-900
                           focus:outline-none
                           focus:ring-1
                           focus:ring-gray-900"
                >

            </div>


            {{-- STATUS --}}

            <div>

                <label
                    for="status"
                    class="block text-sm
                           font-medium text-gray-700"
                >
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="mt-2 block w-full
                           rounded-lg
                           border border-gray-300
                           px-4 py-2.5 text-sm
                           focus:border-gray-900
                           focus:outline-none
                           focus:ring-1
                           focus:ring-gray-900"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="completed"
                        {{ request('status') === 'completed' ? 'selected' : '' }}
                    >
                        Selesai
                    </option>

                    <option
                        value="cancelled"
                        {{ request('status') === 'cancelled' ? 'selected' : '' }}
                    >
                        Dibatalkan
                    </option>

                </select>

            </div>


            {{-- BUTTON --}}

            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="rounded-lg
                           bg-gray-900
                           px-5 py-2.5
                           text-sm font-medium
                           text-white
                           hover:bg-gray-800
                           transition"
                >
                    Cari
                </button>


                @if(request()->filled('search') || request()->filled('status'))

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
                        Reset
                    </a>

                @endif

            </div>

        </form>

    </div>



    {{-- ================================================= --}}
    {{-- TABLE --}}
    {{-- ================================================= --}}

    <div
        class="overflow-hidden
               rounded-xl
               border border-gray-200
               bg-white"
    >

        <div
            class="border-b border-gray-200
                   px-6 py-5"
        >

            <h2
                class="font-semibold text-gray-900"
            >
                Daftar Transaksi
            </h2>

            <p
                class="mt-1 text-sm text-gray-500"
            >
                {{ $transactions->total() }}
                transaksi ditemukan.
            </p>

        </div>


        @if($transactions->count() > 0)

            <div class="overflow-x-auto">

                <table
                    class="w-full text-sm"
                >

                    <thead
                        class="bg-gray-50
                               border-b border-gray-200"
                    >

                        <tr>

                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-gray-600"
                            >
                                Transaksi
                            </th>

                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-gray-600"
                            >
                                Customer
                            </th>

                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-gray-600"
                            >
                                Kendaraan
                            </th>

                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-gray-600"
                            >
                                Kasir
                            </th>

                            <th
                                class="px-6 py-4
                                       text-right
                                       font-semibold
                                       text-gray-600"
                            >
                                Total
                            </th>

                            <th
                                class="px-6 py-4
                                       text-center
                                       font-semibold
                                       text-gray-600"
                            >
                                Status
                            </th>

                            <th
                                class="px-6 py-4
                                       text-center
                                       font-semibold
                                       text-gray-600"
                            >
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y divide-gray-100"
                    >

                        @foreach($transactions as $transaction)

                            <tr
                                class="hover:bg-gray-50
                                       transition"
                            >

                                {{-- TRANSAKSI --}}

                                <td
                                    class="px-6 py-4"
                                >

                                    <p
                                        class="font-semibold
                                               text-gray-900"
                                    >
                                        {{ $transaction->transaction_code }}
                                    </p>

                                    <p
                                        class="mt-1
                                               text-xs
                                               text-gray-500"
                                    >
                                        {{ $transaction->created_at?->format('d M Y H:i') }}
                                    </p>

                                </td>


                                {{-- CUSTOMER --}}

                                <td
                                    class="px-6 py-4"
                                >

                                    <p
                                        class="font-medium
                                               text-gray-900"
                                    >
                                        {{ $transaction->customer?->name ?? '-' }}
                                    </p>

                                    @if($transaction->customer?->phone)

                                        <p
                                            class="mt-1
                                                   text-xs
                                                   text-gray-500"
                                        >
                                            {{ $transaction->customer->phone }}
                                        </p>

                                    @endif

                                </td>


                                {{-- KENDARAAN --}}

                                <td
                                    class="px-6 py-4"
                                >

                                    @if($transaction->vehicle)

                                        <p
                                            class="font-medium
                                                   text-gray-900"
                                        >
                                            {{ $transaction->vehicle->plate_number }}
                                        </p>

                                        <p
                                            class="mt-1
                                                   text-xs
                                                   text-gray-500"
                                        >
                                            {{ $transaction->vehicle->brand }}
                                            {{ $transaction->vehicle->model }}
                                        </p>

                                    @else

                                        <span
                                            class="text-gray-400"
                                        >
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- KASIR --}}

                                <td
                                    class="px-6 py-4
                                           text-gray-700"
                                >
                                    {{ $transaction->user?->name ?? '-' }}
                                </td>


                                {{-- TOTAL --}}

                                <td
                                    class="px-6 py-4
                                           text-right"
                                >

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

                                </td>


                                {{-- STATUS --}}

                                <td
                                    class="px-6 py-4
                                           text-center"
                                >

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

                                </td>


                                {{-- AKSI --}}

                                <td
                                    class="px-6 py-4
                                           text-center"
                                >

                                    <a
                                        href="{{ route(
                                            'admin.transactions.show',
                                            $transaction
                                        ) }}"
                                        class="font-medium
                                               text-gray-900
                                               hover:underline"
                                    >
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if($transactions->hasPages())

                <div
                    class="border-t
                           border-gray-200
                           px-6 py-4"
                >

                    {{ $transactions->links() }}

                </div>

            @endif

        @else

            {{-- EMPTY --}}

            <div
                class="px-6 py-16
                       text-center"
            >

                <div
                    class="mb-4 text-4xl"
                >
                    🧾
                </div>

                <p
                    class="font-medium
                           text-gray-700"
                >
                    Belum ada transaksi
                </p>

                <p
                    class="mt-1 text-sm
                           text-gray-500"
                >
                    Transaksi dari aplikasi kasir
                    akan muncul di halaman ini.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection