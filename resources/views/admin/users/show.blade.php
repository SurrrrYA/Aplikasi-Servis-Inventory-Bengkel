@extends('layouts.admin')

@section('title', 'Detail User')

@section('page-title', 'Detail User')

@section('content')

<div class="max-w-3xl">

    {{-- HEADER --}}

    <div class="flex flex-col sm:flex-row
                sm:items-center sm:justify-between
                gap-4 mb-6">

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Detail User
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Informasi akun pengguna aplikasi.
            </p>

        </div>


        <div class="flex items-center gap-2">

            <a
                href="{{ route('admin.users.edit', $user) }}"
                class="px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm font-medium
                       hover:bg-gray-800"
            >
                Edit
            </a>

            <a
                href="{{ route('admin.users.index') }}"
                class="px-4 py-2.5
                       border border-gray-300
                       rounded-lg
                       text-sm font-medium
                       text-gray-700
                       hover:bg-gray-50"
            >
                Kembali
            </a>

        </div>

    </div>


    {{-- ALERT SUCCESS --}}

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


    {{-- ALERT ERROR --}}

    @if(session('error'))

        <div
            class="mb-6 px-4 py-3
                   rounded-lg
                   bg-gray-50
                   border border-gray-200
                   text-sm text-gray-700"
        >
            {{ session('error') }}
        </div>

    @endif


    {{-- DETAIL --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl
               overflow-hidden"
    >

        {{-- PROFILE --}}

        <div
            class="px-6 py-6
                   border-b border-gray-200"
        >

            <div class="flex items-center gap-4">

                <div
                    class="w-14 h-14
                           rounded-full
                           bg-gray-900
                           text-white
                           flex items-center
                           justify-center
                           text-xl
                           font-bold"
                >
                    {{ strtoupper(
                        substr($user->name, 0, 1)
                    ) }}
                </div>


                <div>

                    <h2
                        class="text-lg
                               font-semibold
                               text-gray-900"
                    >
                        {{ $user->name }}
                    </h2>

                    <p
                        class="text-sm
                               text-gray-500"
                    >
                        {{ $user->email }}
                    </p>

                </div>

            </div>

        </div>


        {{-- INFORMASI --}}

        <div class="p-6">

            <div
                class="grid grid-cols-1
                       sm:grid-cols-2
                       gap-6"
            >

                {{-- NAMA --}}

                <div>

                    <p
                        class="text-xs
                               font-medium
                               uppercase
                               tracking-wide
                               text-gray-400"
                    >
                        Nama
                    </p>

                    <p
                        class="mt-1
                               text-sm
                               font-medium
                               text-gray-900"
                    >
                        {{ $user->name }}
                    </p>

                </div>


                {{-- EMAIL --}}

                <div>

                    <p
                        class="text-xs
                               font-medium
                               uppercase
                               tracking-wide
                               text-gray-400"
                    >
                        Email
                    </p>

                    <p
                        class="mt-1
                               text-sm
                               font-medium
                               text-gray-900"
                    >
                        {{ $user->email }}
                    </p>

                </div>


                {{-- ROLE --}}

                <div>

                    <p
                        class="text-xs
                               font-medium
                               uppercase
                               tracking-wide
                               text-gray-400"
                    >
                        Role
                    </p>

                    <div class="mt-2">

                        @if($user->role === 'owner')

                            <span
                                class="inline-flex
                                       px-2.5 py-1
                                       rounded-full
                                       text-xs
                                       font-medium
                                       bg-gray-900
                                       text-white"
                            >
                                Owner
                            </span>

                        @elseif($user->role === 'admin')

                            <span
                                class="inline-flex
                                       px-2.5 py-1
                                       rounded-full
                                       text-xs
                                       font-medium
                                       bg-gray-100
                                       text-gray-700"
                            >
                                Admin
                            </span>

                        @else

                            <span
                                class="inline-flex
                                       px-2.5 py-1
                                       rounded-full
                                       text-xs
                                       font-medium
                                       bg-gray-50
                                       text-gray-600
                                       border
                                       border-gray-200"
                            >
                                Kasir
                            </span>

                        @endif

                    </div>

                </div>


                {{-- STATUS --}}

                <div>

                    <p
                        class="text-xs
                               font-medium
                               uppercase
                               tracking-wide
                               text-gray-400"
                    >
                        Status
                    </p>

                    <div class="mt-2">

                        @if($user->is_active)

                            <span
                                class="inline-flex
                                       px-2.5 py-1
                                       rounded-full
                                       text-xs
                                       font-medium
                                       bg-gray-900
                                       text-white"
                            >
                                Aktif
                            </span>

                        @else

                            <span
                                class="inline-flex
                                       px-2.5 py-1
                                       rounded-full
                                       text-xs
                                       font-medium
                                       bg-gray-100
                                       text-gray-500
                                       border
                                       border-gray-200"
                            >
                                Nonaktif
                            </span>

                        @endif

                    </div>

                </div>


                {{-- ID --}}

                <div>

                    <p
                        class="text-xs
                               font-medium
                               uppercase
                               tracking-wide
                               text-gray-400"
                    >
                        User ID
                    </p>

                    <p
                        class="mt-1
                               text-sm
                               font-medium
                               text-gray-900"
                    >
                        #{{ $user->id }}
                    </p>

                </div>


                {{-- DIBUAT --}}

                <div>

                    <p
                        class="text-xs
                               font-medium
                               uppercase
                               tracking-wide
                               text-gray-400"
                    >
                        Dibuat
                    </p>

                    <p
                        class="mt-1
                               text-sm
                               font-medium
                               text-gray-900"
                    >
                        {{ $user->created_at
                            ? $user->created_at->format('d M Y H:i')
                            : '-'
                        }}
                    </p>

                </div>


                {{-- DIPERBARUI --}}

                <div>

                    <p
                        class="text-xs
                               font-medium
                               uppercase
                               tracking-wide
                               text-gray-400"
                    >
                        Terakhir Diperbarui
                    </p>

                    <p
                        class="mt-1
                               text-sm
                               font-medium
                               text-gray-900"
                    >
                        {{ $user->updated_at
                            ? $user->updated_at->format('d M Y H:i')
                            : '-'
                        }}
                    </p>

                </div>

            </div>

        </div>


        {{-- AKTIF / NONAKTIF --}}

        @if(
            auth()->check() &&
            auth()->id() !== $user->id
        )

            <div
                class="px-6 py-5
                       border-t border-gray-200"
            >

                <div
                    class="flex flex-col
                           sm:flex-row
                           sm:items-center
                           sm:justify-between
                           gap-4"
                >

                    <div>

                        <p
                            class="text-sm
                                   font-medium
                                   text-gray-900"
                        >
                            Status Akun
                        </p>

                        <p
                            class="mt-1
                                   text-xs
                                   text-gray-500"
                        >
                            User nonaktif tidak dapat menggunakan akun
                            untuk login.
                        </p>

                    </div>


<form
    method="POST"
    action="{{ route('admin.users.toggle-status', $user) }}"
    onsubmit="return confirm(this.dataset.confirm)"
    data-confirm="{{ $user->is_active
        ? 'Yakin ingin menonaktifkan user ini?'
        : 'Yakin ingin mengaktifkan user ini?' }}"
>
    @csrf
    @method('PATCH')

    <button
        type="submit"
        class="px-4 py-2 rounded-lg text-sm font-medium
            {{ $user->is_active
                ? 'bg-yellow-500 hover:bg-yellow-600 text-white'
                : 'bg-green-600 hover:bg-green-700 text-white' }}"
    >
        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
    </button>
</form>

                </div>

            </div>

        @endif


        {{-- HAPUS --}}

        @if(
            auth()->check() &&
            auth()->id() !== $user->id
        )

            <div
                class="px-6 py-5
                       border-t border-gray-200"
            >

                <form
                    method="POST"
                    action="{{ route(
                        'admin.users.destroy',
                        $user
                    ) }}"
                    onsubmit="return confirm(
                        'Yakin ingin menghapus user ini?'
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
                               hover:bg-gray-50"
                    >
                        Hapus User
                    </button>

                </form>

            </div>

        @endif

    </div>

</div>

@endsection