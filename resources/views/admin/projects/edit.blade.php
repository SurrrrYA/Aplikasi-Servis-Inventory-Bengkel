@extends('layouts.admin')

@section('title', 'Edit Project')

@section('page-title', 'Edit Project')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <div class="mb-6">

        <a
            href="{{ route('admin.projects.show', $project) }}"
            class="inline-flex items-center gap-2
                   text-sm text-gray-500
                   hover:text-gray-900
                   mb-4"
        >
            ← Kembali ke Detail Project
        </a>

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Edit Project
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Ubah informasi project yang diperlukan.
            </p>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- FORM --}}
    {{-- ================================================= --}}

    <form
        action="{{ route('admin.projects.update', $project) }}"
        method="POST"
        class="space-y-6"
    >

        @csrf

        @method('PUT')


        {{-- ================================================= --}}
        {{-- INFORMASI PROJECT --}}
        {{-- ================================================= --}}

        <div class="bg-white border border-gray-200 rounded-xl">

            {{-- CARD HEADER --}}

            <div class="px-6 py-5 border-b border-gray-200">

                <h2 class="text-base font-semibold text-gray-900">
                    Informasi Project
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi dasar project.
                </p>

            </div>


            {{-- CARD CONTENT --}}

            <div class="p-6 space-y-5">


                {{-- ================================================= --}}
                {{-- KODE PROJECT --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        class="block text-sm font-medium
                               text-gray-700 mb-2"
                    >
                        Kode Project
                    </label>

                    <input
                        type="text"
                        value="{{ $project->code }}"
                        disabled
                        class="w-full px-4 py-2.5
                               bg-gray-100
                               border border-gray-200
                               rounded-lg
                               text-sm text-gray-500
                               cursor-not-allowed"
                    >

                    <p class="mt-1.5 text-xs text-gray-500">
                        Kode project dibuat otomatis dan tidak dapat diubah.
                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- NAMA PROJECT --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="name"
                        class="block text-sm font-medium
                               text-gray-700 mb-2"
                    >
                        Nama Project
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $project->name) }}"
                        required
                        maxlength="255"
                        placeholder="Masukkan nama project"
                        class="w-full px-4 py-2.5
                               border border-gray-300
                               rounded-lg
                               text-sm text-gray-900
                               placeholder-gray-400
                               focus:outline-none
                               focus:ring-2
                               focus:ring-gray-900
                               focus:border-gray-900"
                    >

                    @error('name')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- CUSTOMER --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="customer_name"
                        class="block text-sm font-medium
                               text-gray-700 mb-2"
                    >
                        Nama Customer
                    </label>

                    <input
                        type="text"
                        id="customer_name"
                        name="customer_name"
                        value="{{ old(
                            'customer_name',
                            $project->customer_name
                        ) }}"
                        maxlength="255"
                        placeholder="Masukkan nama customer"
                        class="w-full px-4 py-2.5
                               border border-gray-300
                               rounded-lg
                               text-sm text-gray-900
                               placeholder-gray-400
                               focus:outline-none
                               focus:ring-2
                               focus:ring-gray-900
                               focus:border-gray-900"
                    >

                    @error('customer_name')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- STATUS --}}
        {{-- ================================================= --}}

        <div class="bg-white border border-gray-200 rounded-xl">

            {{-- CARD HEADER --}}

            <div class="px-6 py-5 border-b border-gray-200">

                <h2 class="text-base font-semibold text-gray-900">
                    Status Project
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Atur status project sesuai dengan proses pengerjaan.
                </p>

            </div>


            {{-- CARD CONTENT --}}

            <div class="p-6">


                {{-- ================================================= --}}
                {{-- STATUS --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="status"
                        class="block text-sm font-medium
                               text-gray-700 mb-2"
                    >
                        Status
                        <span class="text-red-500">*</span>
                    </label>

                    @php

                        $currentStatus = old(
                            'status',
                            $project->status
                        );

                    @endphp

                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full px-4 py-2.5
                               bg-white
                               border border-gray-300
                               rounded-lg
                               text-sm text-gray-900
                               focus:outline-none
                               focus:ring-2
                               focus:ring-gray-900
                               focus:border-gray-900"
                    >

                        <option
                            value="draft"
                            {{ $currentStatus === 'draft'
                                ? 'selected'
                                : ''
                            }}
                        >
                            Draft
                        </option>

                        <option
                            value="process"
                            {{ $currentStatus === 'process'
                                ? 'selected'
                                : ''
                            }}
                        >
                            Process
                        </option>

                        <option
                            value="completed"
                            {{ $currentStatus === 'completed'
                                ? 'selected'
                                : ''
                            }}
                        >
                            Completed
                        </option>

                        <option
                            value="cancelled"
                            {{ $currentStatus === 'cancelled'
                                ? 'selected'
                                : ''
                            }}
                        >
                            Cancelled
                        </option>

                    </select>

                    @error('status')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- INFORMASI PERHITUNGAN --}}
        {{-- ================================================= --}}

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">

            <div class="flex gap-3">

                <div
                    class="w-8 h-8 shrink-0
                           rounded-full
                           bg-white
                           border border-gray-200
                           flex items-center justify-center"
                >
                    <span class="text-sm">
                        ℹ️
                    </span>
                </div>

                <div>

                    <h3 class="text-sm font-semibold text-gray-900">
                        Perhitungan Project
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 leading-6">
                        Total project dihitung secara otomatis berdasarkan
                        seluruh barang, jasa, dan biaya tambahan yang
                        terdapat pada project.
                    </p>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- ACTION --}}
        {{-- ================================================= --}}

        <div
            class="flex flex-col-reverse sm:flex-row
                   sm:items-center sm:justify-end
                   gap-3"
        >

            <a
                href="{{ route('admin.projects.show', $project) }}"
                class="w-full sm:w-auto
                       px-5 py-2.5
                       border border-gray-300
                       rounded-lg
                       text-sm font-medium
                       text-gray-700
                       text-center
                       hover:bg-gray-50"
            >
                Batal
            </a>


            <button
                type="submit"
                class="w-full sm:w-auto
                       px-5 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm font-medium
                       hover:bg-gray-800
                       focus:outline-none
                       focus:ring-2
                       focus:ring-gray-900
                       focus:ring-offset-2"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection