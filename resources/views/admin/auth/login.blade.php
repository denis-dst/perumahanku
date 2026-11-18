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
    
    <!-- Tailwind CSS (CDN) -->
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
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-900 text-slate-100 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-slate-800/90 rounded-3xl p-8 border border-slate-700 shadow-2xl backdrop-blur-md">
        <!-- Logo & Branding -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-nature-500 mx-auto flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-brand-500/20 mb-4">
                <i class="fa-solid fa-house-chimney"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">{{ \App\Models\Setting::get('site_name', 'Perumahanku') }}</h1>
            <p class="text-xs text-slate-400 mt-1">Portal Akses Administrator & Marketing Gallery</p>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-950/60 border border-rose-800 text-rose-300 text-xs flex items-start gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm mt-0.5 shrink-0"></i>
                <div class="min-w-0 flex-1">
                    <p class="font-bold">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4"
              x-data="{
                  showPassword: false,
                  captchaQuestion: '{{ $captchaQuestion ?? '5 + 3' }}',
                  isRefreshingCaptcha: false,
                  refreshCaptcha() {
                      this.isRefreshingCaptcha = true;
                      fetch('{{ route('admin.captcha.refresh') }}')
                          .then(res => res.json())
                          .then(data => {
                              this.captchaQuestion = data.question;
                              this.isRefreshingCaptcha = false;
                          })
                          .catch(() => {
                              this.isRefreshingCaptcha = false;
                          });
                  }
              }">
            @csrf

            <!-- Email (No Auto-Fill) -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1.5">Email Administrator</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500 text-sm pointer-events-none">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="off"
                           placeholder="nama@email.com"
                           class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                </div>
            </div>

            <!-- Password with Show/Hide Toggle (No Auto-Fill) -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500 text-sm pointer-events-none">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input :type="showPassword ? 'text' : 'password'"
                           name="password"
                           value=""
                           required
                           autocomplete="new-password"
                           placeholder="Masukkan kata sandi"
                           class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-10 pr-11 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                    
                    <!-- View Password Toggle Button -->
                    <button type="button"
                            @click="showPassword = !showPassword"
                            tabindex="-1"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-white transition focus:outline-none"
                            :title="showPassword ? 'Sembunyikan Kata Sandi' : 'Tampilkan Kata Sandi'">
                        <i class="fa-solid text-sm" :class="showPassword ? 'fa-eye-slash text-brand-400' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <!-- Math Captcha Security Verification -->
            <div class="pt-1">
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold uppercase text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-brand-400 text-xs"></i>
                        <span>Verifikasi Keamanan</span>
                    </label>
                    <button type="button"
                            @click="refreshCaptcha()"
                            class="text-[11px] text-brand-400 hover:text-brand-300 font-semibold flex items-center gap-1 transition focus:outline-none">
                        <i class="fa-solid fa-arrows-rotate text-[10px]" :class="isRefreshingCaptcha ? 'animate-spin' : ''"></i>
                        <span>Ganti Soal</span>
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Captcha Math Challenge Pill -->
                    <div class="flex items-center justify-center px-4 py-2.5 bg-slate-950/80 border border-slate-700 rounded-xl font-black text-sm tracking-wider text-emerald-400 shrink-0 select-none shadow-inner">
                        <i class="fa-solid fa-calculator text-xs text-slate-500 mr-2"></i>
                        <span x-text="captchaQuestion + ' = ?'"></span>
                    </div>

                    <!-- Captcha Input Field -->
                    <div class="relative flex-1">
                        <input type="number"
                               name="captcha"
                               required
                               autocomplete="off"
                               placeholder="Hasilnya..."
                               class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500 transition font-bold">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400 pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-brand-500 focus:ring-0">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-500 hover:to-nature-500 text-white font-bold text-sm shadow-lg transition duration-200 mt-2 flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                <span>Masuk ke Panel Admin</span>
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-700/80 text-center">
            <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-white transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Beranda Website</span>
            </a>
        </div>
    </div>

</body>
</html>
