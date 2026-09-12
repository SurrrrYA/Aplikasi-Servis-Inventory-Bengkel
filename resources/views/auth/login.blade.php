<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login Admin - Bengkel Sempoeloer</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">

            {{-- Header --}}
            <div class="text-center mb-8">

                <div class="flex justify-center mb-5">
                    <img
                        src="{{ asset('images/logo_sempoeloer.png') }}"
                        alt="Logo Bengkel Sempoeloer"
                        class="h-20 w-auto object-contain"
                    >
                </div>

                <h1 class="text-2xl font-semibold text-gray-900">
                    Login Admin
                </h1>

                <p class="text-sm text-gray-500 mt-2">
                    Masuk untuk mengelola sistem bengkel
                </p>

            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error --}}
            @if($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Login Form --}}
            <form
                method="POST"
                action="{{ route('login') }}"
                class="space-y-5"
            >

                @csrf

                {{-- Email --}}
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Masukkan email"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                               focus:border-gray-500 focus:ring-1 focus:ring-gray-500
                               outline-none transition"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                               focus:border-gray-500 focus:ring-1 focus:ring-gray-500
                               outline-none transition"
                    >
                </div>

                {{-- Button --}}
                <button
                    type="submit"
                    class="w-full rounded-lg bg-gray-900 px-4 py-2.5
                           text-sm font-medium text-white
                           hover:bg-gray-800
                           focus:outline-none focus:ring-2 focus:ring-gray-400
                           transition"
                >
                    Masuk
                </button>

            </form>

        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-500 mt-5">
            Bengkel Sempoeloer
        </p>

    </div>

</body>

</html>