@extends('layouts.admin')

@section('title', 'Tambah Customer')

@section('page-title', 'Tambah Customer')

@section('content')

<div class="mb-8">

    <div class="flex items-center gap-3">

        <a
            href="{{ route('admin.customers.index') }}"
            class="text-gray-500 hover:text-gray-900"
        >
            ←
        </a>

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Tambah Customer
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Tambahkan customer baru ke sistem.
            </p>

        </div>

    </div>

</div>


@if($errors->any())

    <div
        class="mb-6 rounded-lg
               border border-red-200
               bg-red-50
               px-5 py-4"
    >

        <p class="font-semibold text-red-700 mb-2">
            Terdapat kesalahan:
        </p>

        <ul class="list-disc list-inside text-sm text-red-600">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<div
    class="max-w-3xl
           bg-white
           rounded-xl
           border border-gray-200"
>

    <div
        class="px-6 py-5
               border-b border-gray-200"
    >

        <h2 class="font-semibold text-gray-900">
            Informasi Customer
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Masukkan informasi customer.
        </p>

    </div>


    <form
        method="POST"
        action="{{ route('admin.customers.store') }}"
    >

        @csrf


        <div class="p-6 space-y-6">


            {{-- NAMA --}}

            <div>

                <label
                    for="name"
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Nama Customer
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    placeholder="Contoh: Budi Santoso"
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-3
                           text-sm
                           outline-none
                           focus:border-gray-900
                           focus:ring-1
                           focus:ring-gray-900"
                >

            </div>


            {{-- TELEPON --}}

            <div>

                <label
                    for="phone"
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Nomor Telepon
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="Contoh: 081234567890"
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-3
                           text-sm
                           outline-none
                           focus:border-gray-900
                           focus:ring-1
                           focus:ring-gray-900"
                >

            </div>


            {{-- ALAMAT --}}

            <div>

                <label
                    for="address"
                    class="block text-sm
                           font-medium
                           text-gray-700 mb-2"
                >
                    Alamat
                </label>

                <textarea
                    id="address"
                    name="address"
                    rows="4"
                    placeholder="Masukkan alamat customer..."
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-3
                           text-sm
                           outline-none
                           resize-none
                           focus:border-gray-900
                           focus:ring-1
                           focus:ring-gray-900"
                >{{ old('address') }}</textarea>

            </div>

        </div>


        {{-- FOOTER --}}

        <div
            class="px-6 py-5
                   border-t border-gray-200
                   flex items-center
                   justify-end gap-3"
        >

            <a
                href="{{ route('admin.customers.index') }}"
                class="px-5 py-3
                       rounded-lg
                       border border-gray-300
                       text-gray-700
                       text-sm font-medium
                       hover:bg-gray-50"
            >
                Batal
            </a>


            <button
                type="submit"
                class="px-5 py-3
                       rounded-lg
                       bg-gray-900
                       text-white
                       text-sm font-medium
                       hover:bg-gray-800"
            >
                Simpan Customer
            </button>

        </div>

    </form>

</div>

@endsection