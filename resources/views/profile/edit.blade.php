@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')

    <div class="p-6">

        <div class="max-w-[1440px] mx-auto">

            {{-- Page Header --}}
            <div class="flex justify-between items-start mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border bg-slate-100 text-slate-700 border-slate-200">
                            <span class="material-symbols-outlined text-sm">settings</span>
                            Account Settings
                        </span>
                    </div>
                    <h2 class="font-display-lg text-display-lg text-on-surface">
                        Profile Settings
                    </h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                        Kelola informasi akun, keamanan, dan preferensi kamu.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">

                {{-- Left Column: Profile Card --}}
                <div class="col-span-12 lg:col-span-4">

                    <x-ui.card class="overflow-hidden">

                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-6 flex flex-col items-center">

                            {{-- Avatar --}}
                            <div
                                class="w-20 h-20 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold mb-4 ring-4 ring-blue-500/20">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>

                            <h3 class="text-white font-semibold text-lg">
                                {{ Auth::user()->name }}
                            </h3>

                            <p class="text-slate-400 text-sm mt-1">
                                {{ Auth::user()->email }}
                            </p>

                            @php
                                $role = Auth::user()->roles->first()?->name;
                                $roleLabels = [
                                    'head-analytics' => ['label' => 'Head of Data Analytics', 'color' => 'bg-purple-500/20 text-purple-300 border-purple-500/30'],
                                    'financial-controller' => ['label' => 'Financial Controller', 'color' => 'bg-green-500/20 text-green-300 border-green-500/30'],
                                    'logistics-officer' => ['label' => 'Logistics Officer', 'color' => 'bg-orange-500/20 text-orange-300 border-orange-500/30'],
                                    'procurement-director' => ['label' => 'Procurement Director', 'color' => 'bg-blue-500/20 text-blue-300 border-blue-500/30'],
                                    'key-account-manager' => ['label' => 'Key Account Manager', 'color' => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30'],
                                ];
                                $rl = $roleLabels[$role] ?? ['label' => 'User', 'color' => 'bg-slate-500/20 text-slate-300 border-slate-500/30'];
                            @endphp

                            <span class="inline-flex items-center px-3 py-1 mt-3 rounded-full text-xs font-bold border {{ $rl['color'] }}">
                                {{ $rl['label'] }}
                            </span>

                        </div>

                        <div class="p-5 space-y-4">

                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                    <span class="material-symbols-outlined text-base">mail</span>
                                </div>
                                <div>
                                    <p class="text-on-surface-variant text-xs">Email</p>
                                    <p class="text-on-surface font-medium">{{ Auth::user()->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                                    <span class="material-symbols-outlined text-base">verified_user</span>
                                </div>
                                <div>
                                    <p class="text-on-surface-variant text-xs">Status</p>
                                    <p class="text-green-600 font-medium">Active</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                                    <span class="material-symbols-outlined text-base">calendar_today</span>
                                </div>
                                <div>
                                    <p class="text-on-surface-variant text-xs">Member Since</p>
                                    <p class="text-on-surface font-medium">{{ Auth::user()->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                        </div>

                    </x-ui.card>

                </div>

                {{-- Right Column: Forms --}}
                <div class="col-span-12 lg:col-span-8 space-y-6">

                    {{-- Update Profile Information --}}
                    <x-ui.card class="overflow-hidden">

                        <div class="dashboard-card-header flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <span class="material-symbols-outlined text-base">person</span>
                            </div>
                            <div>
                                <h3 class="dashboard-title">Profile Information</h3>
                                <p class="dashboard-subtitle">Update nama dan email akun kamu.</p>
                            </div>
                        </div>

                        <div class="dashboard-card-body">

                            <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                                @csrf
                                @method('patch')

                                <div>
                                    <label for="name" class="block text-sm font-semibold text-on-surface mb-1.5">
                                        Nama Lengkap
                                    </label>
                                    <input id="name" name="name" type="text"
                                        class="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-white text-sm text-on-surface
                                               focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                                        value="{{ old('name', $user->name) }}" required autofocus />

                                    @error('name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-on-surface mb-1.5">
                                        Email Address
                                    </label>
                                    <input id="email" name="email" type="email"
                                        class="w-full px-4 py-2.5 rounded-lg border border-outline-variant bg-white text-sm text-on-surface
                                               focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                                        value="{{ old('email', $user->email) }}" required />

                                    @error('email')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center gap-4 pt-2">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-secondary text-white text-sm font-semibold
                                               hover:opacity-90 transition-all">
                                        <span class="material-symbols-outlined text-base">save</span>
                                        Simpan Perubahan
                                    </button>

                                    @if (session('status') === 'profile-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition
                                            x-init="setTimeout(() => show = false, 3000)"
                                            class="text-sm text-green-600 font-medium flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            Tersimpan!
                                        </p>
                                    @endif
                                </div>

                            </form>

                        </div>

                    </x-ui.card>

                    {{-- Update Password --}}
                    <x-ui.card class="overflow-hidden">

                        <div class="dashboard-card-header flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                                <span class="material-symbols-outlined text-base">lock</span>
                            </div>
                            <div>
                                <h3 class="dashboard-title">Update Password</h3>
                                <p class="dashboard-subtitle">Pastikan akun kamu menggunakan password yang kuat.</p>
                            </div>
                        </div>

                        <div class="dashboard-card-body">

                            <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                                @csrf
                                @method('put')

                                <div>
                                    <label for="update_password_current_password"
                                        class="block text-sm font-semibold text-on-surface mb-1.5">
                                        Password Saat Ini
                                    </label>
                                    <div x-data="{ show: false }" class="relative">
                                        <input id="update_password_current_password" name="current_password"
                                            :type="show ? 'text' : 'password'"
                                            class="w-full px-4 py-2.5 pr-11 rounded-lg border border-outline-variant bg-white text-sm text-on-surface
                                                   focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                                            autocomplete="current-password" />
                                        <button type="button" @click="show = !show" tabindex="-1"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors">
                                            <span class="material-symbols-outlined text-lg" x-text="show ? 'visibility' : 'visibility_off'"></span>
                                        </button>
                                    </div>

                                    @error('current_password', 'updatePassword')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                    <div>
                                        <label for="update_password_password"
                                            class="block text-sm font-semibold text-on-surface mb-1.5">
                                            Password Baru
                                        </label>
                                        <div x-data="{ show: false }" class="relative">
                                            <input id="update_password_password" name="password"
                                                :type="show ? 'text' : 'password'"
                                                class="w-full px-4 py-2.5 pr-11 rounded-lg border border-outline-variant bg-white text-sm text-on-surface
                                                       focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                                                autocomplete="new-password" />
                                            <button type="button" @click="show = !show" tabindex="-1"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors">
                                                <span class="material-symbols-outlined text-lg" x-text="show ? 'visibility' : 'visibility_off'"></span>
                                            </button>
                                        </div>

                                        @error('password', 'updatePassword')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="update_password_password_confirmation"
                                            class="block text-sm font-semibold text-on-surface mb-1.5">
                                            Konfirmasi Password
                                        </label>
                                        <div x-data="{ show: false }" class="relative">
                                            <input id="update_password_password_confirmation" name="password_confirmation"
                                                :type="show ? 'text' : 'password'"
                                                class="w-full px-4 py-2.5 pr-11 rounded-lg border border-outline-variant bg-white text-sm text-on-surface
                                                       focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                                                autocomplete="new-password" />
                                            <button type="button" @click="show = !show" tabindex="-1"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors">
                                                <span class="material-symbols-outlined text-lg" x-text="show ? 'visibility' : 'visibility_off'"></span>
                                            </button>
                                        </div>

                                        @error('password_confirmation', 'updatePassword')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>

                                <div class="flex items-center gap-4 pt-2">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-amber-500 text-white text-sm font-semibold
                                               hover:bg-amber-600 transition-all">
                                        <span class="material-symbols-outlined text-base">lock_reset</span>
                                        Update Password
                                    </button>

                                    @if (session('status') === 'password-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition
                                            x-init="setTimeout(() => show = false, 3000)"
                                            class="text-sm text-green-600 font-medium flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            Password updated!
                                        </p>
                                    @endif
                                </div>

                            </form>

                        </div>

                    </x-ui.card>

                    {{-- Danger Zone --}}
                    <x-ui.card class="overflow-hidden border-red-200">

                        <div class="dashboard-card-header flex items-center gap-3 bg-red-50/50">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                                <span class="material-symbols-outlined text-base">warning</span>
                            </div>
                            <div>
                                <h3 class="dashboard-title text-red-700">Danger Zone</h3>
                                <p class="dashboard-subtitle">Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                        </div>

                        <div class="dashboard-card-body">

                            <p class="text-sm text-on-surface-variant mb-4">
                                Setelah akun dihapus, semua data dan resources akan dihapus secara permanen.
                                Pastikan kamu sudah menyimpan data yang diperlukan sebelum menghapus akun.
                            </p>

                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-red-600 text-white text-sm font-semibold
                                       hover:bg-red-700 transition-all">
                                <span class="material-symbols-outlined text-base">delete_forever</span>
                                Hapus Akun
                            </button>

                            {{-- Delete Modal --}}
                            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                    @csrf
                                    @method('delete')

                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                            <span class="material-symbols-outlined">warning</span>
                                        </div>
                                        <h2 class="text-lg font-bold text-on-surface">
                                            Hapus Akun Permanen?
                                        </h2>
                                    </div>

                                    <p class="text-sm text-on-surface-variant mb-6">
                                        Semua data akan dihapus secara permanen dan tidak dapat dikembalikan.
                                        Masukkan password kamu untuk konfirmasi.
                                    </p>

                                    <div class="mb-6">
                                        <div x-data="{ show: false }" class="relative">
                                            <input id="password" name="password"
                                                :type="show ? 'text' : 'password'"
                                                class="w-full px-4 py-2.5 pr-11 rounded-lg border border-outline-variant bg-white text-sm text-on-surface
                                                       focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all"
                                                placeholder="Masukkan password..." />
                                            <button type="button" @click="show = !show" tabindex="-1"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors">
                                                <span class="material-symbols-outlined text-lg" x-text="show ? 'visibility' : 'visibility_off'"></span>
                                            </button>
                                        </div>

                                        @error('password', 'userDeletion')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" x-on:click="$dispatch('close')"
                                            class="px-4 py-2 rounded-lg border border-outline-variant text-sm font-medium text-on-surface-variant
                                                   hover:bg-gray-50 transition-all">
                                            Batal
                                        </button>

                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold
                                                   hover:bg-red-700 transition-all">
                                            <span class="material-symbols-outlined text-base">delete_forever</span>
                                            Hapus Akun
                                        </button>
                                    </div>
                                </form>
                            </x-modal>

                        </div>

                    </x-ui.card>

                </div>

            </div>

        </div>

    </div>

@endsection
