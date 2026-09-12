<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Invoice {{ $project->code }}
    </title>

    <style>

        @page {
            margin: 35px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            line-height: 1.5;
        }

        .clearfix {
            clear: both;
        }

        /* =====================================================
           HEADER
        ====================================================== */

        .header {
            width: 100%;
            margin-bottom: 25px;
        }

        .business-info {
            float: left;
            width: 55%;
        }

        .business-logo {
    width: 70px;
    height: 70px;
    object-fit: contain;
    float: left;
    margin-right: 15px;
}

.business-text {
    margin-left: 85px;
    padding-top: 8px;
}

        .business-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .business-subtitle {
            color: #6b7280;
            font-size: 11px;
        }

        .invoice-info {
            float: right;
            width: 40%;
            text-align: right;
            padding-top: 5px;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .invoice-number {
            color: #6b7280;
            font-size: 11px;
        }

        .line {
            border-top: 1px solid #d1d5db;
            margin: 20px 0;
        }

        /* =====================================================
           PROJECT INFORMATION
        ====================================================== */

        .project-info {
            width: 100%;
            margin-bottom: 25px;
        }

        .project-info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .project-info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .label {
            width: 120px;
            color: #6b7280;
        }

        .value {
            font-weight: bold;
        }

        /* =====================================================
           SECTION
        ====================================================== */

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 8px;
        }

        /* =====================================================
           TABLE
        ====================================================== */

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.items thead th {
            background: #f3f4f6;
            border-top: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
            padding: 8px;
            font-size: 10px;
            text-align: left;
        }

        table.items tbody td {
            border-bottom: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 11px;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .empty {
            color: #9ca3af;
            font-style: italic;
            text-align: center;
            padding: 10px;
        }

        /* =====================================================
           SUMMARY
        ====================================================== */

        .summary {
            width: 100%;
            margin-top: 20px;
        }

        .summary-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 6px 8px;
        }

        .summary-label {
            color: #6b7280;
        }

        .summary-total {
            border-top: 2px solid #111827;
            font-size: 14px;
            font-weight: bold;
            padding-top: 10px !important;
        }

        /* =====================================================
           FOOTER
        ====================================================== */

        .footer {
            margin-top: 35px;
            padding-top: 12px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }

    </style>

</head>


