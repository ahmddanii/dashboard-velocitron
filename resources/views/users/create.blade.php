@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-xl">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-on-surface transition mb-4">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to Users
            </a>
            <h1 class="text-2xl font-bold text-on-surface" style="font-family: 'Syne', sans-serif">
                Add New User
            </h1>
            <p class="text-sm text-on-surface-variant mt-1">
                Buat akun dan assign role untuk pengguna baru.
            </p>
        </div>

        {{-- Form --}}
        <div class="dashboard-card overflow-hidden">
            <div class="dashboard-card-header">
                <h3 class="dashboard-title">User Details</h3>
                <p class="dashboard-subtitle">Semua field wajib diisi.</p>
            </div>

            <div class="dashboard-card-body space-y-5">

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                        <ul class="space-y-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">
                            Full Name
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Ahmad Dani"
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">
                            Email Address
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="e.g. ahmad@superstore.com"
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition">
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">
                            Role
                        </label>
                        <select name="role" required
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition">
                            <option value="" disabled selected>Pilih role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">
                            Password
                        </label>
                        <input type="password" name="password" required placeholder="Minimum 8 karakter"
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition">
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">
                            Confirm Password
                        </label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition">
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-secondary text-white text-sm font-semibold hover:opacity-90 transition">
                            <span class="material-symbols-outlined text-base">person_add</span>
                            Create User
                        </button>
                        <a href="{{ route('users.index') }}"
                            class="px-5 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection