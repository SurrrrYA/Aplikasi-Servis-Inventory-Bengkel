@extends('layouts.admin')

@section('title', 'Detail Project')

@section('page-title', 'Detail Project')

@section('content')

<div
    class="max-w-7xl mx-auto space-y-6"
    x-data="projectDetail()"
    data-project-id="{{ $project->id }}"
>

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div
        class="flex flex-col sm:flex-row
               sm:items-center sm:justify-between
               gap-4"
    >

        <div>

            <div class="flex items-center gap-3">

                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $project->name }}
                </h1>

                @if($project->status === 'draft')

                    <span
                        class="inline-flex
                               px-2.5 py-1
                               rounded-full
                               text-xs
                               font-medium
                               bg-gray-100
                               text-gray-700"
                    >
                        Draft
                    </span>

                @elseif($project->status === 'process')

                    <span
                        class="inline-flex
                               px-2.5 py-1
                               rounded-full
                               text-xs
                               font-medium
                               bg-gray-100
                               text-gray-700"
                    >
                        Process
                    </span>

                @elseif($project->status === 'completed')

                    <span
                        class="inline-flex
                               px-2.5 py-1
                               rounded-full
                               text-xs
                               font-medium
                               bg-gray-100
                               text-gray-700"
                    >
                        Completed
                    </span>

                @elseif($project->status === 'cancelled')

                    <span
                        class="inline-flex
                               px-2.5 py-1
                               rounded-full
                               text-xs
                               font-medium
                               bg-gray-100
                               text-gray-700"
                    >
                        Cancelled
                    </span>

                @endif

            </div>

            <p class="mt-1 text-sm text-gray-500">
                {{ $project->code }}
            </p>

        </div>


        <div class="flex items-center gap-2 flex-wrap">

            <a
                href="{{ route('admin.projects.index') }}"
                class="inline-flex
                       items-center
                       px-4 py-2.5
                       border border-gray-300
                       rounded-lg
                       text-sm
                       font-medium
                       text-gray-700
                       hover:bg-gray-50
                       transition"
            >
                Kembali
            </a>


            <a
                href="{{ route('admin.projects.invoice', $project) }}"
                target="_blank"
                class="inline-flex
                       items-center
                       gap-2
                       px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm
                       font-medium
                       hover:bg-gray-800
                       transition"
            >
                <span>📄</span>
                Cetak Invoice
            </a>


            <a
                href="{{ route('admin.projects.edit', $project) }}"
                class="inline-flex
                       items-center
                       px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm
                       font-medium
                       hover:bg-gray-800
                       transition"
            >
                Edit Project
            </a>

        </div>

    </div>


    {{-- =====================================================
         PROJECT STATISTICS
    ====================================================== --}}

    <div
        class="grid grid-cols-1
               sm:grid-cols-2
               lg:grid-cols-3
               gap-5"
    >

        {{-- CUSTOMER --}}

        <div
            class="bg-white
                   border border-gray-200
                   rounded-xl
                   p-5"
        >

            <p class="text-sm text-gray-500">
                Customer
            </p>

            <p class="mt-2 text-lg font-semibold text-gray-900">
                {{ $project->customer_name ?? '-' }}
            </p>

        </div>


        {{-- TOTAL PROJECT --}}

        <div
            class="bg-white
                   border border-gray-200
                   rounded-xl
                   p-5"
        >

            <p class="text-sm text-gray-500">
                Total Project
            </p>

            <p class="mt-2 text-lg font-semibold text-gray-900">
                Rp {{ number_format($project->total ?? 0, 0, ',', '.') }}
            </p>

        </div>


        {{-- STATUS --}}

        <div
            class="bg-white
                   border border-gray-200
                   rounded-xl
                   p-5"
        >

            <p class="text-sm text-gray-500">
                Status
            </p>

            <p class="mt-2 text-lg font-semibold text-gray-900">

                @switch($project->status)

                    @case('draft')
                        Draft
                    @break

                    @case('process')
                        Process
                    @break

                    @case('completed')
                        Completed
                    @break

                    @case('cancelled')
                        Cancelled
                    @break

                    @default
                        -

                @endswitch

            </p>

        </div>

    </div>


    {{-- =====================================================
         ITEM PROJECT
    ====================================================== --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl
               overflow-hidden"
    >

        {{-- CARD HEADER --}}

        <div
            class="flex flex-col
                   sm:flex-row
                   sm:items-center
                   sm:justify-between
                   gap-4
                   px-6 py-5
                   border-b border-gray-200"
        >

            <div>

                <h2
                    class="font-semibold
                           text-gray-900"
                >
                    Item Project
                </h2>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Produk dan jasa yang digunakan dalam project.
                </p>

            </div>


            <button
                type="button"
                @click="openAddItem"
                class="inline-flex
                       items-center
                       justify-center
                       gap-2
                       px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm
                       font-medium
                       hover:bg-gray-800
                       transition"
            >

                <span class="text-lg leading-none">
                    +
                </span>

                Tambah Item

            </button>

        </div>


        {{-- TABLE --}}

        @if($project->items->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

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
                                Jenis
                            </th>

                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-gray-600"
                            >
                                Nama
                            </th>

                            <th
                                class="px-6 py-4
                                       text-right
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

                            <th
                                class="px-6 py-4
                                       text-right
                                       font-semibold
                                       text-gray-600"
                            >
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y
                               divide-gray-100"
                    >

                        @foreach($project->items as $item)

                            @php

                                $itemName = '-';

                                if ($item->product) {

                                    $itemName =
                                        $item->product->name;

                                } elseif ($item->service) {

                                    $itemName =
                                        $item->service->name;

                                } elseif (isset($item->name)) {

                                    $itemName =
                                        $item->name;

                                }

                            @endphp


                            <tr
                                class="hover:bg-gray-50
                                       transition"
                            >

                                {{-- JENIS --}}

                                <td class="px-6 py-4">

                                    @if($item->product)

                                        <span
                                            class="inline-flex
                                                   px-2.5 py-1
                                                   rounded-full
                                                   text-xs
                                                   font-medium
                                                   bg-gray-100
                                                   text-gray-700"
                                        >
                                            Produk
                                        </span>

                                    @elseif($item->service)

                                        <span
                                            class="inline-flex
                                                   px-2.5 py-1
                                                   rounded-full
                                                   text-xs
                                                   font-medium
                                                   bg-gray-100
                                                   text-gray-700"
                                        >
                                            Jasa
                                        </span>

                                    @else

                                        <span class="text-gray-400">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- NAMA --}}

                                <td class="px-6 py-4">

                                    <p
                                        class="font-medium
                                               text-gray-900"
                                    >
                                        {{ $itemName }}
                                    </p>

                                </td>


                                {{-- QTY --}}

                                <td
                                    class="px-6 py-4
                                           text-right
                                           text-gray-700"
                                >
                                    {{ $item->quantity }}
                                </td>


                                {{-- HARGA --}}

                                <td
                                    class="px-6 py-4
                                           text-right
                                           font-medium
                                           text-gray-900
                                           whitespace-nowrap"
                                >

                                    Rp
                                    {{ number_format(
                                        $item->price ?? 0,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- SUBTOTAL --}}

                                <td
                                    class="px-6 py-4
                                           text-right
                                           font-medium
                                           text-gray-900
                                           whitespace-nowrap"
                                >

                                    Rp
                                    {{ number_format(
                                        $item->subtotal ?? 0,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- AKSI --}}

                                <td
                                    class="px-6 py-4
                                           text-right"
                                >

                                    <div
                                        class="inline-flex
                                               items-center
                                               gap-1"
                                    >

                                        {{-- EDIT ITEM --}}

                                        <button
                                            type="button"
                                            @click="openEditItem(
                                                {{ $item->id }},
                                                {{ $item->quantity }},
                                                @js($itemName)
                                            )"
                                            class="px-3 py-1.5
                                                   rounded-lg
                                                   text-sm
                                                   font-medium
                                                   text-gray-700
                                                   hover:bg-gray-100
                                                   transition"
                                        >
                                            Edit
                                        </button>


                                        {{-- DELETE ITEM --}}

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.items.destroy',
                                                [
                                                    $project,
                                                    $item
                                                ]
                                            ) }}"
                                            class="inline"
                                            onsubmit="
                                                return confirm(
                                                    'Yakin ingin menghapus item ini?'
                                                )
                                            "
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="px-3 py-1.5
                                                       rounded-lg
                                                       text-sm
                                                       font-medium
                                                       text-gray-600
                                                       hover:bg-gray-100
                                                       hover:text-red-600
                                                       transition"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- EMPTY --}}

            <div
                class="py-16
                       text-center"
            >

                <div
                    class="text-4xl
                           mb-3"
                >
                    📦
                </div>

                <p
                    class="font-medium
                           text-gray-700"
                >
                    Belum ada item
                </p>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Tambahkan produk atau jasa ke project ini.
                </p>

                <button
                    type="button"
                    @click="openAddItem"
                    class="inline-flex
                           mt-5
                           px-4 py-2.5
                           bg-gray-900
                           text-white
                           rounded-lg
                           text-sm
                           font-medium
                           hover:bg-gray-800"
                >
                    Tambah Item
                </button>

            </div>

        @endif

    </div>


    {{-- =====================================================
         BIAYA TAMBAHAN
    ====================================================== --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl
               overflow-hidden"
    >

        {{-- HEADER --}}

        <div
            class="flex flex-col
                   sm:flex-row
                   sm:items-center
                   sm:justify-between
                   gap-4
                   px-6 py-5
                   border-b border-gray-200"
        >

            <div>

                <h2
                    class="font-semibold
                           text-gray-900"
                >
                    Biaya Tambahan
                </h2>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Biaya lain yang dikeluarkan untuk project.
                </p>

            </div>


            <button
                type="button"
                @click="openAddCost"
                class="inline-flex
                       items-center
                       justify-center
                       gap-2
                       px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm
                       font-medium
                       hover:bg-gray-800
                       transition"
            >

                <span class="text-lg leading-none">
                    +
                </span>

                Tambah Biaya

            </button>

        </div>


        {{-- TABLE --}}

        @if($project->costs->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

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
                                Nama Biaya
                            </th>

                            <th
                                class="px-6 py-4
                                       text-right
                                       font-semibold
                                       text-gray-600"
                            >
                                Jumlah
                            </th>

                            <th
                                class="px-6 py-4
                                       text-right
                                       font-semibold
                                       text-gray-600"
                            >
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y
                               divide-gray-100"
                    >

                        @foreach($project->costs as $cost)

                            <tr
                                class="hover:bg-gray-50
                                       transition"
                            >

                                {{-- NAMA BIAYA --}}

                                <td
                                    class="px-6 py-4
                                           font-medium
                                           text-gray-900"
                                >
                                    {{ $cost->name }}
                                </td>


                                {{-- JUMLAH --}}

                                <td
                                    class="px-6 py-4
                                           text-right
                                           font-medium
                                           text-gray-900
                                           whitespace-nowrap"
                                >

                                    Rp
                                    {{ number_format(
                                        $cost->amount ?? 0,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- AKSI --}}

                                <td
                                    class="px-6 py-4
                                           text-right"
                                >

                                    <div
                                        class="inline-flex
                                               items-center
                                               gap-1"
                                    >

                                        {{-- DELETE COST --}}

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.costs.destroy',
                                                [
                                                    $project,
                                                    $cost
                                                ]
                                            ) }}"
                                            class="inline"
                                            onsubmit="
                                                return confirm(
                                                    'Yakin ingin menghapus biaya ini?'
                                                )
                                            "
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="px-3 py-1.5
                                                       rounded-lg
                                                       text-sm
                                                       font-medium
                                                       text-gray-600
                                                       hover:bg-gray-100
                                                       hover:text-red-600
                                                       transition"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- EMPTY --}}

            <div
                class="py-16
                       text-center"
            >

                <div
                    class="text-4xl
                           mb-3"
                >
                    💰
                </div>

                <p
                    class="font-medium
                           text-gray-700"
                >
                    Belum ada biaya tambahan
                </p>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Biaya tambahan project akan muncul di sini.
                </p>

                <button
                    type="button"
                    @click="openAddCost"
                    class="inline-flex
                           mt-5
                           px-4 py-2.5
                           bg-gray-900
                           text-white
                           rounded-lg
                           text-sm
                           font-medium
                           hover:bg-gray-800"
                >
                    Tambah Biaya
                </button>

            </div>

        @endif

    </div>


    {{-- =====================================================
         RINGKASAN PROJECT
    ====================================================== --}}

    <div class="flex justify-end">

        <div
            class="w-full md:w-96
                   bg-white
                   border border-gray-200
                   rounded-xl
                   p-6"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Ringkasan Project
            </h2>

            <p
                class="text-sm
                       text-gray-500
                       mt-1"
            >
                Total keseluruhan biaya project.
            </p>


            <div
                class="mt-5
                       space-y-4
                       text-sm"
            >

                {{-- TOTAL ITEM --}}

                <div class="flex justify-between">

                    <span class="text-gray-500">
                        Total Item
                    </span>

                    <span
                        class="font-medium
                               text-gray-900"
                    >
                        Rp
                        {{ number_format(
                            $project->items->sum('subtotal'),
                            0,
                            ',',
                            '.'
                        ) }}
                    </span>

                </div>


                {{-- BIAYA TAMBAHAN --}}

                <div class="flex justify-between">

                    <span class="text-gray-500">
                        Biaya Tambahan
                    </span>

                    <span
                        class="font-medium
                               text-gray-900"
                    >
                        Rp
                        {{ number_format(
                            $project->costs->sum('amount'),
                            0,
                            ',',
                            '.'
                        ) }}
                    </span>

                </div>


                {{-- TOTAL PROJECT --}}

                <div
                    class="border-t
                           border-gray-200
                           pt-4
                           flex
                           justify-between"
                >

                    <span
                        class="font-semibold
                               text-gray-900"
                    >
                        Total Project
                    </span>

                    <span
                        class="font-bold
                               text-gray-900"
                    >
                        Rp
                        {{ number_format(
                            $project->total ?? 0,
                            0,
                            ',',
                            '.'
                        ) }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MODAL ITEM
    ====================================================== --}}

    <div
        x-show="showItemModal"
        x-cloak
        class="fixed
               inset-0
               left-64
               z-[100]
               flex
               items-center
               justify-center
               p-4
               overflow-y-auto"
        style="display: none;"
    >

        {{-- OVERLAY --}}

        <div
            class="absolute inset-0
                   bg-black/40"
            @click="closeItemModal"
        ></div>


        {{-- MODAL --}}

        <div
            class="relative
                   w-full
                   max-w-lg
                   bg-white
                   rounded-xl
                   shadow-xl
                   my-8"
            @click.stop
        >

            {{-- HEADER --}}

            <div
                class="flex
                       items-center
                       justify-between
                       px-6 py-4
                       border-b border-gray-200"
            >

                <h3
                    class="text-lg
                           font-semibold
                           text-gray-900"
                >

                    <span
                        x-text="
                            itemMode === 'add'
                                ? 'Tambah Item'
                                : 'Edit Quantity'
                        "
                    ></span>

                </h3>


                <button
                    type="button"
                    @click="closeItemModal"
                    class="text-gray-400
                           hover:text-gray-600
                           text-xl"
                >
                    &times;
                </button>

            </div>


            {{-- =================================================
                 ADD ITEM FORM
            ================================================== --}}

            <form
                x-show="itemMode === 'add'"
                @submit.prevent="saveItem"
                class="p-6 space-y-5"
            >

                {{-- TYPE --}}

                <div>

                    <label
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Jenis Item
                    </label>

                    <select
                        x-model="itemForm.type"
                        @change="resetItemSelection"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-2.5
                               text-sm
                               bg-white
                               outline-none
                               focus:border-gray-500
                               focus:ring-1
                               focus:ring-gray-500"
                        required
                    >

                        <option value="">
                            Pilih jenis item
                        </option>

                        <option value="product">
                            Produk
                        </option>

                        <option value="service">
                            Jasa
                        </option>

                    </select>

                </div>


                {{-- =================================================
                     PRODUCT
                ================================================== --}}

                <div
                    x-show="itemForm.type === 'product'"
                    x-transition
                >

                    <label
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Produk
                    </label>


                    <select
                        x-model="itemForm.selected"
                        @change="updateItemPrice($event)"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-2.5
                               text-sm
                               bg-white
                               outline-none
                               focus:border-gray-500
                               focus:ring-1
                               focus:ring-gray-500"
                        :required="itemForm.type === 'product'"
                    >

                        <option value="">
                            Pilih produk
                        </option>


                        @foreach(
                            \App\Models\Product::orderBy('name')->get()
                            as $product
                        )

                            <option
                                value="{{ $product->id }}"
                                data-price="{{ $product->selling_price }}"
                                data-stock="{{ $product->stock }}"
                                @disabled($product->stock <= 0)
                            >

                                {{ $product->name }}

                                —

                                Rp
                                {{ number_format(
                                    $product->selling_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                                —

                                @if($product->stock > 0)

                                    Stok: {{ $product->stock }}

                                @else

                                    STOK HABIS

                                @endif

                            </option>

                        @endforeach

                    </select>


                    {{-- STOCK INFO --}}

                    <div
                        x-show="
                            itemForm.type === 'product' &&
                            itemForm.selected
                        "
                        class="mt-2"
                    >

                        <p
                            x-show="itemForm.stock > 0"
                            class="text-xs text-gray-500"
                        >

                            Stok tersedia:

                            <span
                                class="font-semibold text-gray-700"
                                x-text="itemForm.stock"
                            ></span>

                        </p>


                        <p
                            x-show="
                                itemForm.selected &&
                                itemForm.stock <= 0
                            "
                            class="text-xs
                                   text-red-600
                                   font-medium"
                        >
                            Stok produk habis.
                        </p>

                    </div>

                </div>


                {{-- =================================================
                     SERVICE
                ================================================== --}}

                <div
                    x-show="itemForm.type === 'service'"
                    x-transition
                >

                    <label
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Jasa
                    </label>


                    <select
                        x-model="itemForm.selected"
                        @change="updateItemPrice($event)"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-2.5
                               text-sm
                               bg-white
                               outline-none
                               focus:border-gray-500
                               focus:ring-1
                               focus:ring-gray-500"
                        :required="itemForm.type === 'service'"
                    >

                        <option value="">
                            Pilih jasa
                        </option>


                        @foreach(
                            \App\Models\Service::orderBy('name')->get()
                            as $service
                        )

                            <option
                                value="{{ $service->id }}"
                                data-price="{{ $service->price }}"
                            >

                                {{ $service->name }}

                                —

                                Rp
                                {{ number_format(
                                    $service->price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- =================================================
                     QUANTITY
                ================================================== --}}

                <div>

                    <label
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Quantity
                    </label>


                    <input
                        type="number"
                        min="1"
                        :max="
                            itemForm.type === 'product'
                                ? itemForm.stock
                                : 1
                        "
                        x-model.number="itemForm.quantity"
                        :readonly="itemForm.type === 'service'"
                        @input="
                            if (
                                itemForm.type === 'product' &&
                                itemForm.stock > 0 &&
                                itemForm.quantity > itemForm.stock
                            ) {
                                itemForm.quantity = itemForm.stock
                            }

                            if (
                                itemForm.type === 'service'
                            ) {
                                itemForm.quantity = 1
                            }
                        "
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-2.5
                               text-sm
                               outline-none
                               focus:border-gray-500
                               focus:ring-1
                               focus:ring-gray-500"
                        required
                    >


                    {{-- SERVICE INFO --}}

                    <p
                        x-show="itemForm.type === 'service'"
                        class="mt-1
                               text-xs
                               text-gray-500"
                    >
                        Quantity jasa ditetapkan 1.
                    </p>


                    {{-- PRODUCT INFO --}}

                    <p
                        x-show="
                            itemForm.type === 'product' &&
                            itemForm.selected &&
                            itemForm.stock > 0
                        "
                        class="mt-1
                               text-xs
                               text-gray-500"
                    >

                        Maksimal quantity:

                        <span
                            class="font-semibold text-gray-700"
                            x-text="itemForm.stock"
                        ></span>

                    </p>

                </div>


                {{-- =================================================
                     PRICE PREVIEW
                ================================================== --}}

                <div
                    x-show="itemForm.price > 0"
                    class="bg-gray-50
                           border border-gray-200
                           rounded-lg
                           p-4"
                >

                    <div
                        class="flex
                               justify-between
                               text-sm"
                    >

                        <span class="text-gray-500">
                            Harga
                        </span>

                        <span
                            class="font-medium
                                   text-gray-900"
                        >

                            Rp

                            <span
                                x-text="
                                    formatRupiah(
                                        itemForm.price
                                    )
                                "
                            ></span>

                        </span>

                    </div>


                    <div
                        class="flex
                               justify-between
                               text-sm
                               mt-2"
                    >

                        <span class="text-gray-500">
                            Subtotal
                        </span>

                        <span
                            class="font-semibold
                                   text-gray-900"
                        >

                            Rp

                            <span
                                x-text="
                                    formatRupiah(
                                        itemForm.price *
                                        itemForm.quantity
                                    )
                                "
                            ></span>

                        </span>

                    </div>

                </div>


                {{-- BUTTON --}}

                <div
                    class="flex
                           justify-end
                           gap-2
                           pt-2"
                >

                    <button
                        type="button"
                        @click="closeItemModal"
                        class="px-4 py-2.5
                               border border-gray-300
                               rounded-lg
                               text-sm
                               font-medium
                               text-gray-700
                               hover:bg-gray-50
                               transition"
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        :disabled="loading"
                        class="px-4 py-2.5
                               bg-gray-900
                               text-white
                               rounded-lg
                               text-sm
                               font-medium
                               hover:bg-gray-800
                               disabled:opacity-50
                               transition"
                    >

                        <span
                            x-text="
                                loading
                                    ? 'Menyimpan...'
                                    : 'Simpan'
                            "
                        ></span>

                    </button>

                </div>

            </form>


            {{-- =================================================
                 EDIT ITEM FORM
            ================================================== --}}

            <form
                x-show="itemMode === 'edit'"
                @submit.prevent="updateItem"
                class="p-6 space-y-5"
            >

                <div>

                    <p
                        class="text-sm
                               text-gray-500"
                    >
                        Item
                    </p>

                    <p
                        class="mt-1
                               font-semibold
                               text-gray-900"
                        x-text="editItemName"
                    ></p>

                </div>


                <div>

                    <label
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Quantity
                    </label>

                    <input
                        type="number"
                        min="1"
                        x-model.number="editItemQuantity"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-2.5
                               text-sm
                               outline-none
                               focus:border-gray-500
                               focus:ring-1
                               focus:ring-gray-500"
                        required
                    >

                </div>


                <div
                    class="flex
                           justify-end
                           gap-2"
                >

                    <button
                        type="button"
                        @click="closeItemModal"
                        class="px-4 py-2.5
                               border border-gray-300
                               rounded-lg
                               text-sm
                               font-medium
                               text-gray-700
                               hover:bg-gray-50"
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        :disabled="loading"
                        class="px-4 py-2.5
                               bg-gray-900
                               text-white
                               rounded-lg
                               text-sm
                               font-medium
                               hover:bg-gray-800
                               disabled:opacity-50"
                    >

                        <span
                            x-text="
                                loading
                                    ? 'Menyimpan...'
                                    : 'Simpan Perubahan'
                            "
                        ></span>

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         MODAL COST
    ====================================================== --}}

    <div
        x-show="showCostModal"
        x-cloak
        class="fixed
               inset-0
               left-64
               z-[100]
               flex
               items-center
               justify-center
               p-4
               overflow-y-auto"
        style="display: none;"
    >

        {{-- OVERLAY --}}

        <div
            class="absolute inset-0
                   bg-black/40"
            @click="closeCostModal"
        ></div>


        {{-- MODAL --}}

        <div
            class="relative
                   w-full
                   max-w-lg
                   bg-white
                   rounded-xl
                   shadow-xl
                   my-8"
            @click.stop
        >

            {{-- HEADER --}}

            <div
                class="flex
                       items-center
                       justify-between
                       px-6 py-4
                       border-b border-gray-200"
            >

                <h3
                    class="text-lg
                           font-semibold
                           text-gray-900"
                >
                    Tambah Biaya
                </h3>


                <button
                    type="button"
                    @click="closeCostModal"
                    class="text-gray-400
                           hover:text-gray-600
                           text-xl"
                >
                    &times;
                </button>

            </div>


            {{-- COST FORM --}}

            <form
                @submit.prevent="saveCost"
                class="p-6 space-y-5"
            >

                {{-- NAMA BIAYA --}}

                <div>

                    <label
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Nama Biaya
                    </label>

                    <input
                        type="text"
                        x-model="costForm.name"
                        placeholder="Contoh: Listrik"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-2.5
                               text-sm
                               outline-none
                               focus:border-gray-500
                               focus:ring-1
                               focus:ring-gray-500"
                        required
                    >

                </div>


                {{-- JUMLAH --}}

                <div>

                    <label
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Jumlah
                    </label>

                    <input
                        type="number"
                        min="0"
                        x-model.number="costForm.amount"
                        placeholder="0"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-2.5
                               text-sm
                               outline-none
                               focus:border-gray-500
                               focus:ring-1
                               focus:ring-gray-500"
                        required
                    >

                </div>


                {{-- BUTTON --}}

                <div
                    class="flex
                           justify-end
                           gap-2
                           pt-2"
                >

                    <button
                        type="button"
                        @click="closeCostModal"
                        class="px-4 py-2.5
                               border border-gray-300
                               rounded-lg
                               text-sm
                               font-medium
                               text-gray-700
                               hover:bg-gray-50"
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        :disabled="loading"
                        class="px-4 py-2.5
                               bg-gray-900
                               text-white
                               rounded-lg
                               text-sm
                               font-medium
                               hover:bg-gray-800
                               disabled:opacity-50"
                    >

                        <span
                            x-text="
                                loading
                                    ? 'Menyimpan...'
                                    : 'Simpan'
                            "
                        ></span>

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         LOADING
    ====================================================== --}}

    <div
        x-show="loading"
        x-cloak
        class="fixed
               bottom-5
               right-5
               z-[110]"
        style="display: none;"
    >

        <div
            class="bg-gray-900
                   text-white
                   px-4 py-3
                   rounded-lg
                   shadow-lg
                   text-sm"
        >
            Memproses...
        </div>

    </div>

