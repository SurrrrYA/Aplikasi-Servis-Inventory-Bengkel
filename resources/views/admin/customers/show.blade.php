@extends('layouts.admin')

@section('title', 'Detail Customer')

@section('page-title', 'Detail Customer')

@section('content')

{{-- ================================================= --}}
{{-- HEADER --}}
{{-- ================================================= --}}

<div class="mb-8">

    <div
        class="flex flex-col
               sm:flex-row
               sm:items-center
               sm:justify-between
               gap-4"
    >

        <div>

            <div class="flex items-center gap-3">

                <a
                    href="{{ route('admin.customers.index') }}"
                    class="text-gray-500
                           hover:text-gray-900"
                >
                    ←
                </a>

                <h1
                    class="text-2xl
                           font-bold
                           text-gray-900"
                >
                    Detail Customer
                </h1>

            </div>

            <p
                class="mt-1
                       text-sm
                       text-gray-500
                       ml-7"
            >
                Informasi lengkap customer dan kendaraan.
            </p>

        </div>


        {{-- AKSI --}}

        <div
            class="flex items-center
                   gap-2"
        >

            <a
                href="{{
                    route(
                        'admin.customers.edit',
                        $customer
                    )
                }}"
                class="px-5 py-3
                       rounded-lg
                       bg-gray-900
                       text-white
                       text-sm
                       font-medium
                       hover:bg-gray-800"
            >
                Edit Customer
            </a>


            <form
                method="POST"
                action="{{
                    route(
                        'admin.customers.destroy',
                        $customer
                    )
                }}"
                onsubmit="return confirm(
                    'Yakin ingin menghapus customer ini?'
                )"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="px-5 py-3
                           rounded-lg
                           border
                           border-red-200
                           text-red-600
                           text-sm
                           font-medium
                           hover:bg-red-50"
                >
                    Hapus
                </button>

            </form>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- SUCCESS --}}
{{-- ================================================= --}}

@if(session('success'))

    <div
        class="mb-6
               rounded-lg
               border border-green-200
               bg-green-50
               px-5 py-4"
    >

        <p
            class="text-sm
                   font-medium
                   text-green-700"
        >
            {{ session('success') }}
        </p>

    </div>

@endif



{{-- ================================================= --}}
{{-- CUSTOMER INFO --}}
{{-- ================================================= --}}

<div
    class="grid grid-cols-1
           lg:grid-cols-3
           gap-6"
>


    {{-- PROFILE --}}

    <div
        class="lg:col-span-1
               bg-white
               rounded-xl
               border
               border-gray-200"
    >

        <div
            class="p-6
                   text-center"
        >

            <div
                class="w-20 h-20
                       rounded-full
                       bg-gray-900
                       text-white
                       flex items-center
                       justify-center
                       text-2xl
                       font-bold
                       mx-auto"
            >

                {{
                    strtoupper(
                        substr(
                            $customer->name,
                            0,
                            1
                        )
                    )
                }}

            </div>


            <h2
                class="mt-4
                       text-xl
                       font-bold
                       text-gray-900"
            >

                {{ $customer->name }}

            </h2>


            <p
                class="mt-1
                       text-sm
                       text-gray-500"
            >

                Customer #{{ $customer->id }}

            </p>

        </div>


        <div
            class="border-t
                   border-gray-200
                   divide-y
                   divide-gray-100"
        >

            {{-- TELEPON --}}

            <div class="px-6 py-4">

                <p
                    class="text-xs
                           text-gray-500
                           mb-1"
                >
                    Nomor Telepon
                </p>

                <p
                    class="text-sm
                           font-medium
                           text-gray-900"
                >

                    {{ $customer->phone ?: '-' }}

                </p>

            </div>


            {{-- ALAMAT --}}

            <div class="px-6 py-4">

                <p
                    class="text-xs
                           text-gray-500
                           mb-1"
                >
                    Alamat
                </p>

                <p
                    class="text-sm
                           font-medium
                           text-gray-900"
                >

                    {{ $customer->address ?: '-' }}

                </p>

            </div>


            {{-- KENDARAAN --}}

            <div class="px-6 py-4">

                <p
                    class="text-xs
                           text-gray-500
                           mb-1"
                >
                    Jumlah Kendaraan
                </p>

                <p
                    class="text-sm
                           font-medium
                           text-gray-900"
                >

                    {{ $customer->vehicles->count() }}
                    kendaraan

                </p>

            </div>

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- KENDARAAN --}}
    {{-- ================================================= --}}

    <div
        class="lg:col-span-2
               bg-white
               rounded-xl
               border
               border-gray-200
               overflow-hidden"
    >

        <div
            class="px-6 py-5
                   border-b
                   border-gray-200"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Kendaraan Customer
            </h2>

            <p
                class="text-sm
                       text-gray-500
                       mt-1"
            >
                Kendaraan yang terdaftar atas customer ini.
            </p>

        </div>


        @if($customer->vehicles->count() > 0)

            <div
                class="divide-y
                       divide-gray-100"
            >

                @foreach($customer->vehicles as $vehicle)

                    <div
                        class="px-6 py-5
                               hover:bg-gray-50
                               transition"
                    >

                        <div
                            class="flex items-start
                                   justify-between
                                   gap-4"
                        >

                            <div
                                class="flex items-center
                                       gap-4"
                            >

                                <div
                                    class="w-12 h-12
                                           rounded-lg
                                           bg-gray-100
                                           flex items-center
                                           justify-center
                                           text-xl"
                                >
                                    🏍️
                                </div>


                                <div>

                                    <p
                                        class="font-semibold
                                               text-gray-900"
                                    >

                                        {{
                                            $vehicle->plate_number
                                                ?? '-'
                                        }}

                                    </p>


                                    <p
                                        class="text-sm
                                               text-gray-500
                                               mt-1"
                                    >

                                        {{
                                            trim(
                                                ($vehicle->brand ?? '')
                                                . ' '
                                                . ($vehicle->model ?? '')
                                            )
                                            ?: 'Motor'
                                        }}

                                    </p>

                                </div>

                            </div>


                            <span
                                class="px-3 py-1
                                       rounded-full
                                       bg-gray-100
                                       text-gray-600
                                       text-xs
                                       font-medium"
                            >

                                Kendaraan

                            </span>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div
                class="px-6 py-16
                       text-center"
            >

                <div
                    class="text-5xl
                           mb-4"
                >
                    🏍️
                </div>


                <p
                    class="font-semibold
                           text-gray-700"
                >
                    Belum ada kendaraan
                </p>


                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Customer ini belum memiliki
                    kendaraan yang terdaftar.
                </p>

            </div>

        @endif

    </div>

</div>



{{-- ================================================= --}}
{{-- METADATA --}}
{{-- ================================================= --}}

<div
    class="mt-6
           bg-white
           rounded-xl
           border border-gray-200
           px-6 py-5"
>

    <h2
        class="font-semibold
               text-gray-900
               mb-4"
    >
        Informasi Sistem
    </h2>


    <div
        class="grid grid-cols-1
               sm:grid-cols-2
               gap-4"
    >

        <div>

            <p
                class="text-xs
                       text-gray-500"
            >
                Customer ID
            </p>

            <p
                class="mt-1
                       text-sm
                       font-medium
                       text-gray-900"
            >

                #{{ $customer->id }}

            </p>

        </div>


        <div>

            <p
                class="text-xs
                       text-gray-500"
            >
                Terdaftar
            </p>

            <p
                class="mt-1
                       text-sm
                       font-medium
                       text-gray-900"
            >

                {{
                    $customer->created_at
                        ? $customer->created_at->format(
                            'd M Y H:i'
                        )
                        : '-'
                }}

            </p>

        </div>


        <div>

            <p
                class="text-xs
                       text-gray-500"
            >
                Terakhir Diperbarui
            </p>

            <p
                class="mt-1
                       text-sm
                       font-medium
                       text-gray-900"
            >

                {{
                    $customer->updated_at
                        ? $customer->updated_at->format(
                            'd M Y H:i'
                        )
                        : '-'
                }}

            </p>

        </div>

    </div>

</div>

@endsection