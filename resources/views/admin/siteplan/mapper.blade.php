@extends('layouts.admin')

@section('page_title', 'Visual Siteplan Slicer & Mapper: ' . $project->name)

@section('content')
@php
    $siteplanUrl = $project->siteplan_image ? asset($project->siteplan_image) : asset('storage/sample-siteplan.jpg');
@endphp

<div class="space-y-6" x-data="siteplanMapper({
    saveUrl: '{{ route('admin.projects.siteplan.save', $project) }}',
    csrf: '{{ csrf_token() }}',
    initialUnits: {{ json_encode($units->map(function($u) {
        $pov = $u->povImages->first();
        return [
            'id' => $u->id,
            'block' => $u->block,
            'number' => $u->number,
            'house_type_id' => $u->house_type_id,
            'type_name' => $u->houseType ? $u->houseType->name : '',
            'price' => $u->price,
            'price_formatted' => $u->formatted_price,
            'status' => $u->status,
            'status_label' => $u->status_label,
            'facing' => $u->facing,
            'x_pct' => $u->position_x - (($u->width_pct ?: 5) / 2),
            'y_pct' => $u->position_y - (($u->height_pct ?: 5) / 2),
            'w_pct' => $u->width_pct ?: 5,
            'h_pct' => $u->height_pct ?: 5,
            'slice_image' => $u->slice_image_url,
            'pov_description' => $u->pov_description,
            'pov_photo' => $pov ? $pov->url : '',
        ];
    })) }}
})">

    <!-- Header & Action Controls -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.projects.index') }}" class="hover:underline">Perumahan</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">{{ $project->name }}</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900">Interactive Siteplan Slicer & POV Mapper</h2>
            <p class="text-xs text-slate-500 mt-1">
                Upload peta siteplan, klik & seret untuk memotong arsiran kavling, atau gunakan deteksi otomatis untuk membuat kavling siap klik.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Upload Siteplan Modal Button -->
            <button type="button" @click="showUploadModal = true" class="px-4 py-2.5 rounded-xl border border-slate-300 hover:border-brand-500 bg-white text-slate-700 font-semibold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-brand-600"></i>
                <span>Ganti Gambar Siteplan</span>
            </button>

            <!-- Auto Slice Action Form -->
            <form action="{{ route('admin.projects.siteplan.auto-slice', $project) }}" method="POST" onsubmit="return confirm('Sistem akan melakukan auto-slice pada seluruh blok arsiran siteplan (seperti Blok A, B, C, D, E) dan men-generate kavling ber-POV. Lanjutkan?')">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-700 hover:to-nature-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>⚡ Otomatis Slice Semua Arsiran Kavling</span>
                </button>
            </form>

            <!-- Reset Slices Action Form -->
            <form action="{{ route('admin.projects.siteplan.reset', $project) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset seluruh potongan arsiran kavling pada siteplan ini? Tindakan ini akan menghapus semua kavling dan file potongan arsiran yang telah dibuat agar Anda bisa memulai dari awal.')">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs shadow-xs transition flex items-center gap-1.5" title="Hapus semua potongan kavling dan mulai ulang">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                    <span>Reset Slice Map</span>
                </button>
            </form>

            <a href="{{ route('projects.show', $project->slug) }}#siteplan" target="_blank" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition" title="Preview Tampilan Pengunjung">
                <i class="fa-solid fa-eye"></i>
            </a>
        </div>
    </div>

    <!-- Main Workspace: Canvas + Editor Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Interactive Canvas Slicer Area (8 cols) -->
        <div class="lg:col-span-8 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-map text-brand-500"></i>
                        <span>Kanvas Siteplan</span>
                    </span>
                    <span class="text-[11px] text-slate-400">| Mode: <strong>Klik & Tarik Kursor</strong> pada area unit</span>
                </div>
                <div class="flex items-center gap-3 text-[11px] font-semibold">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Tersedia</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Booking</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Terjual</span>
                </div>
            </div>

            <!-- Siteplan Interactive Container -->
            <div class="relative rounded-2xl overflow-hidden bg-slate-900 border-2 border-slate-200 select-none shadow-inner cursor-crosshair aspect-[16/11.37]"
                 x-ref="siteplanContainer"
                 @mousedown="startDrag($event)"
                 @mousemove="onDrag($event)"
                 @mouseup="endDrag($event)">

                <!-- Background Image -->
                <img x-ref="siteplanImg"
                     src="{{ $siteplanUrl }}"
                     alt="Siteplan"
                     class="w-full h-full object-cover pointer-events-none"
                     @load="onImageLoaded()">

                <!-- SVG Interactive Layer for Sliced Lots -->
                <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <!-- Sliced Units Rectangles/Polygons -->
                    <template x-for="unit in units" :key="unit.id">
                        <g class="pointer-events-auto cursor-pointer group" @click.stop="selectUnit(unit)">
                            <rect :x="unit.x_pct"
                                  :y="unit.y_pct"
                                  :width="unit.w_pct"
                                  :height="unit.h_pct"
                                  rx="0.5"
                                  :class="{
                                      'fill-emerald-500/40 stroke-emerald-500 hover:fill-emerald-500/70': unit.status === 'available',
                                      'fill-amber-500/40 stroke-amber-500 hover:fill-amber-500/70': unit.status === 'booked',
                                      'fill-rose-500/40 stroke-rose-500 hover:fill-rose-500/70': unit.status === 'sold',
                                      'stroke-2 ring-2 ring-white': selectedUnit && selectedUnit.id === unit.id
                                  }"
                                  stroke-width="0.3"
                                  class="transition-all duration-200" />
                            <!-- Unit Number Label -->
                            <text :x="unit.x_pct + (unit.w_pct / 2)"
                                  :y="unit.y_pct + (unit.h_pct / 2) + 0.8"
                                  text-anchor="middle"
                                  font-size="1.8"
                                  font-weight="900"
                                  fill="#ffffff"
                                  class="pointer-events-none select-none drop-shadow"
                                  x-text="unit.block + unit.number">
                            </text>
                        </g>
                    </template>

                    <!-- Active Drag Selection Box -->
                    <rect x-show="isDragging"
                          :x="dragRect.x"
                          :y="dragRect.y"
                          :width="dragRect.w"
                          :height="dragRect.h"
                          fill="rgba(14, 165, 233, 0.35)"
                          stroke="#0ea5e9"
                          stroke-width="0.5"
                          stroke-dasharray="1 1" />
                </svg>
            </div>

            <!-- Instruction Footer -->
            <p class="text-[11px] text-slate-500 flex items-center justify-between">
                <span>💡 <strong>Tips:</strong> Klik & seret kotak di atas salah satu kavling untuk memotong potongan baru, atau klik kavling yang ada untuk mengedit datanya.</span>
                <span class="font-bold text-slate-700"><span x-text="units.length"></span> Kavling Terpetakan</span>
            </p>
        </div>

        <!-- Right Side: Lot Slicer Editor Form (4 cols) -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6 sticky top-24">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-sm">
                    <span x-text="form.unit_id ? 'Edit Kavling ' + form.block + '-' + form.number : 'Data Potongan Kavling Baru'"></span>
                </h3>
                <button type="button" @click="resetForm()" class="text-xs text-brand-600 hover:underline">
                    + Buat Baru
                </button>
            </div>

            <!-- Sliced Lot Thumbnail Preview -->
            <div>
                <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Hasil Potongan Arsiran (Slice)</label>
                <div class="h-28 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center relative">
                    <template x-if="form.slice_preview">
                        <img :src="form.slice_preview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!form.slice_preview">
                        <span class="text-xs text-slate-400 text-center px-4">Seret kotak pada peta atau pilih kavling untuk melihat potongan.</span>
                    </template>
                </div>
            </div>

            <!-- Form Inputs -->
            <form @submit.prevent="submitSlice()" class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Blok *</label>
                        <input type="text" x-model="form.block" required placeholder="A, B, C..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">No. Kavling *</label>
                        <input type="text" x-model="form.number" required placeholder="01, 02, Hook..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Tipe Rumah *</label>
                    <select x-model="form.house_type_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="">Pilih Tipe</option>
                        @foreach($houseTypes as $ht)
                            <option value="{{ $ht->id }}">{{ $ht->name }} - {{ $ht->formatted_price }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Status Unit *</label>
                        <select x-model="form.status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="available">🟢 Tersedia</option>
                            <option value="booked">🟡 Booking</option>
                            <option value="sold">🔴 Terjual</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Arah Hadap</label>
                        <input type="text" x-model="form.facing" placeholder="Timur, Utara..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <!-- POV Real Photo & Description -->
                <div class="p-3.5 rounded-2xl bg-nature-50 border border-nature-200/80 space-y-3">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-nature-800 block">
                        <i class="fa-solid fa-street-view mr-1"></i> Data POV (Point of View) Nyata
                    </span>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-600 mb-1">URL Foto POV Nyata</label>
                        <input type="text" x-model="form.pov_photo_url" placeholder="https://images.unsplash.com/..." class="w-full bg-white border border-nature-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-600 mb-1">Deskripsi Pemandangan POV</label>
                        <textarea x-model="form.pov_description" rows="2" placeholder="Menghadap taman tematik & gazebo..." class="w-full bg-white border border-nature-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-800 focus:outline-none"></textarea>
                    </div>
                </div>

                <button type="submit"
                        :disabled="isSubmitting"
                        class="w-full py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Kavling & Potongan'"></span>
                </button>
            </form>
        </div>
    </div>

    <!-- Upload Siteplan Modal -->
    <div x-show="showUploadModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-xs">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full border border-slate-200 shadow-2xl" @click.away="showUploadModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-900 text-sm">Unggah File Siteplan Baru</h3>
                <button @click="showUploadModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.projects.siteplan.upload', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Pilih Gambar Siteplan (JPG / PNG)</label>
                    <input type="file" name="siteplan_file" required accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="showUploadModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 text-white font-bold text-xs shadow-md hover:bg-brand-700">Unggah Gambar</button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function siteplanMapper(config) {
    return {
        saveUrl: config.saveUrl,
        csrf: config.csrf,
        units: config.initialUnits || [],
        selectedUnit: null,
        showUploadModal: false,
        isDragging: false,
        isSubmitting: false,
        startPos: { x: 0, y: 0 },
        dragRect: { x: 0, y: 0, w: 0, h: 0 },
        form: {
            unit_id: null,
            block: 'A',
            number: '01',
            house_type_id: '',
            status: 'available',
            facing: '',
            price: 0,
            x_pct: 10,
            y_pct: 35,
            w_pct: 6,
            h_pct: 6,
            slice_preview: '',
            slice_data_base64: '',
            pov_description: '',
            pov_photo_url: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80',
        },

        init() {
            if (this.units.length > 0) {
                this.selectUnit(this.units[0]);
            }
        },

        onImageLoaded() {
            // ready
        },

        selectUnit(u) {
            this.selectedUnit = u;
            this.form.unit_id = u.id;
            this.form.block = u.block;
            this.form.number = u.number;
            this.form.house_type_id = u.house_type_id;
            this.form.status = u.status;
            this.form.facing = u.facing || '';
            this.form.price = u.price || 0;
            this.form.x_pct = u.x_pct;
            this.form.y_pct = u.y_pct;
            this.form.w_pct = u.w_pct;
            this.form.h_pct = u.h_pct;
            this.form.slice_preview = u.slice_image || '';
            this.form.slice_data_base64 = '';
            this.form.pov_description = u.pov_description || '';
            this.form.pov_photo_url = u.pov_photo || '';
        },

        resetForm() {
            this.selectedUnit = null;
            this.form.unit_id = null;
            this.form.block = 'A';
            this.form.number = (this.units.length + 1).toString().padStart(2, '0');
            this.form.status = 'available';
            this.form.slice_preview = '';
            this.form.slice_data_base64 = '';
        },

        startDrag(e) {
            const rect = this.$refs.siteplanContainer.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            this.startPos = { x, y };
            this.dragRect = { x, y, w: 0, h: 0 };
            this.isDragging = true;
        },

        onDrag(e) {
            if (!this.isDragging) return;
            const rect = this.$refs.siteplanContainer.getBoundingClientRect();
            const curX = ((e.clientX - rect.left) / rect.width) * 100;
            const curY = ((e.clientY - rect.top) / rect.height) * 100;

            const x = Math.min(this.startPos.x, curX);
            const y = Math.min(this.startPos.y, curY);
            const w = Math.abs(curX - this.startPos.x);
            const h = Math.abs(curY - this.startPos.y);

            this.dragRect = { x, y, w, h };
        },

        endDrag(e) {
            if (!this.isDragging) return;
            this.isDragging = false;

            if (this.dragRect.w > 1 && this.dragRect.h > 1) {
                this.selectedUnit = null;
                this.form.unit_id = null;
                this.form.x_pct = parseFloat(this.dragRect.x.toFixed(2));
                this.form.y_pct = parseFloat(this.dragRect.y.toFixed(2));
                this.form.w_pct = parseFloat(this.dragRect.w.toFixed(2));
                this.form.h_pct = parseFloat(this.dragRect.h.toFixed(2));

                // Client-side crop using canvas to preview slice!
                this.generateClientSlicePreview();
            }
        },

        generateClientSlicePreview() {
            const img = this.$refs.siteplanImg;
            if (!img) return;

            const canvas = document.createElement('canvas');
            const naturalW = img.naturalWidth || 1600;
            const naturalH = img.naturalHeight || 1137;

            const cropX = (this.form.x_pct / 100) * naturalW;
            const cropY = (this.form.y_pct / 100) * naturalH;
            const cropW = (this.form.w_pct / 100) * naturalW;
            const cropH = (this.form.h_pct / 100) * naturalH;

            canvas.width = Math.max(10, cropW);
            canvas.height = Math.max(10, cropH);

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, cropX, cropY, cropW, cropH, 0, 0, canvas.width, canvas.height);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            this.form.slice_preview = dataUrl;
            this.form.slice_data_base64 = dataUrl;
        },

        submitSlice() {
            if (!this.form.house_type_id) {
                alert('Silakan pilih tipe rumah terlebih dahulu.');
                return;
            }

            this.isSubmitting = true;

            fetch(this.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(this.form)
            })
            .then(res => res.json())
            .then(data => {
                this.isSubmitting = false;
                if (data.success) {
                    const u = data.unit;
                    const pov = u.pov_images && u.pov_images[0] ? u.pov_images[0].path : this.form.pov_photo_url;
                    const item = {
                        id: u.id,
                        block: u.block,
                        number: u.number,
                        house_type_id: u.house_type_id,
                        type_name: u.house_type ? u.house_type.name : '',
                        price: u.price,
                        status: u.status,
                        status_label: u.status === 'available' ? 'Tersedia' : (u.status === 'booked' ? 'Booking' : 'Terjual'),
                        facing: u.facing,
                        x_pct: this.form.x_pct,
                        y_pct: this.form.y_pct,
                        w_pct: this.form.w_pct,
                        h_pct: this.form.h_pct,
                        slice_image: u.slice_image ? '/storage/' + u.slice_image : this.form.slice_preview,
                        pov_description: u.pov_description,
                        pov_photo: pov,
                    };

                    const idx = this.units.findIndex(x => x.id === u.id);
                    if (idx >= 0) {
                        this.units[idx] = item;
                    } else {
                        this.units.push(item);
                    }
                    this.selectUnit(item);
                    alert('Kavling berhasil disimpan dan terpetakan di siteplan!');
                } else {
                    alert('Gagal menyimpan: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(err => {
                this.isSubmitting = false;
                alert('Terjadi kesalahan koneksi.');
            });
        }
    }
}
</script>
@endpush
@endsection
