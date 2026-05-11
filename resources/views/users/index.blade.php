@extends('layouts.app')

@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6" x-data="{ showAddModal: false }">
            <div>
                <h1 class="text-2xl font-bold text-on-surface" style="font-family: 'Syne', sans-serif">
                    User Management
                </h1>
                <p class="text-sm text-on-surface-variant mt-1">
                    Kelola akses dan role pengguna VELOCITRON.
                </p>
            </div>
            <button @click="showAddModal = true"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-secondary text-white text-sm font-semibold hover:opacity-90 transition shadow-lg shadow-blue-900/20">
                <span class="material-symbols-outlined text-base">person_add</span>
                Add User
            </button>

            {{-- Add User Modal --}}
            <div x-show="showAddModal" 
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-cloak>
                
                <div class="bg-surface rounded-[32px] shadow-2xl max-w-lg w-full overflow-hidden border border-outline-variant" @click.away="showAddModal = false">
                    {{-- Modal Header --}}
                    <div class="px-8 py-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-inner">
                                <span class="material-symbols-outlined text-2xl">person_add</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-on-surface">Add New User</h3>
                                <p class="text-xs text-on-surface-variant">Buat akun akses sistem baru.</p>
                            </div>
                        </div>
                        <button @click="showAddModal = false" class="p-2 hover:bg-surface-container rounded-full transition">
                            <span class="material-symbols-outlined text-on-surface-variant">close</span>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <form method="POST" action="{{ route('users.store') }}" class="p-8 space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5">Full Name</label>
                            <input type="text" name="name" required placeholder="e.g. Ahmad Dani"
                                class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:ring-2 focus:ring-secondary/30 focus:border-secondary focus:outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5">Email Address</label>
                            <input type="email" name="email" required placeholder="e.g. dani@velocitron.com"
                                class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:ring-2 focus:ring-secondary/30 focus:border-secondary focus:outline-none transition">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5">Role Access</label>
                                <select name="role" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:ring-2 focus:ring-secondary/30 focus:border-secondary focus:outline-none transition">
                                    <option value="" disabled selected>Pilih role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">
                                            {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-data="{ show: false }">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5">Password</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="Min. 8 char"
                                        class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:ring-2 focus:ring-secondary/30 focus:border-secondary focus:outline-none transition">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-secondary transition-colors">
                                        <span class="material-symbols-outlined text-base" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                            </div>
                            <div x-data="{ show: false }">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1.5">Confirm</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="Ulangi"
                                        class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:ring-2 focus:ring-secondary/30 focus:border-secondary focus:outline-none transition">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-secondary transition-colors">
                                        <span class="material-symbols-outlined text-base" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showAddModal = false"
                                class="flex-1 px-6 py-3 rounded-2xl border border-outline-variant text-sm font-bold text-on-surface-variant hover:bg-surface-container transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-6 py-3 rounded-2xl bg-secondary text-white text-sm font-bold hover:opacity-90 transition shadow-lg shadow-blue-900/20">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="dashboard-card overflow-hidden">
            <div class="dashboard-card-header flex items-center justify-between">
                <div>
                    <h3 class="dashboard-title">All Users</h3>
                    <p class="dashboard-subtitle">{{ $users->count() }} pengguna terdaftar</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">#</th>
                            <th class="px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">User
                            </th>
                            <th class="px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Role
                            </th>
                            <th class="px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Joined
                            </th>
                            <th
                                class="px-5 py-3 text-xs font-bold uppercase tracking-wider text-on-surface-variant text-right">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($users as $i => $user)
                            @php
                                $roleSlug = $user->roles->first()?->name ?? 'no-role';
                                $roleLabel = [
                                    'head-analytics' => 'Head of Analytics',
                                    'financial-controller' => 'Financial Controller',
                                    'logistics-officer' => 'Logistics Officer',
                                    'procurement-director' => 'Procurement Director',
                                    'key-account-manager' => 'Key Account Manager',
                                ][$roleSlug] ?? $roleSlug;
                                $roleColor = [
                                    'head-analytics' => 'bg-purple-50 text-purple-700 ring-purple-200',
                                    'financial-controller' => 'bg-green-50 text-green-700 ring-green-200',
                                    'logistics-officer' => 'bg-orange-50 text-orange-700 ring-orange-200',
                                    'procurement-director' => 'bg-blue-50 text-blue-700 ring-blue-200',
                                    'key-account-manager' => 'bg-cyan-50 text-cyan-700 ring-cyan-200',
                                ][$roleSlug] ?? 'bg-slate-50 text-slate-600 ring-slate-200';
                            @endphp
                            <tr x-data="{ confirmOpen: false }" class="hover:bg-surface-container-low transition-colors">
                                <td class="px-5 py-4 text-sm text-on-surface-variant font-mono">
                                    {{ $i + 1 }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-xs font-bold text-white shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-on-surface">{{ $user->name }}</p>
                                            <p class="text-xs text-on-surface-variant">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center text-xs font-bold px-2.5 py-1 rounded-full ring-1 {{ $roleColor }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm text-on-surface-variant">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if($user->id !== auth()->id())
                                        <button @click="confirmOpen = true"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:text-red-800 transition px-3 py-1.5 rounded-lg hover:bg-red-50">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                            Hapus
                                        </button>

                                        <div x-show="confirmOpen" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="confirmOpen = false">
                                            </div>
                                            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                                                    <span
                                                        class="material-symbols-outlined text-red-500 text-2xl">delete_forever</span>
                                                </div>
                                                <h3 class="text-base font-bold text-center text-on-surface mb-1">Hapus User?</h3>
                                                <p class="text-sm text-center text-on-surface-variant mb-6">
                                                    <span class="font-semibold text-on-surface">{{ $user->name }}</span>
                                                    akan dihapus permanen dan tidak bisa dikembalikan.
                                                </p>
                                                <div class="flex gap-3">
                                                    <button @click="confirmOpen = false"
                                                        class="flex-1 px-4 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition">
                                                        Batal
                                                    </button>
                                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                                        class="flex-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">
                                                            Ya, Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-on-surface-variant px-3 py-1.5">You</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-on-surface-variant text-sm">
                                    <span class="material-symbols-outlined text-4xl block mb-2 opacity-30">group</span>
                                    Belum ada user terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection