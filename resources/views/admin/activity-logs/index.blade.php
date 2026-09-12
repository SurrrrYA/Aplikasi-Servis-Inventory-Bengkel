@extends('layouts.admin')

@section('title', 'Activity Log')

@section('page-title', 'Activity Log')

@section('content')

<div class="flex flex-col sm:flex-row
            sm:items-center sm:justify-between
            gap-4 mb-6">

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Activity Log
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Riwayat aktivitas yang dilakukan pengguna.
        </p>

    </div>

</div>


{{-- FILTER --}}

<div class="bg-white
            border border-gray-200
            rounded-xl p-5 mb-6">

    <form
        method="GET"
        action="{{ route('admin.activity-logs.index') }}"
        class="grid grid-cols-1 md:grid-cols-5 gap-4"
    >

        {{-- SEARCH --}}

        <div class="md:col-span-2">

            <label class="block text-sm font-medium
                          text-gray-700 mb-2">
                Cari
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari aktivitas..."
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


        {{-- USER --}}

        <div>

            <label class="block text-sm font-medium
                          text-gray-700 mb-2">
                Pengguna
            </label>

            <select
                name="user_id"
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none"
            >

                <option value="">
                    Semua
                </option>

                @foreach($users as $user)

                    <option
                        value="{{ $user->id }}"
                        @selected(
                            request('user_id') == $user->id
                        )
                    >
                        {{ $user->name }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- ACTION --}}

        <div>

            <label class="block text-sm font-medium
                          text-gray-700 mb-2">
                Aksi
            </label>

            <select
                name="action"
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none"
            >

                <option value="">
                    Semua
                </option>

                @foreach($actions as $action)

                    <option
                        value="{{ $action }}"
                        @selected(
                            request('action') === $action
                        )
                    >
                        {{ $action }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- MODULE --}}

        <div>

            <label class="block text-sm font-medium
                          text-gray-700 mb-2">
                Modul
            </label>

            <select
                name="module"
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none"
            >

                <option value="">
                    Semua
                </option>

                @foreach($modules as $module)

                    <option
                        value="{{ $module }}"
                        @selected(
                            request('module') === $module
                        )
                    >
                        {{ $module }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- BUTTON --}}

        <div class="md:col-span-5 flex gap-2">

            <button
                type="submit"
                class="px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm font-medium
                       hover:bg-gray-800"
            >
                Filter
            </button>

            <a
                href="{{ route('admin.activity-logs.index') }}"
                class="px-4 py-2.5
                       border border-gray-300
                       rounded-lg
                       text-sm font-medium
                       text-gray-700
                       hover:bg-gray-50"
            >
                Reset
            </a>

        </div>

    </form>

</div>


{{-- TABLE --}}

<div class="bg-white
            border border-gray-200
            rounded-xl
            overflow-hidden">

    <div class="px-6 py-5
                border-b border-gray-200">

        <h2 class="font-semibold text-gray-900">
            Riwayat Aktivitas
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Semua aktivitas pengguna yang tercatat dalam sistem.
        </p>

    </div>


    @if($logs->count())

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead
                    class="bg-gray-50
                           border-b border-gray-200"
                >

                    <tr>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Waktu
                        </th>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Pengguna
                        </th>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Aksi
                        </th>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Modul
                        </th>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Aktivitas
                        </th>

                        <th class="px-6 py-4 text-right
                                   font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @foreach($logs as $log)

                        <tr class="hover:bg-gray-50">

                            {{-- WAKTU --}}

                            <td class="px-6 py-4 text-gray-600">

                                <div>
                                    {{ $log->created_at->format('d/m/Y') }}
                                </div>

                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $log->created_at->format('H:i:s') }}
                                </div>

                            </td>


                            {{-- USER --}}

                            <td class="px-6 py-4">

                                @if($log->user)

                                    <div class="font-medium text-gray-900">
                                        {{ $log->user->name }}
                                    </div>

                                @else

                                    <span class="text-gray-400">
                                        Pengguna tidak tersedia
                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}

                            <td class="px-6 py-4">

                                <span
                                    class="inline-flex
                                           px-2.5 py-1
                                           rounded-full
                                           text-xs
                                           font-medium
                                           bg-gray-100
                                           text-gray-700"
                                >
                                    {{ $log->action }}
                                </span>

                            </td>


                            {{-- MODULE --}}

                            <td class="px-6 py-4">

                                <span class="text-gray-700">
                                    {{ $log->module }}
                                </span>

                            </td>


                            {{-- DESCRIPTION --}}

                            <td class="px-6 py-4">

                                <span class="text-gray-700">
                                    {{ $log->description }}
                                </span>

                            </td>


                            {{-- DETAIL --}}

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route(
                                        'admin.activity-logs.show',
                                        $log
                                    ) }}"
                                    class="text-sm
                                           font-medium
                                           text-gray-700
                                           hover:text-black"
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

        <div class="px-6 py-4
                    border-t border-gray-200">

            {{ $logs->links() }}

        </div>

    @else

        <div class="py-16 text-center">

            <div class="text-4xl mb-3">
                📋
            </div>

            <p class="font-medium text-gray-700">
                Belum ada activity log
            </p>

            <p class="text-sm text-gray-500 mt-1">
                Aktivitas pengguna akan muncul di sini.
            </p>

        </div>

    @endif

</div>

@endsection