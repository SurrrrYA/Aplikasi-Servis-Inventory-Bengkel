@extends('layouts.admin')

@section('title', 'Jasa')

@section('page-title', 'Jasa')

@section('content')

{{-- ================================================= --}}
{{-- HEADER --}}
{{-- ================================================= --}}

<div class="flex flex-col sm:flex-row
            sm:items-center sm:justify-between
            gap-4 mb-6">

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Jasa
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Kelola daftar jasa servis dan harga jasa.
        </p>

    </div>


    <a
        href="{{ route('admin.services.create') }}"
        class="inline-flex items-center justify-center
               gap-2 px-4 py-2.5
               bg-gray-900 text-white
               rounded-lg text-sm font-medium
               hover:bg-gray-800 transition"
    >
        <span>+</span>
        Tambah Jasa
    </a>

</div>



{{-- ================================================= --}}
{{-- ALERT SUCCESS --}}
{{-- ================================================= --}}

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



{{-- ================================================= --}}
{{-- STATISTIC --}}
{{-- ================================================= --}}

<div
    class="grid grid-cols-1
           sm:grid-cols-2
           gap-5 mb-6"
>

    {{-- TOTAL JASA --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl p-5"
    >

        <p class="text-sm text-gray-500">
            Total Jasa
        </p>

        <p
            class="mt-2 text-2xl
                   font-bold text-gray-900"
        >
            {{ $services->total() }}
        </p>

    </div>


    {{-- HARGA TERTINGGI --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl p-5"
    >

        <p class="text-sm text-gray-500">
            Jasa Tersedia
        </p>

        <p
            class="mt-2 text-2xl
                   font-bold text-gray-900"
        >
            {{ $services->total() }}
        </p>

    </div>

</div>



{{-- ================================================= --}}
{{-- SEARCH --}}
{{-- ================================================= --}}

<div
    class="bg-white
           border border-gray-200
           rounded-xl p-5 mb-6"
>

    <form
        method="GET"
        action="{{ route('admin.services.index') }}"
        class="flex flex-col sm:flex-row gap-3"
    >

        <div class="flex-1">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau kode jasa..."
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       focus:border-gray-500
                       focus:ring-1
                       focus:ring-gray-500
                       outline-none"
            >

        </div>


        <button
            type="submit"
            class="px-5 py-2.5
                   bg-gray-900
                   text-white
                   rounded-lg
                   text-sm font-medium
                   hover:bg-gray-800"
        >
            Cari
        </button>


        @if(request('search'))

            <a
                href="{{ route('admin.services.index') }}"
                class="px-5 py-2.5
                       border border-gray-300
                       rounded-lg
                       text-sm font-medium
                       text-gray-700
                       hover:bg-gray-50
                       text-center"
            >
                Reset
            </a>

        @endif

    </form>

</div>



{{-- ================================================= --}}
{{-- TABLE --}}
{{-- ================================================= --}}

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

        <h2 class="font-semibold text-gray-900">
            Daftar Jasa
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Jasa servis yang tersedia di bengkel.
        </p>

    </div>


    @if($services->count())

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
                            Jasa
                        </th>

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Kode
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
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Deskripsi
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
                    class="divide-y divide-gray-100"
                >

                    @foreach($services as $service)

                        <tr
                            class="hover:bg-gray-50"
                        >

                            {{-- NAMA --}}

                            <td class="px-6 py-4">

                                <p
                                    class="font-medium
                                           text-gray-900"
                                >
                                    {{ $service->name }}
                                </p>

                            </td>


                            {{-- KODE --}}

                            <td
                                class="px-6 py-4
                                       text-gray-600"
                            >

                                {{ $service->code }}

                            </td>


                            {{-- HARGA --}}

                            <td
                                class="px-6 py-4
                                       text-right
                                       font-medium
                                       text-gray-900"
                            >

                                Rp
                                {{ number_format(
                                    $service->price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            {{-- DESKRIPSI --}}

                            <td
                                class="px-6 py-4
                                       text-gray-500"
                            >

                                @if($service->description)

                                    {{ Str::limit(
                                        $service->description,
                                        50
                                    ) }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- AKSI --}}

                            <td
                                class="px-6 py-4
                                       text-right"
                            >

                                <a
                                    href="{{ route(
                                        'admin.services.show',
                                        $service
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

        <div
            class="px-6 py-4
                   border-t border-gray-200"
        >

            {{ $services->links() }}

        </div>

    @else

        <div
            class="py-16
                   text-center"
        >

            <div class="text-4xl mb-3">
                🔧
            </div>

            <p
                class="font-medium
                       text-gray-700"
            >
                Belum ada jasa
            </p>

            <p
                class="text-sm
                       text-gray-500
                       mt-1"
            >
                Jasa yang ditambahkan
                akan muncul di sini.
            </p>

        </div>

    @endif

</div>

@endsection