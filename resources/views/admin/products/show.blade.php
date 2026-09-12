@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('page-title', 'Detail Produk')

@section('content')

<div class="max-w-4xl">

    {{-- HEADER --}}

    <div
        class="flex flex-col
               sm:flex-row
               sm:items-center
               sm:justify-between
               gap-4 mb-6"
    >

        <div>

            <a
                href="{{ route('admin.products.index') }}"
                class="text-sm text-gray-500
                       hover:text-gray-900"
            >
                ← Kembali ke Inventory
            </a>

            <h1
                class="mt-3 text-2xl
                       font-bold text-gray-900"
            >
                {{ $product->name }}
            </h1>

            <p
                class="mt-1 text-sm
                       text-gray-500"
            >
                {{ $product->code }}
            </p>

        </div>


        <a
            href="{{ route(
                'admin.products.edit',
                $product
            ) }}"
            class="inline-flex
                   items-center
                   justify-center
                   px-4 py-2.5
                   bg-gray-900
                   text-white
                   rounded-lg
                   text-sm
                   font-medium
                   hover:bg-gray-800"
        >
            Edit Produk
        </a>

    </div>


    {{-- SUCCESS --}}

    @if(session('success'))

        <div
            class="mb-6 px-4 py-3
                   rounded-lg
                   bg-gray-50
                   border border-gray-200
                   text-sm text-gray-700"
        >
            {{ session('success') }}
        </div>

    @endif


    {{-- DETAIL --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl
               overflow-hidden"
    >

        <div
            class="px-6 py-5
                   border-b border-gray-200"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Informasi Produk
            </h2>

        </div>


        <div
            class="grid grid-cols-1
                   md:grid-cols-2"
        >

            {{-- NAMA --}}

            <div
                class="px-6 py-5
                       border-b
                       border-gray-100"
            >

                <p class="text-sm text-gray-500">
                    Nama Produk
                </p>

                <p
                    class="mt-1 font-medium
                           text-gray-900"
                >
                    {{ $product->name }}
                </p>

            </div>


            {{-- KODE --}}

            <div
                class="px-6 py-5
                       border-b
                       border-gray-100"
            >

                <p class="text-sm text-gray-500">
                    Kode Produk
                </p>

                <p
                    class="mt-1 font-medium
                           text-gray-900"
                >
                    {{ $product->code }}
                </p>

            </div>


            {{-- KATEGORI --}}

            <div
                class="px-6 py-5
                       border-b
                       border-gray-100"
            >

                <p class="text-sm text-gray-500">
                    Kategori
                </p>

                <p
                    class="mt-1 font-medium
                           text-gray-900"
                >
                    {{ $product->category->name ?? '-' }}
                </p>

            </div>


            {{-- SATUAN --}}

            <div
                class="px-6 py-5
                       border-b
                       border-gray-100"
            >

                <p class="text-sm text-gray-500">
                    Satuan
                </p>

                <p
                    class="mt-1 font-medium
                           text-gray-900"
                >
                    {{ $product->unit ?? '-' }}
                </p>

            </div>


            {{-- HARGA BELI --}}

            <div
                class="px-6 py-5
                       border-b
                       border-gray-100"
            >

                <p class="text-sm text-gray-500">
                    Harga Beli
                </p>

                <p
                    class="mt-1 font-medium
                           text-gray-900"
                >
                    Rp
                    {{ number_format(
                        $product->purchase_price,
                        0,
                        ',',
                        '.'
                    ) }}
                </p>

            </div>


            {{-- HARGA JUAL --}}

            <div
                class="px-6 py-5
                       border-b
                       border-gray-100"
            >

                <p class="text-sm text-gray-500">
                    Harga Jual
                </p>

                <p
                    class="mt-1 font-medium
                           text-gray-900"
                >
                    Rp
                    {{ number_format(
                        $product->selling_price,
                        0,
                        ',',
                        '.'
                    ) }}
                </p>

            </div>


            {{-- STOK --}}

            <div
                class="px-6 py-5
                       border-b
                       border-gray-100"
            >

                <p class="text-sm text-gray-500">
                    Stok
                </p>

                <p
                    class="mt-1 text-xl
                           font-bold text-gray-900"
                >
                    {{ $product->stock }}

                    <span
                        class="text-sm font-normal
                               text-gray-500"
                    >
                        {{ $product->unit }}
                    </span>
                </p>

            </div>


            {{-- MINIMUM STOK --}}

            <div
                class="px-6 py-5
                       border-b
                       border-gray-100"
            >

                <p class="text-sm text-gray-500">
                    Minimum Stok
                </p>

                <p
                    class="mt-1 font-medium
                           text-gray-900"
                >
                    {{ $product->minimum_stock }}
                    {{ $product->unit }}
                </p>

            </div>


            {{-- STATUS --}}

            <div
                class="px-6 py-5
                       md:col-span-2
                       border-b
                       border-gray-100"
            >

                <p class="text-sm text-gray-500">
                    Status Stok
                </p>

                <div class="mt-2">

                    @if(
                        $product->stock
                        <=
                        $product->minimum_stock
                    )

                        <span
                            class="inline-flex
                                   px-3 py-1.5
                                   rounded-full
                                   text-xs
                                   font-medium
                                   bg-gray-100
                                   text-gray-700"
                        >
                            Stok Menipis
                        </span>

                    @else

                        <span
                            class="inline-flex
                                   px-3 py-1.5
                                   rounded-full
                                   text-xs
                                   font-medium
                                   bg-gray-50
                                   text-gray-600"
                        >
                            Stok Aman
                        </span>

                    @endif

                </div>

            </div>


            {{-- DESKRIPSI --}}

            <div
                class="px-6 py-5
                       md:col-span-2"
            >

                <p class="text-sm text-gray-500">
                    Deskripsi
                </p>

                <p
                    class="mt-2 text-sm
                           text-gray-700
                           whitespace-pre-line"
                >
                    {{ $product->description ?: '-' }}
                </p>

            </div>

        </div>

    </div>


    {{-- HAPUS --}}

    <div
        class="mt-6
               bg-white
               border border-gray-200
               rounded-xl p-6"
    >

        <h2
            class="font-semibold
                   text-gray-900"
        >
            Hapus Produk
        </h2>

        <p
            class="mt-1 text-sm
                   text-gray-500"
        >
            Produk yang dihapus tidak dapat dikembalikan.
        </p>


        <form
            action="{{ route(
                'admin.products.destroy',
                $product
            ) }}"
            method="POST"
            class="mt-4"
            onsubmit="return confirm(
                'Yakin ingin menghapus produk ini?'
            )"
        >

            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="px-4 py-2.5
                       rounded-lg
                       border border-gray-300
                       text-sm font-medium
                       text-gray-700
                       hover:bg-gray-50"
            >
                Hapus Produk
            </button>

        </form>

    </div>

</div>

@endsection