@extends('layouts.admin')

@section('title', 'Detail Activity Log')

@section('page-title', 'Detail Activity Log')

@section('content')

<div class="mb-6">

    <a
        href="{{ route('admin.activity-logs.index') }}"
        class="text-sm text-gray-600 hover:text-black"
    >
        ← Kembali ke Activity Log
    </a>

</div>


<div class="bg-white
            border border-gray-200
            rounded-xl
            overflow-hidden">

    <div class="px-6 py-5
                border-b border-gray-200">

        <h1 class="text-xl font-bold text-gray-900">
            Detail Aktivitas
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Informasi lengkap aktivitas yang dilakukan.
        </p>

    </div>


    <div class="p-6">

        <div class="grid grid-cols-1
                    md:grid-cols-2
                    gap-6">

            <div>

                <p class="text-sm text-gray-500">
                    Pengguna
                </p>

                <p class="mt-1 font-medium text-gray-900">

                    {{ $activityLog->user->name ?? 'Pengguna tidak tersedia' }}

                </p>

            </div>


            <div>

                <p class="text-sm text-gray-500">
                    Waktu
                </p>

                <p class="mt-1 font-medium text-gray-900">

                    {{ $activityLog->created_at->format('d/m/Y H:i:s') }}

                </p>

            </div>


            <div>

                <p class="text-sm text-gray-500">
                    Aksi
                </p>

                <p class="mt-1 font-medium text-gray-900">

                    {{ $activityLog->action }}

                </p>

            </div>


            <div>

                <p class="text-sm text-gray-500">
                    Modul
                </p>

                <p class="mt-1 font-medium text-gray-900">

                    {{ $activityLog->module }}

                </p>

            </div>


            <div class="md:col-span-2">

                <p class="text-sm text-gray-500">
                    Aktivitas
                </p>

                <p class="mt-1 font-medium text-gray-900">

                    {{ $activityLog->description }}

                </p>

            </div>

        </div>


        {{-- DATA LAMA --}}

        @if($activityLog->old_data)

            <div class="mt-8">

                <h2 class="font-semibold text-gray-900">
                    Data Sebelum
                </h2>

                <pre
                    class="mt-3 p-4
                           bg-gray-50
                           border border-gray-200
                           rounded-lg
                           text-xs
                           text-gray-700
                           overflow-x-auto"
                >{{ json_encode(
                    $activityLog->old_data,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE
                ) }}</pre>

            </div>

        @endif


        {{-- DATA BARU --}}

        @if($activityLog->new_data)

            <div class="mt-8">

                <h2 class="font-semibold text-gray-900">
                    Data Setelah
                </h2>

                <pre
                    class="mt-3 p-4
                           bg-gray-50
                           border border-gray-200
                           rounded-lg
                           text-xs
                           text-gray-700
                           overflow-x-auto"
                >{{ json_encode(
                    $activityLog->new_data,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE
                ) }}</pre>

            </div>

        @endif

    </div>

</div>

@endsection