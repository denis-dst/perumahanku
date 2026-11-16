<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - {{ \App\Models\Setting::get('site_name', 'Perumahanku') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        nature: {
                            50: '#f0fdf4',
                            500: '#10b981',
                            600: '#059669',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 md:static md:translate-x-0 flex flex-col justify-between shadow-xl">
            <div>
                <!-- Brand -->
                <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-nature-500 flex items-center justify-center text-white font-bold shadow-md">
                        <i class="fa-solid fa-house-chimney"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-white text-lg tracking-tight block">{{ \App\Models\Setting::get('site_name', 'Perumahanku') }}</span>
                        <span class="text-[10px] text-brand-400 font-semibold uppercase tracking-wider">Admin Workspace</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.projects.*') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <i class="fa-solid fa-city w-5 text-center"></i>
                        <span>Katalog Perumahan</span>
                    </a>

                    <a href="{{ route('admin.bookings.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.bookings.*') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-regular fa-calendar-check w-5 text-center"></i>
                            <span>Booking Survei</span>
                        </div>
                        @php
                            $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.banks.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.banks.*') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <i class="fa-solid fa-building-columns w-5 text-center"></i>
                        <span>Bank & Suku Bunga KPR</span>
                    </a>

                    <a href="{{ route('admin.blog.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.blog.*') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <i class="fa-solid fa-newspaper w-5 text-center"></i>
                        <span>Artikel & Tips SEO</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-brand-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <i class="fa-solid fa-sliders w-5 text-center"></i>
                        <span>Pengaturan Web & PT</span>
                    </a>
                </nav>
            </div>

            <!-- Footer User Section -->
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center justify-between px-2 mb-3">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-brand-500 text-white flex items-center justify-center font-bold text-xs">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@perumahanku.id' }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('home') }}" target="_blank" class="flex-1 py-2 text-center text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Web
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-2 text-center text-xs bg-rose-900/40 hover:bg-rose-800 text-rose-300 rounded-lg transition">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar Header -->
            <header class="h-20 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-brand-600 hover:border-brand-300 text-xs font-semibold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-globe"></i>
                        <span>Lihat Website</span>
                    </a>
                </div>
            </header>

            <!-- Page Body -->
            <main class="p-6 md:p-8 flex-1">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-xs">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-xs">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
