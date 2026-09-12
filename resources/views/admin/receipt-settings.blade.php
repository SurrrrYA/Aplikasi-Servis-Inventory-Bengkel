@extends('layouts.admin')


@section('title', 'Pengaturan Struk')


@section('page-title', 'Pengaturan Struk')


@section('content')


{{-- ================================================= --}}
{{-- HEADER --}}
{{-- ================================================= --}}

<div class="mb-8">

    <h1
        class="text-2xl font-bold text-gray-900"
    >
        Pengaturan Struk
    </h1>

    <p
        class="mt-1 text-sm text-gray-500"
    >
        Atur informasi dan tampilan struk thermal
        yang digunakan oleh aplikasi kasir.
    </p>

</div>



{{-- ================================================= --}}
{{-- SUCCESS MESSAGE --}}
{{-- ================================================= --}}

@if(session('success'))

    <div
        class="mb-6 rounded-xl
               border border-green-200
               bg-green-50
               px-5 py-4"
    >

        <div class="flex items-center gap-3">

            <div class="text-xl">
                ✓
            </div>

            <p
                class="text-sm font-medium
                       text-green-700"
            >
                {{ session('success') }}
            </p>

        </div>

    </div>

@endif



{{-- ================================================= --}}
{{-- VALIDATION ERROR --}}
{{-- ================================================= --}}

@if($errors->any())

    <div
        class="mb-6 rounded-xl
               border border-red-200
               bg-red-50
               px-5 py-4"
    >

        <p
            class="font-semibold
                   text-red-700"
        >
            Ada data yang perlu diperbaiki.
        </p>


        <ul
            class="mt-2
                   list-disc
                   list-inside
                   text-sm
                   text-red-600"
        >

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif



<form
    method="POST"
    action="{{ route('admin.receipt-settings.update') }}"
