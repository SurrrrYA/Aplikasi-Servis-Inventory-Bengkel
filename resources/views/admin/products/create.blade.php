@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('page-title', 'Tambah Produk')

@section('content')

<div class="max-w-4xl">

    {{-- HEADER --}}

    <div class="mb-6">

        <a
            href="{{ route('admin.products.index') }}"
            class="text-sm text-gray-500 hover:text-gray-900"
        >
            ← Kembali ke Inventory
        </a>

        <h1 class="mt-3 text-2xl font-bold text-gray-900">
            Tambah Produk
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Tambahkan produk baru ke inventory.
        </p>

    </div>


    {{-- ERROR --}}

    @if($errors->any())

        <div
            class="mb-6 rounded-lg
                   border border-gray-200
                   bg-gray-50 p-4"
        >

            <p class="font-medium text-gray-900 mb-2">
                Terdapat kesalahan:
            </p>

            <ul class="text-sm text-gray-600 list-disc pl-5">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- FORM --}}

    <form
        action="{{ route('admin.products.store') }}"
        method="POST"
        class="bg-white
               border border-gray-200
               rounded-xl p-6"
    >

        @csrf


        {{-- NAMA PRODUK --}}

        <div class="mb-5">

            <label
                for="name"
                class="block text-sm
                       font-medium
                       text-gray-700 mb-2"
            >
                Nama Produk
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                placeholder="Contoh: Oli Mesin"
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none
                       focus:border-gray-500
                       focus:ring-1
                       focus:ring-gray-500"
            >

        </div>


        {{-- KATEGORI --}}

        <div class="mb-5">

            <label
                for="category_id"
                class="block text-sm
                       font-medium
                       text-gray-700 mb-2"
            >
                Kategori
            </label>

            <select
                id="category_id"
                name="category_id"
                required
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none
                       focus:border-gray-500
                       focus:ring-1
                       focus:ring-gray-500"
            >

                <option value="">
                    Pilih kategori
                </option>

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        @selected(
                            old('category_id')
                            == $category->id
                        )
                    >
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- HARGA --}}

        <div
            class="grid grid-cols-1
                   md:grid-cols-2
                   gap-5 mb-5"
        >

            {{-- HARGA BELI --}}

            <div>

                <label
                    for="purchase_price"
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Harga Beli
                </label>

                <input
                    type="number"
                    id="purchase_price"
                    name="purchase_price"
                    value="{{ old('purchase_price') }}"
                    min="0"
                    required
                    placeholder="0"
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           outline-none
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500"
                >

            </div>


            {{-- HARGA JUAL --}}

            <div>

                <label
                    for="selling_price"
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Harga Jual
                </label>

                <input
                    type="number"
                    id="selling_price"
                    name="selling_price"
                    value="{{ old('selling_price') }}"
                    min="0"
                    required
                    placeholder="0"
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           outline-none
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500"
                >

            </div>

        </div>


        {{-- STOK --}}

        <div
            class="grid grid-cols-1
                   md:grid-cols-3
                   gap-5 mb-5"
        >

            {{-- STOK AWAL --}}

            <div>

                <label
                    for="stock"
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Stok Awal
                </label>

                <input
                    type="number"
                    id="stock"
                    name="stock"
                    value="{{ old('stock', 0) }}"
                    min="0"
                    required
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           outline-none
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500"
                >

            </div>


            {{-- MINIMUM STOK --}}

            <div>

                <label
                    for="minimum_stock"
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Minimum Stok
                </label>

                <input
                    type="number"
                    id="minimum_stock"
                    name="minimum_stock"
                    value="{{ old('minimum_stock', 5) }}"
                    min="0"
                    required
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           outline-none
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500"
                >

            </div>


            {{-- SATUAN --}}

            <div>

                <label
                    for="unit"
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Satuan
                </label>

                <input
                    type="text"
                    id="unit"
                    name="unit"
                    value="{{ old('unit', 'pcs') }}"
                    placeholder="pcs"
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           outline-none
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500"
                >

            </div>

        </div>


        {{-- DESKRIPSI --}}

        <div class="mb-6">

            <label
                for="description"
                class="block text-sm
                       font-medium
                       text-gray-700 mb-2"
            >
                Deskripsi
            </label>

            <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Deskripsi produk (opsional)"
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none
                       focus:border-gray-500
                       focus:ring-1
                       focus:ring-gray-500"
            >{{ old('description') }}</textarea>

        </div>


        {{-- BUTTON --}}

        <div
            class="flex items-center
                   justify-end gap-3
                   pt-5
                   border-t border-gray-200"
        >

            <a
                href="{{ route('admin.products.index') }}"
                class="px-4 py-2.5
                       rounded-lg
                       border border-gray-300
                       text-sm font-medium
                       text-gray-700
                       hover:bg-gray-50"
            >
                Batal
            </a>


            <button
                type="submit"
                class="px-5 py-2.5
                       rounded-lg
                       bg-gray-900
                       text-white
                       text-sm font-medium
                       hover:bg-gray-800"
            >
                Simpan Produk
            </button>

        </div>

    </form>

</div>

@endsection