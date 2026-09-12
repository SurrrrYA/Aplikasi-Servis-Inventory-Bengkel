@extends('layouts.admin')

@section('title', 'Detail Jasa')

@section('page-title', 'Detail Jasa')

@section('content')

<div class="max-w-4xl">

    {{-- HEADER --}}

    <div
        class="flex flex-col sm:flex-row
               sm:items-center
               sm:justify-between
               gap-4 mb-6"
    >

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Detail Jasa
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Informasi lengkap jasa servis.
            </p>

        </div>


        <div class="flex gap-2">

            <a
                href="{{ route(
                    'admin.services.edit',
                    $service
                ) }}"
                class="px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm font-medium
                       hover:bg-gray-800"
            >
                Edit
            </a>

        </div>

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

            <h2 class="font-semibold text-gray-900">
                Informasi Jasa
            </h2>

        </div>


        <div class="p-6">

            <div
                class="grid grid-cols-1
                       sm:grid-cols-2
                       gap-6"
            >

                {{-- KODE --}}

                <div>

                    <p
                        class="text-sm
                               text-gray-500"
                    >
                        Kode Jasa
                    </p>

                    <p
                        class="mt-1
                               font-semibold
                               text-gray-900"
                    >
                        {{ $service->code }}
                    </p>

                </div>


                {{-- NAMA --}}

                <div>

                    <p
                        class="text-sm
                               text-gray-500"
                    >
                        Nama Jasa
                    </p>

                    <p
                        class="mt-1
                               font-semibold
                               text-gray-900"
                    >
                        {{ $service->name }}
                    </p>

                </div>


                {{-- HARGA --}}

                <div>

                    <p
                        class="text-sm
                               text-gray-500"
                    >
                        Harga
                    </p>

                    <p
                        class="mt-1
                               text-xl
                               font-bold
                               text-gray-900"
                    >
                        Rp
                        {{ number_format(
                            $service->price,
                            0,
                            ',',
                            '.'
                        ) }}
                    </p>

                </div>


                {{-- CREATED --}}

                <div>

                    <p
                        class="text-sm
                               text-gray-500"
                    >
                        Ditambahkan
                    </p>

                    <p
                        class="mt-1
                               font-medium
                               text-gray-900"
                    >
                        {{ $service->created_at
                            ? $service->created_at
                                ->format('d M Y H:i')
                            : '-'
                        }}
                    </p>

                </div>

            </div>


            {{-- DESKRIPSI --}}

            <div
                class="mt-8
                       pt-6
                       border-t
                       border-gray-200"
            >

                <p
                    class="text-sm
                           text-gray-500"
                >
                    Deskripsi
                </p>

                <p
                    class="mt-2
                           text-gray-700
                           leading-relaxed"
                >

                    @if($service->description)

                        {{ $service->description }}

                    @else

                        Tidak ada deskripsi.

                    @endif

                </p>

            </div>

        </div>

    </div>


    {{-- HAPUS --}}

    <div
        class="mt-6
               bg-white
               border border-gray-200
               rounded-xl
               p-6"
    >

        <div
            class="flex flex-col
                   sm:flex-row
                   sm:items-center
                   sm:justify-between
                   gap-4"
        >

            <div>

                <h3
                    class="font-semibold
                           text-gray-900"
                >
                    Hapus Jasa
                </h3>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Jasa yang dihapus tidak dapat dikembalikan.
                </p>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.services.destroy',
                    $service
                ) }}"
                onsubmit="return confirm(
                    'Yakin ingin menghapus jasa ini?'
                )"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="px-4 py-2.5
                           border border-gray-300
                           rounded-lg
                           text-sm font-medium
                           text-gray-700
                           hover:bg-gray-100"
                >
                    Hapus Jasa
                </button>

            </form>

        </div>

    </div>


    {{-- BACK --}}

    <div class="mt-6">

        <a
            href="{{ route('admin.services.index') }}"
            class="text-sm
                   font-medium
                   text-gray-600
                   hover:text-black"
        >
            ← Kembali ke daftar jasa
        </a>

    </div>

</div>

@endsection