</div>


{{-- =====================================================
     STYLE
====================================================== --}}

<style>

[x-cloak] {
    display: none !important;
}

</style>


{{-- =====================================================
     JAVASCRIPT
====================================================== --}}

@verbatim

<script>

function projectDetail() {

    return {

        projectId: null,

        loading: false,

        showItemModal: false,

        showCostModal: false,


        /* =================================================
           ITEM
        ================================================== */

        itemMode: 'add',

        editItemId: null,

        editItemName: '',

        editItemQuantity: 1,


        itemForm: {

            type: '',

            selected: '',

            quantity: 1,

            price: 0,

            stock: 0

        },


        /* =================================================
           COST
        ================================================== */

        costMode: 'add',

        costForm: {

            name: '',

            amount: 0

        },


        /* =================================================
           INIT
        ================================================== */

        init() {

            this.projectId =
                Number(
                    this.$el.dataset.projectId
                );

        },


        /* =================================================
           CSRF
        ================================================== */

        csrfToken() {

            const meta =
                document.querySelector(
                    'meta[name="csrf-token"]'
                );

            return meta
                ? meta.getAttribute('content')
                : '';

        },


        /* =================================================
           RESPONSE
        ================================================== */

        async parseResponse(response) {

            const text =
                await response.text();

            if (!text) {

                return {};

            }

            try {

                return JSON.parse(text);

            } catch (error) {

                return {

                    message: text

                };

            }

        },


        /* =================================================
           ADD ITEM
        ================================================== */

        openAddItem() {

            this.itemMode = 'add';

            this.editItemId = null;

            this.editItemName = '';

            this.editItemQuantity = 1;


            this.itemForm = {

                type: '',

                selected: '',

                quantity: 1,

                price: 0,

                stock: 0

            };


            this.showItemModal = true;

        },


        /* =================================================
           EDIT ITEM
        ================================================== */

        openEditItem(
            id,
            quantity,
            name
        ) {

            this.itemMode = 'edit';

            this.editItemId =
                Number(id);

            this.editItemQuantity =
                Number(quantity);

            this.editItemName =
                String(name || '');

            this.showItemModal = true;

        },


        /* =================================================
           CLOSE ITEM
        ================================================== */

        closeItemModal() {

            this.showItemModal = false;

            this.loading = false;

        },


        /* =================================================
           RESET ITEM SELECTION
        ================================================== */

        resetItemSelection() {

            this.itemForm.selected = '';

            this.itemForm.price = 0;

            this.itemForm.stock = 0;

            this.itemForm.quantity = 1;

        },


        /* =================================================
           UPDATE PRICE + STOCK
        ================================================== */

        updateItemPrice(event) {

            if (
                !event ||
                !event.target
            ) {

                this.itemForm.price = 0;

                this.itemForm.stock = 0;

                return;

            }


            const option =
                event.target.options[
                    event.target.selectedIndex
                ];


            if (!option) {

                this.itemForm.price = 0;

                this.itemForm.stock = 0;

                return;

            }


            this.itemForm.price =
                Number(
                    option.dataset.price || 0
                );


            this.itemForm.stock =
                Number(
                    option.dataset.stock || 0
                );


            /* =========================================
               SERVICE
            ========================================== */

            if (
                this.itemForm.type === 'service'
            ) {

                this.itemForm.quantity = 1;

                return;

            }


            /* =========================================
               PRODUCT
            ========================================== */

            if (
                this.itemForm.type === 'product'
            ) {

                if (
                    this.itemForm.stock > 0 &&
                    this.itemForm.quantity >
                    this.itemForm.stock
                ) {

                    this.itemForm.quantity =
                        this.itemForm.stock;

                }

            }

        },


        /* =================================================
           SAVE ITEM
        ================================================== */

        async saveItem() {

            if (!this.itemForm.type) {

                alert(
                    'Pilih jenis item terlebih dahulu.'
                );

                return;

            }


            if (!this.itemForm.selected) {

                alert(
                    'Pilih produk atau jasa terlebih dahulu.'
                );

                return;

            }


            let quantity =
                Number(
                    this.itemForm.quantity
                );


            /* =========================================
               SERVICE
            ========================================== */

            if (
                this.itemForm.type === 'service'
            ) {

                quantity = 1;

            }


            /* =========================================
               BASIC QUANTITY VALIDATION
            ========================================== */

            if (
                !quantity ||
                quantity < 1
            ) {

                alert(
                    'Quantity minimal 1.'
                );

                return;

            }


            /* =========================================
               STOCK VALIDATION
            ========================================== */

            if (
                this.itemForm.type === 'product'
            ) {

                const stock =
                    Number(
                        this.itemForm.stock
                    );


                if (
                    stock <= 0
                ) {

                    alert(
                        'Stok produk habis.'
                    );

                    return;

                }


                if (
                    quantity > stock
                ) {

                    alert(
                        `Stok tidak mencukupi. Stok tersedia: ${stock}.`
                    );

                    return;

                }

            }


            this.loading = true;


            try {

                const data = {

                    type:
                        this.itemForm.type,

                    quantity:
                        quantity

                };


                /* =====================================
                   PRODUCT
                ====================================== */

                if (
                    this.itemForm.type === 'product'
                ) {

                    data.product_id =
                        Number(
                            this.itemForm.selected
                        );

                }


                /* =====================================
                   SERVICE
                ====================================== */

                else {

                    data.service_id =
                        Number(
                            this.itemForm.selected
                        );

                }


                const response =
                    await fetch(

                        `/admin/projects/${this.projectId}/items`,

                        {

                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    this.csrfToken(),

                                'X-Requested-With':
                                    'XMLHttpRequest'

                            },

                            credentials:
                                'same-origin',

                            body:
                                JSON.stringify(data)

                        }

                    );


                const result =
                    await this.parseResponse(
                        response
                    );


                if (!response.ok) {

                    throw new Error(

                        result.message ||
                        'Gagal menambahkan item.'

                    );

                }


                alert(
                    'Item berhasil ditambahkan.'
                );


                window.location.reload();

            }

            catch (error) {

                console.error(
                    'SAVE ITEM ERROR:',
                    error
                );

                alert(
                    error.message ||
                    'Terjadi kesalahan.'
                );

            }

            finally {

                this.loading = false;

            }

        },


        /* =================================================
           UPDATE ITEM
        ================================================== */

        async updateItem() {

            const quantity =
                Number(
                    this.editItemQuantity
                );


            if (
                !quantity ||
                quantity < 1
            ) {

                alert(
                    'Quantity minimal 1.'
                );

                return;

            }


            this.loading = true;


            try {

                const response =
                    await fetch(

                        `/admin/projects/${this.projectId}/items/${this.editItemId}`,

                        {

                            method: 'PUT',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    this.csrfToken(),

                                'X-Requested-With':
                                    'XMLHttpRequest'

                            },

                            credentials:
                                'same-origin',

                            body:
                                JSON.stringify({

                                    quantity:
                                        quantity

                                })

                        }

                    );


                const result =
                    await this.parseResponse(
                        response
                    );


                if (!response.ok) {

                    throw new Error(

                        result.message ||
                        'Gagal mengubah quantity item.'

                    );

                }


                alert(
                    'Quantity item berhasil diperbarui.'
                );


                window.location.reload();

            }

            catch (error) {

                console.error(
                    'UPDATE ITEM ERROR:',
                    error
                );

                alert(
                    error.message ||
                    'Terjadi kesalahan.'
                );

            }

            finally {

                this.loading = false;

            }

        },


        /* =================================================
           ADD COST
        ================================================== */

        openAddCost() {

            this.costMode = 'add';


            this.costForm = {

                name: '',

                amount: 0

            };


            this.showCostModal = true;

        },


        /* =================================================
           CLOSE COST
        ================================================== */

        closeCostModal() {

            this.showCostModal = false;

            this.loading = false;

        },


        /* =================================================
           SAVE COST
        ================================================== */

        async saveCost() {

            const name =
                String(
                    this.costForm.name || ''
                ).trim();


            const amount =
                Number(
                    this.costForm.amount
                );


            if (!name) {

                alert(
                    'Nama biaya wajib diisi.'
                );

                return;

            }


            if (
                !Number.isFinite(amount) ||
                amount < 0
            ) {

                alert(
                    'Jumlah biaya tidak valid.'
                );

                return;

            }


            this.loading = true;


            try {

                const response =
                    await fetch(

                        `/admin/projects/${this.projectId}/costs`,

                        {

                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    this.csrfToken(),

                                'X-Requested-With':
                                    'XMLHttpRequest'

                            },

                            credentials:
                                'same-origin',

                            body:
                                JSON.stringify({

                                    name:
                                        name,

                                    amount:
                                        amount

                                })

                        }

                    );


                const result =
                    await this.parseResponse(
                        response
                    );


                if (!response.ok) {

                    throw new Error(

                        result.message ||
                        'Gagal menambahkan biaya.'

                    );

                }


                alert(
                    'Biaya berhasil ditambahkan.'
                );


                window.location.reload();

            }

            catch (error) {

                console.error(
                    'SAVE COST ERROR:',
                    error
                );

                alert(
                    error.message ||
                    'Terjadi kesalahan.'
                );

            }

            finally {

                this.loading = false;

            }

        },


        /* =================================================
           RUPIAH
        ================================================== */

        formatRupiah(value) {

            return new Intl.NumberFormat(
                'id-ID'
            ).format(
                Number(value) || 0
            );

        }

    };

}

</script>

@endverbatim

@endsection