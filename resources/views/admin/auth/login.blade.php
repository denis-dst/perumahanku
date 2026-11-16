<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - {{ \App\Models\Setting::get('site_name', 'Perumahanku') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: { 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1' },
                        nature: { 500: '#10b981', 600: '#059669' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-900 text-slate-100 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-slate-800/90 rounded-3xl p-8 border border-slate-700 shadow-2xl backdrop-blur-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-nature-500 mx-auto flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-brand-500/20 mb-4">
                <i class="fa-solid fa-house-chimney"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">{{ \App\Models\Setting::get('site_name', 'Perumahanku') }}</h1>
            <p class="text-xs text-slate-400 mt-1">Portal Akses Administrator & Marketing Gallery</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-950/60 border border-rose-800 text-rose-300 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1.5">Email Administrator</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500 text-sm">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email', 'admin@perumahanku.id') }}" required
                           class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500 text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" value="admin123" required
                           class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400 pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-brand-500 focus:ring-0">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-500 hover:to-nature-500 text-white font-bold text-sm shadow-lg transition duration-200 mt-2">
                Masuk ke Panel Admin
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-700/80 text-center">
            <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-white transition">
                ← Kembali ke Beranda Website
            </a>
        </div>
    </div>

</body>
</html>
