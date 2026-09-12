<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    {{-- CSRF TOKEN UNTUK REQUEST JAVASCRIPT / AJAX --}}
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Admin Dashboard')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="bg-gray-100 text-gray-800">

    <div class="min-h-screen flex">


        {{-- ================================================= --}}
        {{-- SIDEBAR --}}
        {{-- ================================================= --}}

        <aside
            class="w-64 bg-white border-r border-gray-200
                   flex flex-col fixed inset-y-0 left-0"
        >

            {{-- LOGO --}}

            <div
                class="h-20 px-6 flex items-center
                       border-b border-gray-200"
            >

                <div>

                    <h1 class="text-xl font-bold text-gray-900">
                        SEMPOELOER
                    </h1>

                    <p class="text-xs text-gray-500">
                        Bengkel Motor
                    </p>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- MENU --}}
            {{-- ================================================= --}}

            <nav
                class="flex-1 px-4 py-5 space-y-1 overflow-y-auto"
            >

                {{-- DASHBOARD --}}

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.dashboard')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>📊</span>

                    <span class="text-sm font-medium">
                        Dashboard
                    </span>

                </a>


                {{-- TRANSAKSI --}}

                <a
                    href="{{ route('admin.transactions.index') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.transactions.*')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>💰</span>

                    <span class="text-sm font-medium">
                        Transaksi
                    </span>

                </a>


                {{-- CUSTOMER --}}

                <a
                    href="{{ route('admin.customers.index') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.customers.*')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>👥</span>

                    <span class="text-sm font-medium">
                        Customer
                    </span>

                </a>


                {{-- KENDARAAN --}}

                <a
                    href="#"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg text-gray-600
                           hover:bg-gray-100"
                >

                    <span>🏍️</span>

                    <span class="text-sm font-medium">
                        Kendaraan
                    </span>

                </a>


                {{-- INVENTORY --}}

                <a
                    href="{{ route('admin.products.index') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.products.*')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>📦</span>

                    <span class="text-sm font-medium">
                        Inventory
                    </span>

                </a>


                {{-- JASA --}}

                <a
                    href="{{ route('admin.services.index') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.services.*')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>🔧</span>

                    <span class="text-sm font-medium">
                        Jasa
                    </span>

                </a>


                {{-- PROJECT SERVICE --}}

                <a
                    href="{{ route('admin.projects.index') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.projects.*')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>🛠️</span>

                    <span class="text-sm font-medium">
                        Project Service
                    </span>

                </a>


                {{-- ================================================= --}}
                {{-- LAPORAN --}}
                {{-- ================================================= --}}

                <div class="pt-5 pb-2">

                    <p
                        class="px-4 text-xs font-semibold
                               uppercase tracking-wider
                               text-gray-400"
                    >
                        Laporan
                    </p>

                </div>


                {{-- LAPORAN PENJUALAN --}}

                <a
                    href="{{ route('admin.reports.sales') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.reports.sales')
                                ? 'bg-gray-100 text-gray-900 font-semibold'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>📈</span>

                    <span class="text-sm font-medium">
                        Laporan Penjualan
                    </span>

                </a>


                {{-- LAPORAN STOK --}}

                <a
                    href="{{ route('admin.reports.stock') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.reports.stock')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>📋</span>

                    <span class="text-sm font-medium">
                        Laporan Stok
                    </span>

                </a>


                {{-- ================================================= --}}
                {{-- PENGATURAN --}}
                {{-- ================================================= --}}

                <div class="pt-5 pb-2">

                    <p
                        class="px-4 text-xs font-semibold
                               uppercase tracking-wider
                               text-gray-400"
                    >
                        Pengaturan
                    </p>

                </div>


                {{-- MANAJEMEN USER --}}

                <a
                    href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.users.*')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>👤</span>

                    <span class="text-sm font-medium">
                        Manajemen User
                    </span>

                </a>


                {{-- PENGATURAN STRUK --}}

                <a
                    href="{{ route('admin.receipt-settings.edit') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.receipt-settings.*')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>🧾</span>

                    <span class="text-sm font-medium">
                        Pengaturan Struk
                    </span>

                </a>


                {{-- ACTIVITY LOG --}}

                <a
                    href="{{ route('admin.activity-logs.index') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-lg
                           {{ request()->routeIs('admin.activity-logs.*')
                                ? 'bg-gray-900 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}"
                >

                    <span>📋</span>

                    <span class="text-sm font-medium">
                        Activity Log
                    </span>

                </a>

            </nav>


            {{-- ================================================= --}}
            {{-- FOOTER SIDEBAR --}}
            {{-- ================================================= --}}

            <div class="border-t border-gray-200 p-4">

                @auth

                    <div class="flex items-center gap-3">

                        {{-- AVATAR --}}

                        <div
                            class="w-10 h-10 rounded-full
                                   bg-gray-900 text-white
                                   flex items-center justify-center
                                   font-semibold"
                        >
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>


                        {{-- USER INFO --}}

                        <div class="flex-1 min-w-0">

                            <p
                                class="text-sm font-semibold text-gray-900
                                       truncate"
                            >
                                {{ auth()->user()->name }}
                            </p>

                            <p
                                class="text-xs text-gray-500
                                       capitalize"
                            >
                                {{ auth()->user()->role }}
                            </p>

                        </div>

                    </div>


                    {{-- LOGOUT --}}

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="mt-3"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="w-full flex items-center justify-center
                                   gap-2 px-4 py-2
                                   rounded-lg
                                   border border-gray-300
                                   text-sm font-medium
                                   text-gray-700
                                   hover:bg-gray-50
                                   transition"
                        >

                            <span>↪</span>

                            <span>
                                Logout
                            </span>

                        </button>

                    </form>

                @endauth

            </div>

        </aside>


        {{-- ================================================= --}}
        {{-- MAIN --}}
        {{-- ================================================= --}}

        <main class="ml-64 flex-1 min-h-screen">


            {{-- ================================================= --}}
            {{-- TOPBAR --}}
            {{-- ================================================= --}}

            <header
                class="h-20 bg-white border-b
                       border-gray-200 px-8
                       flex items-center justify-between"
            >

                <div>

                    <h2 class="text-lg font-semibold text-gray-900">

                        @yield(
                            'page-title',
                            'Dashboard'
                        )

                    </h2>

                    <p class="text-sm text-gray-500">
                        Kelola operasional Bengkel Sempoeloer
                    </p>

                </div>


                {{-- USER LOGIN DI TOPBAR --}}

                @auth

                    <div class="flex items-center gap-3">

                        <div class="text-right">

                            <p class="text-sm font-medium text-gray-900">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-gray-500 capitalize">
                                {{ auth()->user()->role }}
                            </p>

                        </div>


                        <div
                            class="w-9 h-9 rounded-full
                                   bg-gray-900 text-white
                                   flex items-center justify-center
                                   text-sm font-semibold"
                        >

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                        </div>

                    </div>

                @endauth

            </header>


            {{-- ================================================= --}}
            {{-- CONTENT --}}
            {{-- ================================================= --}}

            <section class="p-8">

                @yield('content')

            </section>

        </main>

    </div>


    {{-- ================================================= --}}
    {{-- ALPINE JS --}}
    {{-- ================================================= --}}

    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>


</body>

</html>