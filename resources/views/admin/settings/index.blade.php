@extends('layouts.admin')

@section('page_title', 'Pengaturan Profil & Website')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{
    activeTab: '{{ request('tab', 'profile') }}',
    photoPreview: null,
    removeAvatar: false,
    showCurrentPass: false,
    showNewPass: false,
    showConfirmPass: false,
    previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            this.removeAvatar = false;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    },
    clearPhoto() {
        this.photoPreview = null;
        this.removeAvatar = true;
        if (this.$refs.avatarInput) {
            this.$refs.avatarInput.value = '';
        }
    }
}">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Pusat Pengaturan & Keamanan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola profil administrator, foto profil, ganti kata sandi, serta konfigurasi identitas website.</p>
        </div>

        <!-- Navigation Tabs Pill -->
        <div class="inline-flex p-1 bg-white border border-slate-200 rounded-2xl shadow-2xs self-start sm:self-auto">
            <button type="button"
                    @click="activeTab = 'profile'"
                    :class="activeTab === 'profile' ? 'bg-brand-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm transition flex items-center gap-2">
                <i class="fa-solid fa-user-shield text-xs"></i>
                <span>Profil & Keamanan</span>
            </button>
            <button type="button"
                    @click="activeTab = 'website'"
                    :class="activeTab === 'website' ? 'bg-brand-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm transition flex items-center gap-2">
                <i class="fa-solid fa-sliders text-xs"></i>
                <span>Website & Perusahaan</span>
            </button>
        </div>
    </div>

    <!-- Error Messages Notification -->
    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-start gap-3 shadow-xs">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg mt-0.5 shrink-0"></i>
            <div class="space-y-1">
                <p class="font-bold">Terjadi kesalahan pada data yang Anda kirimkan:</p>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- TAB 1: PROFIL & KEAMANAN AKUN -->
    <div x-show="activeTab === 'profile'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left Col: Profile & Avatar (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Identitas Akun</span>
                    <h3 class="text-lg font-bold text-slate-900 mt-0.5">Profil & Foto Administrator</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Ubah foto avatar, nama lengkap, dan alamat email login Anda.</p>
                </div>

                <form action="{{ route('admin.settings.profile') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'">

                    <!-- Avatar Upload & Live Preview Section -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <!-- Avatar Display Frame -->
                        <div class="relative shrink-0">
                            <!-- Live Preview (if new file selected) -->
                            <template x-if="photoPreview && !removeAvatar">
                                <img :src="photoPreview" alt="Pratinjau Foto" class="w-24 h-24 rounded-2xl object-cover border-2 border-brand-500 shadow-md">
                            </template>

                            <!-- Current Avatar from Database -->
                            <template x-if="!photoPreview && !removeAvatar">
                                @if($user && $user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-2xl object-cover border-2 border-slate-200 shadow-md">
                                @else
                                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-brand-600 to-nature-600 text-white flex items-center justify-center text-2xl font-extrabold shadow-md">
                                        {{ $user->initials ?? 'AD' }}
                                    </div>
                                @endif
                            </template>

                            <!-- Removed Avatar (Placeholder) -->
                            <template x-if="removeAvatar">
                                <div class="w-24 h-24 rounded-2xl bg-slate-200 text-slate-500 flex items-center justify-center text-3xl font-extrabold border-2 border-dashed border-slate-300">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            </template>
                        </div>

                        <!-- Actions & Upload Instructions -->
                        <div class="space-y-2 text-center sm:text-left flex-1 min-w-0">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Foto Profil Administrator</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Gunakan foto formal atau logo developer dengan rasio 1:1. Format yang didukung: <strong>JPG, PNG, atau WEBP</strong> (Maksimal 2 MB).
                            </p>

                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                                <!-- Hidden Real File Input -->
                                <input type="file"
                                       name="avatar"
                                       id="avatar_input"
                                       accept="image/png,image/jpeg,image/webp"
                                       class="hidden"
                                       x-ref="avatarInput"
                                       @change="previewImage($event)">

                                <button type="button"
                                        @click="$refs.avatarInput.click()"
                                        class="px-3.5 py-1.5 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs border border-brand-200 shadow-2xs transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-camera"></i>
                                    <span>Pilih Foto Baru</span>
                                </button>

                                <button type="button"
                                        x-show="photoPreview || (!removeAvatar && {{ $user && $user->avatar ? 'true' : 'false' }})"
                                        @click="clearPhoto()"
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-semibold text-xs border border-slate-200 hover:border-rose-200 transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-trash-can"></i>
                                    <span>Hapus Foto</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Name Field -->
                    <div>
                        <label for="admin_name" class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Nama Lengkap Administrator *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm pointer-events-none">
                                <i class="fa-solid fa-user-check"></i>
                            </span>
                            <input type="text"
                                   id="admin_name"
                                   name="name"
                                   value="{{ old('name', $user->name ?? '') }}"
                                   required
                                   placeholder="Contoh: Administrator Graha"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="admin_profile_email" class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Alamat Email Login *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm pointer-events-none">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email"
                                   id="admin_profile_email"
                                   name="email"
                                   value="{{ old('email', $user->email ?? '') }}"
                                   required
                                   placeholder="admin@perumahanku.id"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Email ini digunakan sebagai kredensial utama untuk mengakses dashboard administrator.</p>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-700 hover:to-nature-700 text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center gap-2">
                            <i class="fa-regular fa-floppy-disk"></i>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Col: Change Password (5 cols) -->
            <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Keamanan Akses</span>
                    <h3 class="text-lg font-bold text-slate-900 mt-0.5">Ganti Kata Sandi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui kata sandi akun secara berkala demi keamanan data properti.</p>
                </div>

                <form action="{{ route('admin.settings.password') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Kata Sandi Saat Ini *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm pointer-events-none">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input :type="showCurrentPass ? 'text' : 'password'"
                                   id="current_password"
                                   name="current_password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Kata sandi lama"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-11 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            
                            <button type="button"
                                    @click="showCurrentPass = !showCurrentPass"
                                    tabindex="-1"
                                    aria-label="Lihat kata sandi saat ini"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-700 focus:outline-none">
                                <i class="fa-solid text-xs" :class="showCurrentPass ? 'fa-eye-slash text-brand-600' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Kata Sandi Baru *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm pointer-events-none">
                                <i class="fa-solid fa-key"></i>
                            </span>
                            <input :type="showNewPass ? 'text' : 'password'"
                                   id="new_password"
                                   name="password"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Minimal 8 karakter"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-11 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            
                            <button type="button"
                                    @click="showNewPass = !showNewPass"
                                    tabindex="-1"
                                    aria-label="Lihat kata sandi baru"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-700 focus:outline-none">
                                <i class="fa-solid text-xs" :class="showNewPass ? 'fa-eye-slash text-brand-600' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm pointer-events-none">
                                <i class="fa-solid fa-shield-halved"></i>
                            </span>
                            <input :type="showConfirmPass ? 'text' : 'password'"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Ulangi kata sandi baru"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-11 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            
                            <button type="button"
                                    @click="showConfirmPass = !showConfirmPass"
                                    tabindex="-1"
                                    aria-label="Lihat konfirmasi kata sandi"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-700 focus:outline-none">
                                <i class="fa-solid text-xs" :class="showConfirmPass ? 'fa-eye-slash text-brand-600' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Security Tips Banner -->
                    <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/80 text-[11px] text-amber-800 flex items-start gap-2">
                        <i class="fa-solid fa-shield text-amber-600 text-xs mt-0.5 shrink-0"></i>
                        <span>Gunakan kombinasi minimal 8 karakter dengan huruf kapital, angka, dan simbol untuk keamanan maksimal.</span>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-key"></i>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 2: PENGATURAN WEBSITE & PERUSAHAAN -->
    <div x-show="activeTab === 'website'" x-cloak class="space-y-6">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            @foreach($settings as $group => $items)
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 uppercase tracking-wider text-xs text-brand-600 flex items-center gap-2">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Kategori Pengaturan: {{ ucfirst($group) }}</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($items as $s)
                            <div class="{{ in_array($s->key, ['hero_title', 'hero_subtitle', 'company_address']) ? 'sm:col-span-2' : '' }}">
                                <label for="setting_{{ $s->key }}" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                                    {{ $s->label ?? $s->key }}
                                </label>

                                @if(in_array($s->key, ['hero_subtitle', 'company_address']))
                                    <textarea id="setting_{{ $s->key }}" name="{{ $s->key }}" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">{{ $s->value }}</textarea>
                                @else
                                    <input type="text" id="setting_{{ $s->key }}" name="{{ $s->key }}" value="{{ $s->value }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-8 py-3 rounded-2xl bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-700 hover:to-nature-700 text-white font-bold text-sm shadow-md transition flex items-center gap-2">
                    <i class="fa-regular fa-floppy-disk"></i>
                    <span>Simpan Pengaturan Website</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
