<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Velocitron — Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@300,0;1,1&display=swap"
        rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes blob-bounce {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .animate-blob {
            animation: blob-bounce 15s infinite alternate;
        }

        /* Custom scrollbar */
        .dark ::-webkit-scrollbar-track {
            background: #020617;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #1e293b;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 100px white inset !important;
            -webkit-text-fill-color: #0f172a !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .dark input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 100px #0f172a inset !important;
            -webkit-text-fill-color: #fff !important;
        }
    </style>
</head>

<body x-data="{ isDark: true }" :class="{ 'dark': isDark }"
    class="font-['Inter'] min-h-screen flex overflow-hidden transition-colors duration-500">

    {{-- ── THEME TOGGLE ────────────────────────── --}}
    <button @click="isDark = !isDark"
        class="fixed top-6 right-6 z-[100] w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 border shadow-lg group active:scale-95"
        :class="isDark ? 'bg-slate-900 border-slate-800 text-yellow-400 shadow-yellow-500/5' :
            'bg-white border-slate-200 text-indigo-600 shadow-indigo-500/10'">
        <span class="material-symbols-outlined transition-transform duration-500 group-hover:rotate-45"
            x-text="isDark ? 'light_mode' : 'dark_mode'"></span>
    </button>

    {{-- ── LEFT SIDE: VISUAL BRANDING ─────────────────────────── --}}
    <section
        class="hidden lg:flex lg:w-7/12 relative items-center justify-center overflow-hidden border-r transition-colors duration-500"
        :class="isDark ? 'bg-[#020617] border-white/5' : 'bg-slate-50 border-slate-200'">

        {{-- High-tech Grid Pattern --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute top-[-200px] left-[-150px] w-[600px] h-[600px] blur-[120px] rounded-full animate-blob opacity-40 transition-colors duration-500"
                :class="isDark ? 'bg-blue-600/20' : 'bg-blue-400/30'"></div>
            <div class="absolute bottom-[-150px] right-[-100px] w-[500px] h-[500px] blur-[100px] rounded-full animate-blob opacity-40 transition-colors duration-500"
                :class="isDark ? 'bg-indigo-600/20' : 'bg-indigo-400/30'" style="animation-delay: -5s;"></div>

            <div class="absolute inset-0 opacity-[0.07] transition-opacity duration-500"
                :class="isDark ? 'opacity-[0.07]' : 'opacity-[0.15]'"
                style="background-image: linear-gradient(currentColor 1px, transparent 1px), linear-gradient(90deg, currentColor 1px, transparent 1px); background-size: 40px 40px;"
                :style="{ color: isDark ? 'rgba(59, 130, 246, 0.2)' : 'rgba(59, 130, 246, 0.1)' }">
            </div>

            {{-- Radial Overlay --}}
            <div class="absolute inset-0 transition-opacity duration-500"
                :class="isDark ? 'bg-[radial-gradient(circle_at_center,transparent_0%,#020617_100%)] opacity-100' :
                    'bg-[radial-gradient(circle_at_center,transparent_0%,white_100%)] opacity-50'">
            </div>
        </div>

        <div class="relative z-10 text-center max-w-xl px-12 animate-in fade-in zoom-in duration-1000">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full border mb-10 backdrop-blur-md transition-colors duration-500"
                :class="isDark ? 'bg-blue-500/10 border-blue-500/20' : 'bg-blue-50 border-blue-200'">
                <span class="flex h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest"
                    :class="isDark ? 'text-blue-400' : 'text-blue-600'">v2.5 Enterprise Ready</span>
            </div>

            <div class="flex flex-col items-center mb-12 relative group">
                {{-- Ambient Glow behind logo --}}
                <div class="absolute inset-0 blur-[100px] rounded-full scale-150 transition-all duration-1000"
                    :class="isDark ? 'bg-blue-500/20' : 'bg-blue-400/20'"></div>

                <div class="relative z-10 flex flex-col items-center">
                    {{-- Logo Symbol (Cropped to hide image text) --}}
                    <div class="w-64 h-48 overflow-hidden flex items-start justify-center mb-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Symbol"
                            class="w-full h-auto transition-transform duration-700 group-hover:scale-110 -mt-4"
                            style="clip-path: inset(0 0 35% 0);"> {{-- Memotong 35% bagian bawah gambar (teks) --}}
                    </div>

                    <div class="text-center -mt-10">
                        <h1 class="font-['Inter'] text-5xl font-extrabold tracking-tighter leading-none transition-colors duration-500"
                            :class="isDark ? 'text-white' : 'text-slate-900'">
                            VELOCI<span class="text-blue-500">TRON</span>
                        </h1>
                        <p class="font-['Inter'] text-[10px] font-bold tracking-[0.6em] mt-3 transition-colors duration-500"
                            :class="isDark ? 'text-slate-500' : 'text-slate-400'">
                            DYNAMICS
                        </p>
                    </div>
                </div>
            </div>

            <p class="text-xl font-light leading-relaxed mb-12 transition-colors duration-500"
                :class="isDark ? 'text-slate-400' : 'text-slate-600'">
                Empowering businesses with <span class="font-semibold transition-colors duration-500"
                    :class="isDark ? 'text-white' : 'text-slate-900'">real-time predictive analytics</span> and
                seamless intelligence workflows.
            </p>

            {{-- Stat badges --}}
            <div class="grid grid-cols-3 gap-4">
                <template
                    x-for="stat in [{v:'99.9%', l:'Uptime'}, {v:'2.4ms', l:'Latency'}, {v:'AES-256', l:'Secure'}]">
                    <div class="p-4 rounded-2xl border backdrop-blur-md transition-all duration-500"
                        :class="isDark ? 'bg-white/5 border-white/5' : 'bg-white/50 border-slate-200 shadow-sm'">
                        <p class="text-2xl font-bold transition-colors duration-500"
                            :class="isDark ? 'text-white' : 'text-slate-900'" x-text="stat.v"></p>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-slate-500" x-text="stat.l"></p>
                    </div>
                </template>
            </div>
        </div>

        <div class="absolute bottom-10 left-12 text-xs font-medium transition-colors duration-500"
            :class="isDark ? 'text-slate-600' : 'text-slate-400'">
            © 2026 Velocitron BI Group. All rights reserved.
        </div>
    </section>

    {{-- ── RIGHT SIDE: LOGIN FORM ────────────────────────────── --}}
    <section
        class="w-full lg:w-5/12 flex items-center justify-center p-8 md:p-16 relative transition-colors duration-500"
        :class="isDark ? 'bg-slate-950' : 'bg-white'">

        {{-- Mobile backgrounds --}}
        <div class="lg:hidden absolute inset-0 z-0 opacity-40">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 blur-[80px] rounded-full"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-600/10 blur-[80px] rounded-full"></div>
        </div>

        <div
            class="w-full max-w-[400px] relative z-10 animate-in slide-in-from-right-10 fade-in duration-700 delay-300">

            <div class="mb-10 text-center lg:text-left">
                <div class="lg:hidden flex flex-col items-center mb-10">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-white">trending_up</span>
                    </div>
                    <h1 class="font-['Inter'] text-2xl font-extrabold tracking-tighter transition-colors duration-500"
                        :class="isDark ? 'text-white' : 'text-slate-900'">
                        VELOCI<span class="text-blue-500">TRON</span>
                    </h1>
                </div>
                <h2 class="font-['Inter'] text-3xl font-bold mb-3 transition-colors duration-500 text-center"
                    :class="isDark ? 'text-white' : 'text-slate-900'">Sign In</h2>
                <p class="font-medium transition-colors duration-500 text-center"
                    :class="isDark ? 'text-slate-400' : 'text-slate-500'">Access the executive intelligence console.
                </p>
            </div>

            <!-- Errors -->
            @if ($errors->any())
                <div class="mb-8 p-4 rounded-2xl flex items-start gap-3 animate-in shake duration-500 border transition-all duration-500"
                    :class="isDark ? 'bg-red-500/10 border-red-500/20' : 'bg-red-50 border-red-200'">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <p class="text-sm text-red-600 font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Work Email</label>
                    <div class="relative group">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-blue-600 transition-colors text-xl">mail</span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-12 pr-5 py-4 rounded-2xl text-sm outline-none transition-all placeholder:text-slate-500 border"
                            :class="isDark ?
                                'bg-slate-900/50 border-slate-800 text-white focus:ring-blue-500/20 focus:border-blue-500' :
                                'bg-slate-50 border-slate-200 text-slate-900 focus:ring-blue-600/10 focus:border-blue-600'"
                            placeholder="username@velocitron.com">
                    </div>
                </div>

                <div class="space-y-2" x-data="{ show: false }">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Master
                        Password</label>
                    <div class="relative group">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-blue-600 transition-colors text-xl">lock</span>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full pl-12 pr-12 py-4 rounded-2xl text-sm outline-none transition-all placeholder:text-slate-500 border"
                            :class="isDark ?
                                'bg-slate-900/50 border-slate-800 text-white focus:ring-blue-500/20 focus:border-blue-500' :
                                'bg-slate-50 border-slate-200 text-slate-900 focus:ring-blue-600/10 focus:border-blue-600'"
                            placeholder="••••••••••••">
                        <button type="button" @click="show = !show"
                            class="absolute right-4 inset-y-0 flex items-center text-slate-500 hover:text-blue-600 transition-colors">
                            <span class="material-symbols-outlined text-xl leading-none"
                                x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between py-1">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="remember" id="remember"
                            class="peer w-5 h-5 rounded-lg border-slate-300 bg-slate-50 text-blue-600 focus:ring-blue-500 transition-colors"
                            :class="isDark && 'border-slate-800 bg-slate-900'">
                        <span class="text-sm font-medium transition-colors duration-500"
                            :class="isDark ? 'text-slate-400 group-hover:text-slate-200' :
                                'text-slate-500 group-hover:text-slate-800'">Remember
                            this session</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3 text-white font-['Inter'] font-semibold rounded-2xl shadow-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2 text-base"
                    :class="isDark ? 'bg-blue-600 hover:bg-blue-500 shadow-blue-900/10' :
                        'bg-slate-900 hover:bg-slate-800 shadow-slate-200'">
                    Access Console
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </button>

                <div class="pt-4 text-center">
                    <a href="/" class="inline-flex items-center gap-1.5 text-xs font-medium transition-all duration-300 hover:underline"
                        :class="isDark ? 'text-slate-500 hover:text-slate-300' : 'text-slate-400 hover:text-slate-600'">
                        <span class="material-symbols-outlined text-sm">home</span>
                        Back to Homepage
                    </a>
                </div>
            </form>

            <div class="mt-12 p-6 rounded-2xl border transition-colors duration-500"
                :class="isDark ? 'bg-white/[0.02] border-white/5' : 'bg-slate-50 border-slate-100'">
                <p class="text-[10px] leading-relaxed text-center font-semibold tracking-wide"
                    :class="isDark ? 'text-slate-500' : 'text-slate-400'">
                    SECURITY NOTICE:<br>
                    UNAUTHORIZED ACCESS IS STRICTLY PROHIBITED AND MONITORED BY VELOCITRON SIEM.
                </p>
            </div>

            <div class="lg:hidden mt-8 text-center text-xs font-medium transition-colors duration-500"
                :class="isDark ? 'text-slate-600' : 'text-slate-400'">
                © 2026 Velocitron BI Group.
            </div>
        </div>
    </section>

</body>

</html>