>

    @csrf

    @method('PUT')


    <div
        class="grid grid-cols-1
               lg:grid-cols-3
               gap-6"
    >


        {{-- ================================================= --}}
        {{-- INFORMASI BENGKEL --}}
        {{-- ================================================= --}}

        <div
            class="lg:col-span-2
                   bg-white
                   rounded-xl
                   border border-gray-200"
        >

            <div
                class="px-6 py-5
                       border-b border-gray-200"
            >

                <h2
                    class="font-semibold
                           text-gray-900"
                >
                    Informasi Bengkel
                </h2>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Informasi yang ditampilkan pada
                    bagian atas struk.
                </p>

            </div>


            <div class="p-6 space-y-5">


                {{-- NAMA BENGKEL --}}

                <div>

                    <label
                        for="business_name"
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Nama Bengkel
                    </label>


                    <input
                        type="text"
                        id="business_name"
                        name="business_name"
                        value="{{ old(
                            'business_name',
                            $setting->business_name
                        ) }}"
                        required
                        maxlength="255"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-3
                               text-sm
                               focus:border-gray-900
                               focus:ring-1
                               focus:ring-gray-900"
                    >

                </div>



                {{-- SUBTITLE --}}

                <div>

                    <label
                        for="subtitle"
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Keterangan / Subtitle
                    </label>


                    <input
                        type="text"
                        id="subtitle"
                        name="subtitle"
                        value="{{ old(
                            'subtitle',
                            $setting->subtitle
                        ) }}"
                        maxlength="255"
                        placeholder="Servis & Sparepart Motor"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-3
                               text-sm
                               focus:border-gray-900
                               focus:ring-1
                               focus:ring-gray-900"
                    >

                </div>



                {{-- ALAMAT --}}

                <div>

                    <label
                        for="address"
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Alamat
                    </label>


                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        maxlength="1000"
                        placeholder="Alamat bengkel"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-3
                               text-sm
                               resize-none
                               focus:border-gray-900
                               focus:ring-1
                               focus:ring-gray-900"
                    >{{ old(
                        'address',
                        $setting->address
                    ) }}</textarea>

                </div>



                {{-- TELEPON --}}

                <div>

                    <label
                        for="phone"
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Nomor Telepon
                    </label>


                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old(
                            'phone',
                            $setting->phone
                        ) }}"
                        maxlength="50"
                        placeholder="08xxxxxxxxxx"
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-3
                               text-sm
                               focus:border-gray-900
                               focus:ring-1
                               focus:ring-gray-900"
                    >

                </div>



                {{-- FOOTER --}}

                <div>

                    <label
                        for="footer"
                        class="block
                               text-sm
                               font-medium
                               text-gray-700
                               mb-2"
                    >
                        Footer Struk
                    </label>


                    <textarea
                        id="footer"
                        name="footer"
                        rows="3"
                        maxlength="1000"
                        placeholder="Terima kasih telah berkunjung."
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-4 py-3
                               text-sm
                               resize-none
                               focus:border-gray-900
                               focus:ring-1
                               focus:ring-gray-900"
                    >{{ old(
                        'footer',
                        $setting->footer
                    ) }}</textarea>

                </div>

            </div>

        </div>



        {{-- ================================================= --}}
        {{-- ELEMEN STRUK --}}
        {{-- ================================================= --}}

        <div
            class="bg-white
                   rounded-xl
                   border border-gray-200"
        >

            <div
                class="px-6 py-5
                       border-b border-gray-200"
            >

                <h2
                    class="font-semibold
                           text-gray-900"
                >
                    Elemen Struk
                </h2>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Pilih informasi yang ingin ditampilkan.
                </p>

            </div>


            <div class="p-6 space-y-5">


                {{-- KASIR --}}

                <label
                    class="flex items-center
                           justify-between
                           gap-4
                           cursor-pointer"
                >

                    <div>

                        <p
                            class="text-sm
                                   font-medium
                                   text-gray-900"
                        >
                            Kasir
                        </p>

                        <p
                            class="text-xs
                                   text-gray-500
                                   mt-1"
                        >
                            Nama kasir pada transaksi.
                        </p>

                    </div>


                    <input
                        type="hidden"
                        name="show_cashier"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        name="show_cashier"
                        value="1"
                        {{ old(
                            'show_cashier',
                            $setting->show_cashier
                        ) ? 'checked' : '' }}
                        class="w-5 h-5
                               rounded
                               border-gray-300
                               text-gray-900
                               focus:ring-gray-900"
                    >

                </label>



                {{-- CUSTOMER --}}

                <label
                    class="flex items-center
                           justify-between
                           gap-4
                           cursor-pointer"
                >

                    <div>

                        <p
                            class="text-sm
                                   font-medium
                                   text-gray-900"
                        >
                            Customer
                        </p>

                        <p
                            class="text-xs
                                   text-gray-500
                                   mt-1"
                        >
                            Nama customer.
                        </p>

                    </div>


                    <input
                        type="hidden"
                        name="show_customer"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        name="show_customer"
                        value="1"
                        {{ old(
                            'show_customer',
                            $setting->show_customer
                        ) ? 'checked' : '' }}
                        class="w-5 h-5
                               rounded
                               border-gray-300
                               text-gray-900
                               focus:ring-gray-900"
                    >

                </label>



                {{-- TELEPON CUSTOMER --}}

                <label
                    class="flex items-center
                           justify-between
                           gap-4
                           cursor-pointer"
                >

                    <div>

                        <p
                            class="text-sm
                                   font-medium
                                   text-gray-900"
                        >
                            Telepon Customer
                        </p>

                        <p
                            class="text-xs
                                   text-gray-500
                                   mt-1"
                        >
                            Nomor telepon customer.
                        </p>

                    </div>


                    <input
                        type="hidden"
                        name="show_customer_phone"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        name="show_customer_phone"
                        value="1"
                        {{ old(
                            'show_customer_phone',
                            $setting->show_customer_phone
                        ) ? 'checked' : '' }}
                        class="w-5 h-5
                               rounded
                               border-gray-300
                               text-gray-900
                               focus:ring-gray-900"
                    >

                </label>



                {{-- KENDARAAN --}}

                <label
                    class="flex items-center
                           justify-between
                           gap-4
                           cursor-pointer"
                >

                    <div>

                        <p
                            class="text-sm
                                   font-medium
                                   text-gray-900"
                        >
                            Kendaraan
                        </p>

                        <p
                            class="text-xs
                                   text-gray-500
                                   mt-1"
                        >
                            Nomor polisi dan motor.
                        </p>

                    </div>


                    <input
                        type="hidden"
                        name="show_vehicle"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        name="show_vehicle"
                        value="1"
                        {{ old(
                            'show_vehicle',
                            $setting->show_vehicle
                        ) ? 'checked' : '' }}
                        class="w-5 h-5
                               rounded
                               border-gray-300
                               text-gray-900
                               focus:ring-gray-900"
                    >

                </label>



                {{-- CATATAN --}}

                <label
                    class="flex items-center
                           justify-between
                           gap-4
                           cursor-pointer"
                >

                    <div>

                        <p
                            class="text-sm
                                   font-medium
                                   text-gray-900"
                        >
                            Catatan
                        </p>

                        <p
                            class="text-xs
                                   text-gray-500
                                   mt-1"
                        >
                            Catatan transaksi.
                        </p>

                    </div>


                    <input
                        type="hidden"
                        name="show_notes"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        name="show_notes"
                        value="1"
                        {{ old(
                            'show_notes',
                            $setting->show_notes
                        ) ? 'checked' : '' }}
                        class="w-5 h-5
                               rounded
                               border-gray-300
                               text-gray-900
                               focus:ring-gray-900"
                    >

                </label>

            </div>

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- PREVIEW --}}
    {{-- ================================================= --}}

    <div
        class="mt-6
               bg-white
               rounded-xl
               border border-gray-200"
    >

        <div
            class="px-6 py-5
                   border-b border-gray-200"
        >

            <h2
                class="font-semibold
                       text-gray-900"
            >
                Contoh Struk
            </h2>

            <p
                class="text-sm
                       text-gray-500
                       mt-1"
            >
                Gambaran sederhana format struk thermal.
            </p>

        </div>


        <div
            class="p-6
                   flex
                   justify-center"
        >

            <div
                class="w-full
                       max-w-sm
                       bg-gray-50
                       border border-gray-300
                       rounded-lg
                       p-6
                       font-mono
                       text-sm"
            >

                <div
                    class="text-center"
                >

                    <p
                        class="font-bold
                               text-base"
                    >
                        {{ $setting->business_name }}
                    </p>


                    @if($setting->subtitle)

                        <p>
                            {{ $setting->subtitle }}
                        </p>

                    @endif


                    @if($setting->address)

                        <p>
                            {{ $setting->address }}
                        </p>

                    @endif


                    @if($setting->phone)

                        <p>
                            {{ $setting->phone }}
                        </p>

                    @endif

                </div>


                <div class="my-3">
                    --------------------------------
                </div>


                <p>
                    No : TRX-20260831-000001
                </p>


                @if($setting->show_cashier)

                    <p>
                        Kasir : Admin
                    </p>

                @endif


                @if($setting->show_customer)

                    <p>
                        Customer : Surya
                    </p>

                @endif


                @if($setting->show_customer_phone)

                    <p>
                        Telepon : 08xxxxxxxxxx
                    </p>

                @endif


                @if($setting->show_vehicle)

                    <p>
                        Motor : H 7890 HG
                    </p>

                    <p>
                        Honda Vario
                    </p>

                @endif


                <div class="my-3">
                    --------------------------------
                </div>


                <p>
                    Ganti Oli
                </p>

                <div
                    class="flex
                           justify-between"
                >

                    <span>
                        1 x Rp 15.000
                    </span>

                    <span>
                        Rp 15.000
                    </span>

                </div>


                <div
                    class="flex
                           justify-between
                           font-bold
                           mt-3"
                >

                    <span>
                        TOTAL
                    </span>

                    <span>
                        Rp 15.000
                    </span>

                </div>


                <div class="my-3">
                    --------------------------------
                </div>


                @if($setting->show_notes)

                    <p>
                        Catatan:
                    </p>

                    <p>
                        Contoh catatan transaksi
                    </p>

                    <div class="my-3">
                        --------------------------------
                    </div>

                @endif


                <div
                    class="text-center"
                >

                    @if($setting->footer)

                        <p>
                            {{ $setting->footer }}
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- SAVE --}}
    {{-- ================================================= --}}

    <div
        class="mt-6
               flex
               justify-end"
    >

        <button
            type="submit"
            class="px-6 py-3
                   rounded-lg
                   bg-gray-900
                   text-white
                   text-sm
                   font-medium
                   hover:bg-gray-800
                   transition"
        >
            Simpan Pengaturan
        </button>

    </div>

</form>


@endsection