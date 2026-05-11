<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velocitron Dynamics — Your Trusted Partner in Office, Tech & Furniture Solutions</title>
    <meta name="description" content="Velocitron Dynamics is a leading distributor of technology, furniture, and office supplies for consumers, corporate, and home office segments.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Material+Symbols+Outlined:wght,FILL@300,0;1,1&display=swap" rel="stylesheet">
    <style>
        [x-cloak]{display:none!important}
        @keyframes blob{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(30px,-50px) scale(1.1)}66%{transform:translate(-20px,20px) scale(0.9)}}
        .animate-blob{animation:blob 15s infinite alternate}
        @keyframes fade-up{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fade-up .8s ease-out forwards}
        .d1{animation-delay:.1s}.d2{animation-delay:.2s}.d3{animation-delay:.3s}.d4{animation-delay:.4s}
    </style>
</head>
<body x-data="{ isDark: true }" :class="isDark ? 'bg-slate-950 text-white' : 'bg-white text-slate-900'"
    class="font-['Inter'] antialiased overflow-x-hidden transition-colors duration-500">

    {{-- THEME TOGGLE --}}
    <button @click="isDark = !isDark"
        class="fixed bottom-6 right-6 z-[100] w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 border shadow-lg group active:scale-95"
        :class="isDark ? 'bg-slate-900 border-slate-800 text-yellow-400 shadow-yellow-500/5' : 'bg-white border-slate-200 text-indigo-600 shadow-indigo-500/10'">
        <span class="material-symbols-outlined transition-transform duration-500 group-hover:rotate-45" x-text="isDark ? 'light_mode' : 'dark_mode'"></span>
    </button>

    {{-- NAVBAR --}}
    {{-- NAVBAR --}}
    <nav x-data="{open:false,s:false}" @scroll.window="s=window.scrollY>50"
        class="fixed top-0 w-full z-50 transition-all duration-300"
        :class="s ? (isDark ? 'bg-slate-950/90 backdrop-blur-xl border-b border-white/5 shadow-xl' : 'bg-white/90 backdrop-blur-xl border-b border-slate-200 shadow-lg') : 'bg-transparent'">
        <div class="max-w-7xl mx-auto flex justify-between items-center h-20 px-6">
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain" style="clip-path:inset(0 0 35% 0)">
                <span class="text-xl font-extrabold tracking-tighter transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">VELOCI<span class="text-blue-500">TRON</span></span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#about" class="text-sm transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900'">About</a>
                <a href="#products" class="text-sm transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900'">Products</a>
                <a href="#segments" class="text-sm transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900'">Segments</a>
                <a href="#reach" class="text-sm transition-colors" :class="isDark ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900'">Reach</a>
            </div>
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all active:scale-95 shadow-lg shadow-blue-600/20 flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">login</span> Sign In
            </a>
        </div>
    </nav>

    <main>
        {{-- HERO --}}
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-[-200px] left-[-150px] w-[600px] h-[600px] blur-[120px] rounded-full animate-blob" :class="isDark ? 'bg-blue-600/10' : 'bg-blue-400/20'"></div>
                <div class="absolute bottom-[-150px] right-[-100px] w-[500px] h-[500px] blur-[100px] rounded-full animate-blob" :class="isDark ? 'bg-indigo-600/10' : 'bg-indigo-400/20'" style="animation-delay:-5s"></div>
                <div class="absolute inset-0 transition-opacity duration-500" :class="isDark ? 'opacity-[0.05]' : 'opacity-[0.1]'" style="background-image:linear-gradient(rgba(59,130,246,.15) 1px,transparent 1px),linear-gradient(90deg,rgba(59,130,246,.15) 1px,transparent 1px);background-size:40px 40px"></div>
                <div class="absolute inset-0 transition-opacity duration-500" :class="isDark ? 'bg-[radial-gradient(circle_at_center,transparent_0%,#020617_100%)]' : 'bg-[radial-gradient(circle_at_center,transparent_0%,white_100%)] opacity-80'"></div>
            </div>
            <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 mb-8 backdrop-blur-md fade-up">
                    <span class="flex h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Trusted Distributor Since 2019</span>
                </div>
                <div class="fade-up d1 opacity-0">
                    <div class="w-48 h-36 overflow-hidden mx-auto mb-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Velocitron" class="w-full h-auto -mt-2" style="clip-path:inset(0 0 35% 0)">
                    </div>
                    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tighter leading-none -mt-6 transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">VELOCI<span class="text-blue-500">TRON</span></h1>
                    <p class="text-sm font-bold tracking-[0.6em] mt-2 transition-colors duration-500" :class="isDark ? 'text-slate-500' : 'text-slate-400'">DYNAMICS</p>
                </div>
                <p class="text-xl md:text-2xl font-light leading-relaxed max-w-3xl mx-auto mt-8 fade-up d2 opacity-0 transition-colors duration-500" :class="isDark ? 'text-slate-400' : 'text-slate-600'">
                    Your trusted partner for <span class="font-semibold transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">Technology</span>,
                    <span class="font-semibold transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">Furniture</span>, and
                    <span class="font-semibold transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">Office Supplies</span> —
                    serving consumers, corporates, and home offices nationwide.
                </p>
                <div class="flex items-center justify-center gap-4 mt-10 fade-up d3 opacity-0">
                    <a href="#about" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-2xl font-semibold transition-all active:scale-95 shadow-2xl shadow-blue-600/20 flex items-center gap-2">
                        Discover More <span class="material-symbols-outlined text-xl">arrow_downward</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-16 max-w-3xl mx-auto fade-up d4 opacity-0">
                    <div class="p-4 rounded-2xl border backdrop-blur-md transition-all duration-500" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-slate-50 border-slate-200'"><p class="text-2xl font-bold">3</p><p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Product Lines</p></div>
                    <div class="p-4 rounded-2xl border backdrop-blur-md transition-all duration-500" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-slate-50 border-slate-200'"><p class="text-2xl font-bold">4</p><p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Sales Regions</p></div>
                    <div class="p-4 rounded-2xl border backdrop-blur-md transition-all duration-500" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-slate-50 border-slate-200'"><p class="text-2xl font-bold">10K+</p><p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Orders / Year</p></div>
                    <div class="p-4 rounded-2xl border backdrop-blur-md transition-all duration-500" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-slate-50 border-slate-200'"><p class="text-2xl font-bold">3</p><p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Customer Segments</p></div>
                </div>
            </div>
        </section>

        {{-- ABOUT --}}
        <section id="about" class="py-24 md:py-32">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">About Us</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mt-4 leading-tight transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">Delivering Quality<br>Products <span class="text-blue-500">Everywhere</span></h2>
                    <p class="mt-6 leading-relaxed text-lg transition-colors duration-500" :class="isDark ? 'text-slate-400' : 'text-slate-600'">
                        <span class="font-semibold transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">Velocitron Dynamics</span> is a leading retail and distribution company specializing in technology products, office furniture, and essential office supplies. Established in 2019, we serve thousands of customers across multiple regions with fast, reliable, and efficient logistics.
                    </p>
                    <p class="mt-4 leading-relaxed transition-colors duration-500" :class="isDark ? 'text-slate-400' : 'text-slate-600'">
                        From laptops and printers to ergonomic desks and everyday stationery — we provide everything businesses and individuals need to build productive workspaces. Our multi-segment approach ensures tailored solutions for consumers, corporate clients, and home office professionals.
                    </p>
                    <div class="grid grid-cols-2 gap-6 mt-10">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-600/20 rounded-xl flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-blue-400 text-xl">local_shipping</span></div>
                            <div><p class="font-semibold text-sm">Fast Delivery</p><p class="text-xs text-slate-500 mt-1">Multiple ship modes available</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-600/20 rounded-xl flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-blue-400 text-xl">verified</span></div>
                            <div><p class="font-semibold text-sm">Quality Assured</p><p class="text-xs text-slate-500 mt-1">Trusted brands & warranties</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-600/20 rounded-xl flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-blue-400 text-xl">support_agent</span></div>
                            <div><p class="font-semibold text-sm">Dedicated Support</p><p class="text-xs text-slate-500 mt-1">B2B & B2C account managers</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-600/20 rounded-xl flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-blue-400 text-xl">inventory_2</span></div>
                            <div><p class="font-semibold text-sm">Massive Catalog</p><p class="text-xs text-slate-500 mt-1">1,800+ products in stock</p></div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-500/10 blur-[80px] rounded-full"></div>
                    <div class="relative bg-white/5 border rounded-3xl p-8 backdrop-blur-md transition-all duration-500" :class="isDark ? 'bg-white/5 border-white/10' : 'bg-slate-50 border-slate-200 shadow-xl'">
                        <div class="border-b border-white/5 pb-4 mb-6 flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Company Snapshot</span>
                            <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div><p class="text-xs text-slate-500">Founded</p><p class="text-lg font-bold mt-1">2019</p></div>
                            <div><p class="text-xs text-slate-500">Industry</p><p class="text-lg font-bold mt-1">Retail & Distribution</p></div>
                            <div><p class="text-xs text-slate-500">Product Lines</p><p class="text-lg font-bold mt-1">3 Categories</p></div>
                            <div><p class="text-xs text-slate-500">Customer Segments</p><p class="text-lg font-bold mt-1">3 Segments</p></div>
                        </div>
                        <div class="pt-6 border-t border-white/5 mt-6">
                            <p class="text-xs text-slate-500 mb-3">Ship Modes</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-medium">Standard Class</span>
                                <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-medium">Second Class</span>
                                <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-medium">First Class</span>
                                <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-medium">Same Day</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- PRODUCT CATEGORIES --}}
        <section id="products" class="py-24 md:py-32 transition-colors duration-500" :class="isDark ? 'bg-slate-900/50' : 'bg-slate-50'">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">Our Products</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mt-4 transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">What We <span class="text-blue-500">Sell</span></h2>
                    <p class="mt-4 max-w-2xl mx-auto transition-colors duration-500" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Three core product categories designed to equip modern workspaces — from high-end tech to everyday essentials.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="group p-8 rounded-3xl bg-gradient-to-br from-blue-600/10 to-transparent border border-blue-500/10 hover:border-blue-500/30 transition-all duration-500">
                        <span class="material-symbols-outlined text-blue-400 text-4xl">devices</span>
                        <h3 class="text-2xl font-bold mt-4 mb-3">Technology</h3>
                        <p class="text-slate-400 leading-relaxed">Laptops, phones, printers, copiers, and accessories from leading brands. Powering productivity for businesses of all sizes.</p>
                        <div class="flex flex-wrap gap-2 mt-6">
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-medium">Phones</span>
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-medium">Computers</span>
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-medium">Copiers</span>
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-medium">Accessories</span>
                        </div>
                    </div>
                    <div class="group p-8 rounded-3xl bg-gradient-to-br from-amber-600/10 to-transparent border border-amber-500/10 hover:border-amber-500/30 transition-all duration-500">
                        <span class="material-symbols-outlined text-amber-400 text-4xl">chair</span>
                        <h3 class="text-2xl font-bold mt-4 mb-3">Furniture</h3>
                        <p class="text-slate-400 leading-relaxed">Ergonomic chairs, standing desks, bookcases, and furnishings. Creating comfortable and productive work environments.</p>
                        <div class="flex flex-wrap gap-2 mt-6">
                            <span class="px-3 py-1 bg-amber-500/10 text-amber-400 rounded-lg text-xs font-medium">Chairs</span>
                            <span class="px-3 py-1 bg-amber-500/10 text-amber-400 rounded-lg text-xs font-medium">Tables</span>
                            <span class="px-3 py-1 bg-amber-500/10 text-amber-400 rounded-lg text-xs font-medium">Bookcases</span>
                            <span class="px-3 py-1 bg-amber-500/10 text-amber-400 rounded-lg text-xs font-medium">Furnishings</span>
                        </div>
                    </div>
                    <div class="group p-8 rounded-3xl bg-gradient-to-br from-emerald-600/10 to-transparent border border-emerald-500/10 hover:border-emerald-500/30 transition-all duration-500">
                        <span class="material-symbols-outlined text-emerald-400 text-4xl">edit_note</span>
                        <h3 class="text-2xl font-bold mt-4 mb-3">Office Supplies</h3>
                        <p class="text-slate-400 leading-relaxed">Paper, binders, pens, labels, envelopes, and storage. The everyday essentials that keep offices running smoothly.</p>
                        <div class="flex flex-wrap gap-2 mt-6">
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-lg text-xs font-medium">Paper</span>
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-lg text-xs font-medium">Binders</span>
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-lg text-xs font-medium">Art</span>
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-lg text-xs font-medium">Storage</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- CUSTOMER SEGMENTS --}}
        <section id="segments" class="py-24 md:py-32">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">Customer Segments</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mt-4 transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">Who We <span class="text-blue-500">Serve</span></h2>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-8 rounded-3xl border hover:border-cyan-500/20 transition-all duration-500 text-center" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-white border-slate-200 shadow-lg'">
                        <div class="w-16 h-16 bg-cyan-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6"><span class="material-symbols-outlined text-cyan-400 text-3xl">person</span></div>
                        <h3 class="text-xl font-bold mb-2">Consumer</h3>
                        <p class="text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Individual buyers looking for personal tech, home furniture, and daily office essentials at competitive prices.</p>
                    </div>
                    <div class="p-8 rounded-3xl border hover:border-blue-500/20 transition-all duration-500 text-center" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-white border-slate-200 shadow-lg'">
                        <div class="w-16 h-16 bg-blue-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6"><span class="material-symbols-outlined text-blue-400 text-3xl">apartment</span></div>
                        <h3 class="text-xl font-bold mb-2">Corporate</h3>
                        <p class="text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Enterprise clients with bulk procurement needs, dedicated account managers, and custom pricing agreements.</p>
                    </div>
                    <div class="p-8 rounded-3xl border hover:border-indigo-500/20 transition-all duration-500 text-center" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-white border-slate-200 shadow-lg'">
                        <div class="w-16 h-16 bg-indigo-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6"><span class="material-symbols-outlined text-indigo-400 text-3xl">home_work</span></div>
                        <h3 class="text-xl font-bold mb-2">Home Office</h3>
                        <p class="text-sm" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Remote workers and freelancers building productive home setups with curated product bundles and fast delivery.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- SALES REGIONS --}}
        <section id="reach" class="py-24 md:py-32 transition-colors duration-500" :class="isDark ? 'bg-slate-900/50' : 'bg-slate-50'">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">Sales Coverage</span>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mt-4 mb-6 transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">Our Sales <span class="text-blue-500">Regions</span></h2>
                <p class="max-w-2xl mx-auto mb-16 transition-colors duration-500" :class="isDark ? 'text-slate-400' : 'text-slate-600'">We operate across four major regions with dedicated warehouses and logistics networks to ensure fast, reliable delivery.</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="p-8 rounded-3xl border hover:border-blue-500/20 transition-all" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-white border-slate-200 shadow-lg'">
                        <span class="material-symbols-outlined text-blue-400 text-3xl mb-4">east</span>
                        <h3 class="text-xl font-bold">East</h3>
                        <p class="text-xs text-slate-500 mt-2">New York, Philadelphia, Boston & more</p>
                    </div>
                    <div class="p-8 rounded-3xl border hover:border-emerald-500/20 transition-all" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-white border-slate-200 shadow-lg'">
                        <span class="material-symbols-outlined text-emerald-400 text-3xl mb-4">west</span>
                        <h3 class="text-xl font-bold">West</h3>
                        <p class="text-xs text-slate-500 mt-2">Los Angeles, Seattle, San Francisco & more</p>
                    </div>
                    <div class="p-8 rounded-3xl border hover:border-amber-500/20 transition-all" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-white border-slate-200 shadow-lg'">
                        <span class="material-symbols-outlined text-amber-400 text-3xl mb-4">location_on</span>
                        <h3 class="text-xl font-bold">Central</h3>
                        <p class="text-xs text-slate-500 mt-2">Chicago, Houston, Dallas & more</p>
                    </div>
                    <div class="p-8 rounded-3xl border hover:border-rose-500/20 transition-all" :class="isDark ? 'bg-white/5 border-white/5' : 'bg-white border-slate-200 shadow-lg'">
                        <span class="material-symbols-outlined text-rose-400 text-3xl mb-4">south</span>
                        <h3 class="text-xl font-bold">South</h3>
                        <p class="text-xs text-slate-500 mt-2">Miami, Atlanta, Charlotte & more</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="py-24 md:py-32 relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/10 blur-[150px] rounded-full"></div>
            </div>
            <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">Ready to Get<br><span class="text-blue-500">Started</span>?</h2>
                <p class="mt-6 text-lg transition-colors duration-500" :class="isDark ? 'text-slate-400' : 'text-slate-600'">Sign in to access the Velocitron Intelligence Console — your dashboard for sales analytics, procurement, and decision support.</p>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-2xl font-semibold transition-all active:scale-95 shadow-2xl shadow-blue-600/20 text-lg mt-10">
                    Sign In <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="border-t py-12 transition-colors duration-500" :class="isDark ? 'border-white/5' : 'border-slate-200'">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain" style="clip-path:inset(0 0 35% 0)">
                    <span class="font-extrabold tracking-tighter transition-colors duration-500" :class="isDark ? 'text-white' : 'text-slate-900'">VELOCI<span class="text-blue-500">TRON</span></span>
                </div>
                <div class="flex gap-8 text-sm" :class="isDark ? 'text-slate-500' : 'text-slate-400'">
                    <a href="#about" class="transition-colors" :class="isDark ? 'hover:text-white' : 'hover:text-slate-900'">About</a>
                    <a href="#products" class="transition-colors" :class="isDark ? 'hover:text-white' : 'hover:text-slate-900'">Products</a>
                    <a href="#segments" class="transition-colors" :class="isDark ? 'hover:text-white' : 'hover:text-slate-900'">Segments</a>
                    <a href="#reach" class="transition-colors" :class="isDark ? 'hover:text-white' : 'hover:text-slate-900'">Regions</a>
                    <a href="{{ route('login') }}" class="transition-colors" :class="isDark ? 'hover:text-white' : 'hover:text-slate-900'">Sign In</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t text-center text-xs transition-colors duration-500" :class="isDark ? 'border-white/5 text-slate-600' : 'border-slate-200 text-slate-400'">
                © {{ date('Y') }} Velocitron Dynamics. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>