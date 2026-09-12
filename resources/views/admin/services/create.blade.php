@extends('layouts.admin')

@section('title', 'Tambah Jasa')

@section('page-title', 'Tambah Jasa')

@section('content')

<div class="max-w-3xl">

    {{-- HEADER --}}

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-900">
            Tambah Jasa
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Tambahkan jasa servis baru ke dalam sistem.
        </p>

    </div>


    {{-- FORM --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl"
    >

        <div
            class="px-6 py-5
                   border-b border-gray-200"
        >

            <h2 class="font-semibold text-gray-900">
                Informasi Jasa
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Masukkan informasi jasa yang tersedia.
            </p>

        </div>


        <form
            method="POST"
            action="{{ route('admin.services.store') }}"
            class="p-6"
        >

            @csrf


            {{-- NAMA --}}

            <div class="mb-5">

                <label
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Nama Jasa
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Servis Ringan"
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           outline-none
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500"
                    required
                >

                @error('name')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- HARGA --}}

            <div class="mb-5">

                <label
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Harga Jasa
                </label>

                <div class="flex">

                    <span
                        class="inline-flex
                               items-center
                               px-4
                               border border-r-0
                               border-gray-300
                               rounded-l-lg
                               bg-gray-50
                               text-sm
                               text-gray-600"
                    >
                        Rp
                    </span>

                    <input
                        type="number"
                        name="price"
                        value="{{ old('price') }}"
                        placeholder="0"
                        min="0"
                        class="w-full rounded-r-lg
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

                @error('price')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- DESKRIPSI --}}

            <div class="mb-6">

                <label
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Deskripsi
                </label>

                <textarea
                    name="description"
                    rows="4"
                    placeholder="Masukkan deskripsi jasa..."
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-3
                           text-sm
                           outline-none
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500"
                >{{ old('description') }}</textarea>

                @error('description')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- BUTTON --}}

            <div
                class="flex items-center
                       justify-end
                       gap-3"
            >

                <a
                    href="{{ route('admin.services.index') }}"
                    class="px-5 py-2.5
                           border border-gray-300
                           rounded-lg
                           text-sm font-medium
                           text-gray-700
                           hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5
                           bg-gray-900
                           text-white
                           rounded-lg
                           text-sm font-medium
                           hover:bg-gray-800"
                >
                    Simpan Jasa
                </button>

            </div>

        </form>

    </div>

</div>

@endsection