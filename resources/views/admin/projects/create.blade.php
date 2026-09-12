@extends('layouts.admin')

@section('title', 'Tambah Project')
@section('page-title', 'Tambah Project')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Tambah Project
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Tambahkan project baru ke sistem.
            </p>
        </div>

        <a
            href="{{ route('admin.projects.index') }}"
            class="px-4 py-2.5
                   border border-gray-300
                   rounded-lg
                   text-sm font-medium
                   text-gray-700
                   hover:bg-gray-50
                   transition"
        >
            Kembali
        </a>

    </div>


    {{-- FORM --}}
    <div class="bg-white border border-gray-200
                rounded-xl shadow-sm">

        <form
            action="{{ route('admin.projects.store') }}"
            method="POST"
        >

            @csrf

            <div class="p-6 space-y-5">

                {{-- NAMA PROJECT --}}
                <div>

                    <label
                        for="name"
                        class="block mb-2
                               text-sm font-medium
                               text-gray-700"
                    >
                        Nama Project
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Custom Minibike"
                        required
                        class="w-full px-3 py-2.5
                               border border-gray-300
                               rounded-lg
                               text-sm text-gray-900
                               focus:outline-none
                               focus:ring-2
                               focus:ring-gray-900
                               focus:border-gray-900
                               @error('name') border-red-500 @enderror"
                    >

                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- CUSTOMER --}}
                <div>

                    <label
                        for="customer_name"
                        class="block mb-2
                               text-sm font-medium
                               text-gray-700"
                    >
                        Nama Customer
                    </label>

                    <input
                        type="text"
                        name="customer_name"
                        id="customer_name"
                        value="{{ old('customer_name') }}"
                        placeholder="Contoh: Surya"
                        class="w-full px-3 py-2.5
                               border border-gray-300
                               rounded-lg
                               text-sm text-gray-900
                               focus:outline-none
                               focus:ring-2
                               focus:ring-gray-900
                               focus:border-gray-900
                               @error('customer_name') border-red-500 @enderror"
                    >

                    @error('customer_name')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- STATUS --}}
                <div>

                    <label
                        for="status"
                        class="block mb-2
                               text-sm font-medium
                               text-gray-700"
                    >
                        Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        required
                        class="w-full px-3 py-2.5
                               border border-gray-300
                               rounded-lg
                               text-sm text-gray-900
                               bg-white
                               focus:outline-none
                               focus:ring-2
                               focus:ring-gray-900
                               focus:border-gray-900
                               @error('status') border-red-500 @enderror"
                    >

                        <option
                            value="draft"
                            {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}
                        >
                            Draft
                        </option>

                        <option
                            value="process"
                            {{ old('status') === 'process' ? 'selected' : '' }}
                        >
                            Process
                        </option>

                        <option
                            value="completed"
                            {{ old('status') === 'completed' ? 'selected' : '' }}
                        >
                            Completed
                        </option>

                        <option
                            value="cancelled"
                            {{ old('status') === 'cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                    </select>

                    @error('status')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- FOOTER FORM --}}
            <div
                class="flex items-center justify-end gap-3
                       px-6 py-4
                       bg-gray-50
                       border-t border-gray-200
                       rounded-b-xl"
            >

                <a
                    href="{{ route('admin.projects.index') }}"
                    class="px-4 py-2.5
                           border border-gray-300
                           rounded-lg
                           text-sm font-medium
                           text-gray-700
                           bg-white
                           hover:bg-gray-50
                           transition"
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
                           hover:bg-gray-800
                           transition"
                >
                    Simpan Project
                </button>

            </div>

        </form>

    </div>

</div>

@endsection