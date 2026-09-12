@extends('layouts.admin')

@section('title', 'Project')

@section('page-title', 'Project')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col sm:flex-row
                sm:items-center sm:justify-between
                gap-4 mb-6">

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Project
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola project dan perhitungan biaya.
            </p>

        </div>


        <a
            href="{{ route('admin.projects.create') }}"
            class="inline-flex items-center justify-center
                   gap-2 px-4 py-2.5
                   bg-gray-900 text-white
                   rounded-lg text-sm font-medium
                   hover:bg-gray-800
                   transition"
        >

            <span class="text-lg leading-none">
                +
            </span>

            Tambah Project

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- STATISTIC --}}
    {{-- ========================================================= --}}

    <div
        class="grid grid-cols-1
               sm:grid-cols-3
               gap-5 mb-6"
    >

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

            <p
                class="mt-2 text-2xl
                       font-bold text-gray-900"
            >
                {{ $projects->total() }}
            </p>

        </div>


        {{-- PROJECT DITAMPILKAN --}}

        <div
            class="bg-white
                   border border-gray-200
                   rounded-xl
                   p-5"
        >

            <p class="text-sm text-gray-500">
                Ditampilkan
            </p>

            <p
                class="mt-2 text-2xl
                       font-bold text-gray-900"
            >
                {{ $projects->count() }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Data pada halaman ini
            </p>

        </div>


        {{-- HALAMAN --}}

        <div
            class="bg-white
                   border border-gray-200
                   rounded-xl
                   p-5"
        >

            <p class="text-sm text-gray-500">
                Halaman
            </p>

            <p
                class="mt-2 text-2xl
                       font-bold text-gray-900"
            >
                {{ $projects->currentPage() }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                dari {{ $projects->lastPage() }} halaman
            </p>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl
               p-5
               mb-6"
    >

        <form
            method="GET"
            action="{{ route('admin.projects.index') }}"
            class="grid grid-cols-1
                   md:grid-cols-4
                   gap-4"
        >

            {{-- SEARCH --}}

            <div class="md:col-span-2">

                <label
                    for="search"
                    class="block text-sm
                           font-medium
                           text-gray-700
                           mb-2"
                >
                    Cari Project
                </label>

                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Cari kode, nama project, atau customer..."
                    class="w-full
                           rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           outline-none
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500"
                >

            </div>


            {{-- STATUS --}}

            <div>

                <label
                    for="status"
                    class="block text-sm
                           font-medium
                           text-gray-700
                           mb-2"
                >
                    Status
                </label>

                <select
                    name="status"
                    id="status"
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
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="draft"
                        @selected(request('status') === 'draft')
                    >
                        Draft
                    </option>

                    <option
                        value="process"
                        @selected(request('status') === 'process')
                    >
                        Process
                    </option>

                    <option
                        value="completed"
                        @selected(request('status') === 'completed')
                    >
                        Completed
                    </option>

                    <option
                        value="cancelled"
                        @selected(request('status') === 'cancelled')
                    >
                        Cancelled
                    </option>

                </select>

            </div>


            {{-- BUTTON --}}

            <div
                class="flex items-end gap-2"
            >

                <button
                    type="submit"
                    class="flex-1
                           px-4 py-2.5
                           bg-gray-900
                           text-white
                           rounded-lg
                           text-sm
                           font-medium
                           hover:bg-gray-800
                           transition"
                >
                    Filter
                </button>


                @if(request('search') || request('status'))

                    <a
                        href="{{ route('admin.projects.index') }}"
                        class="px-4 py-2.5
                               border border-gray-300
                               rounded-lg
                               text-sm
                               font-medium
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


    {{-- ========================================================= --}}
    {{-- TABLE --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl
               overflow-hidden"
    >

        {{-- TABLE HEADER --}}

        <div
            class="px-6 py-5
                   border-b border-gray-200"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Daftar Project
            </h2>

            <p
                class="text-sm
                       text-gray-500
                       mt-1"
            >
                Project yang sedang dikelola beserta perhitungan biayanya.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- DATA ADA --}}
        {{-- ===================================================== --}}

        @if($projects->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    {{-- TABLE HEAD --}}

                    <thead
                        class="bg-gray-50
                               border-b
                               border-gray-200"
                    >

                        <tr>

                            {{-- PROJECT --}}

                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-gray-600"
                            >
                                Project
                            </th>


                            {{-- CUSTOMER --}}

                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-gray-600"
                            >
                                Customer
                            </th>


                            {{-- TOTAL BIAYA --}}

                            <th
                                class="px-6 py-4
                                       text-right
                                       font-semibold
                                       text-gray-600"
                            >
                                Total Biaya
                            </th>


                            {{-- STATUS --}}

                            <th
                                class="px-6 py-4
                                       text-center
                                       font-semibold
                                       text-gray-600"
                            >
                                Status
                            </th>


                            {{-- AKSI --}}

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


                    {{-- TABLE BODY --}}

                    <tbody
                        class="divide-y
                               divide-gray-100"
                    >

                        @foreach($projects as $project)

                            <tr
                                class="hover:bg-gray-50
                                       transition"
                            >

                                {{-- PROJECT --}}

                                <td class="px-6 py-4">

                                    <div>

                                        <p
                                            class="font-medium
                                                   text-gray-900"
                                        >
                                            {{ $project->name }}
                                        </p>

                                        <p
                                            class="text-xs
                                                   text-gray-500
                                                   mt-1"
                                        >
                                            {{ $project->code }}
                                        </p>

                                    </div>

                                </td>


                                {{-- CUSTOMER --}}

                                <td
                                    class="px-6 py-4
                                           text-gray-600"
                                >

                                    {{ $project->customer_name ?? '-' }}

                                </td>


                                {{-- TOTAL BIAYA --}}

                                <td
                                    class="px-6 py-4
                                           text-right
                                           font-medium
                                           text-gray-900
                                           whitespace-nowrap"
                                >

                                    Rp
                                    {{ number_format(
                                        $project->total,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- STATUS --}}

                                <td
                                    class="px-6 py-4
                                           text-center"
                                >

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
                                                   bg-gray-50
                                                   text-gray-600"
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

                                        {{-- DETAIL --}}

                                        <a
                                            href="{{ route(
                                                'admin.projects.show',
                                                $project
                                            ) }}"
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1.5
                                                   rounded-lg
                                                   text-sm
                                                   font-medium
                                                   text-gray-700
                                                   hover:bg-gray-100
                                                   hover:text-black
                                                   transition"
                                        >
                                            Detail
                                        </a>


                                        {{-- EDIT --}}

                                        <a
                                            href="{{ route(
                                                'admin.projects.edit',
                                                $project
                                            ) }}"
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1.5
                                                   rounded-lg
                                                   text-sm
                                                   font-medium
                                                   text-gray-700
                                                   hover:bg-gray-100
                                                   hover:text-black
                                                   transition"
                                        >
                                            Edit
                                        </a>


                                        {{-- DELETE --}}

                                        <form
                                            action="{{ route(
                                                'admin.projects.destroy',
                                                $project
                                            ) }}"
                                            method="POST"
                                            onsubmit="return confirm(
                                                'Yakin ingin menghapus project ini?'
                                            )"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex
                                                       items-center
                                                       px-3 py-1.5
                                                       rounded-lg
                                                       text-sm
                                                       font-medium
                                                       text-gray-700
                                                       hover:bg-gray-100
                                                       hover:text-black
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


            {{-- ================================================= --}}
            {{-- PAGINATION --}}
            {{-- ================================================= --}}

            @if($projects->hasPages())

                <div
                    class="px-6 py-4
                           border-t
                           border-gray-200"
                >

                    {{ $projects->withQueryString()->links() }}

                </div>

            @endif


        @else

            {{-- ================================================= --}}
            {{-- EMPTY STATE --}}
            {{-- ================================================= --}}

            <div
                class="py-16
                       text-center"
            >

                <div
                    class="text-4xl
                           mb-3"
                >
                    📁
                </div>


                <p
                    class="font-medium
                           text-gray-700"
                >
                    Belum ada project
                </p>


                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >

                    @if(request('search') || request('status'))

                        Tidak ada project yang sesuai
                        dengan filter yang dipilih.

                    @else

                        Project yang ditambahkan
                        akan muncul di sini.

                    @endif

                </p>


                @if(request('search') || request('status'))

                    <a
                        href="{{ route('admin.projects.index') }}"
                        class="inline-flex
                               mt-5
                               px-4 py-2.5
                               border border-gray-300
                               text-gray-700
                               rounded-lg
                               text-sm
                               font-medium
                               hover:bg-gray-50
                               transition"
                    >
                        Reset Filter
                    </a>

                @else

                    <a
                        href="{{ route('admin.projects.create') }}"
                        class="inline-flex
                               mt-5
                               px-4 py-2.5
                               bg-gray-900
                               text-white
                               rounded-lg
                               text-sm
                               font-medium
                               hover:bg-gray-800
                               transition"
                    >
                        Tambah Project
                    </a>

                @endif

            </div>

        @endif

    </div>

</div>

@endsection