<body>


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="header">

        <div class="business-info">

            <img
                src="{{ public_path('images/logo_sempoeloer.png') }}"
                class="business-logo"
            >

            <div class="business-text">

                <div class="business-name">
                    {{ config('app.name', 'BENGKEL') }}
                </div>

                <div class="business-subtitle">
                    Invoice Project
                </div>

            </div>

        </div>


        <div class="invoice-info">

            <div class="invoice-title">
                INVOICE
            </div>

            <div class="invoice-number">
                INV-{{ $project->code }}
            </div>

            <div class="invoice-number">
                {{ $project->created_at
                    ? $project->created_at->format('d/m/Y')
                    : date('d/m/Y')
                }}
            </div>

        </div>


        <div class="clearfix"></div>

    </div>


    <div class="line"></div>


    {{-- =====================================================
         PROJECT INFORMATION
    ====================================================== --}}

    <div class="project-info">

        <table class="project-info-table">

            <tr>

                <td class="label">
                    Project
                </td>

                <td class="value">
                    {{ $project->name }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Kode Project
                </td>

                <td>
                    {{ $project->code }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Customer
                </td>

                <td>
                    {{ $project->customer_name ?? '-' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Status
                </td>

                <td>

                    @switch($project->status)

                        @case('draft')
                            Draft
                            @break

                        @case('process')
                            Process
                            @break

                        @case('completed')
                            Completed
                            @break

                        @case('cancelled')
                            Cancelled
                            @break

                        @default
                            -

                    @endswitch

                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         BARANG / MATERIAL
    ====================================================== --}}

    <div class="section-title">
        Barang / Material
    </div>


    @php

        $products = $project->items
            ->filter(function ($item) {
                return $item->product_id && $item->product;
            });

    @endphp


    @if($products->count())

        <table class="items">

            <thead>

                <tr>

                    <th style="width: 45%;">
                        Nama Barang
                    </th>

                    <th
                        style="width: 10%;"
                        class="text-center"
                    >
                        Qty
                    </th>

                    <th
                        style="width: 20%;"
                        class="text-right"
                    >
                        Harga
                    </th>

                    <th
                        style="width: 25%;"
                        class="text-right"
                    >
                        Subtotal
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($products as $item)

                    <tr>

                        <td>
                            {{ $item->product->name }}
                        </td>

                        <td class="text-center">
                            {{ $item->quantity }}
                        </td>

                        <td class="text-right">
                            Rp
                            {{ number_format(
                                $item->price ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td class="text-right">
                            Rp
                            {{ number_format(
                                $item->subtotal ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <table class="items">

            <tbody>

                <tr>

                    <td class="empty">
                        Tidak ada barang/material.
                    </td>

                </tr>

            </tbody>

        </table>

    @endif


    {{-- =====================================================
         JASA
    ====================================================== --}}

    <div class="section-title">
        Jasa
    </div>


    @php

        $services = $project->items
            ->filter(function ($item) {
                return $item->service_id && $item->service;
            });

    @endphp


    @if($services->count())

        <table class="items">

            <thead>

                <tr>

                    <th style="width: 70%;">
                        Nama Jasa
                    </th>

                    <th
                        style="width: 30%;"
                        class="text-right"
                    >
                        Harga
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($services as $item)

                    <tr>

                        <td>
                            {{ $item->service->name }}
                        </td>

                        <td class="text-right">
                            Rp
                            {{ number_format(
                                $item->price ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <table class="items">

            <tbody>

                <tr>

                    <td class="empty">
                        Tidak ada jasa.
                    </td>

                </tr>

            </tbody>

        </table>

    @endif


    {{-- =====================================================
         BIAYA TAMBAHAN
    ====================================================== --}}

    <div class="section-title">
        Biaya Tambahan
    </div>


    @if($project->costs->count())

        <table class="items">

            <thead>

                <tr>

                    <th style="width: 70%;">
                        Nama Biaya
                    </th>

                    <th
                        style="width: 30%;"
                        class="text-right"
                    >
                        Jumlah
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($project->costs as $cost)

                    <tr>

                        <td>
                            {{ $cost->name }}
                        </td>

                        <td class="text-right">
                            Rp
                            {{ number_format(
                                $cost->amount ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <table class="items">

            <tbody>

                <tr>

                    <td class="empty">
                        Tidak ada biaya tambahan.
                    </td>

                </tr>

            </tbody>

        </table>

    @endif


    {{-- =====================================================
         RINGKASAN
    ====================================================== --}}

    @php

        $totalItems =
            $project->items->sum('subtotal');

        $totalCosts =
            $project->costs->sum('amount');

        $totalProject =
            $totalItems + $totalCosts;

    @endphp


    <div class="summary">

        <table class="summary-table">

            <tr>

                <td class="summary-label">
                    Total Item
                </td>

                <td class="text-right">
                    Rp
                    {{ number_format(
                        $totalItems,
                        0,
                        ',',
                        '.'
                    ) }}
                </td>

            </tr>


            <tr>

                <td class="summary-label">
                    Biaya Tambahan
                </td>

                <td class="text-right">
                    Rp
                    {{ number_format(
                        $totalCosts,
                        0,
                        ',',
                        '.'
                    ) }}
                </td>

            </tr>


            <tr>

                <td class="summary-total">
                    TOTAL PROJECT
                </td>

                <td
                    class="summary-total text-right"
                >
                    Rp
                    {{ number_format(
                        $totalProject,
                        0,
                        ',',
                        '.'
                    ) }}
                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="footer">

        Terima kasih telah menggunakan layanan kami.

    </div>


</body>

</html>