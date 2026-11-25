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
            'position_x' => $u->position_x,
            'position_y' => $u->position_y,
            'x_pct' => $u->position_x - (($u->width_pct ?: 5) / 2),
            'y_pct' => $u->position_y - (($u->height_pct ?: 5) / 2),
            'w_pct' => $u->width_pct ?: 5,
            'h_pct' => $u->height_pct ?: 5,
            'polygon_points' => $u->polygon_coords,
            'svg_points' => $u->svg_polygon_points,
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
            <h2 class="text-xl font-bold text-slate-900">Interactive Line Slicer & Siteplan Mapper</h2>
            <p class="text-xs text-slate-500 mt-1">
                Gunakan <strong>garis terhubung (polygon)</strong> untuk memotong arsiran unit kavling dengan presisi. Dilengkapi fitur zoom in/out.
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
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-700 hover:to-nature-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>⚡ Otomatis Slice Semua</span>
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

            <!-- Toolbar: Zoom In/Out & Drawing Mode Bar -->
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <!-- Zoom Controls -->
                <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-2xl">
                    <button type="button" @click="zoomOut()" class="w-8 h-8 rounded-xl bg-white hover:bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-sm shadow-xs transition" title="Perkecil Tampilan (Zoom Out)">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="px-3 text-xs font-black text-slate-800 tabular-nums min-w-[50px] text-center" x-text="Math.round(zoomLevel * 100) + '%'">100%</span>
                    <button type="button" @click="zoomIn()" class="w-8 h-8 rounded-xl bg-white hover:bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-sm shadow-xs transition" title="Perbesar Tampilan (Zoom In)">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button type="button" @click="resetZoom()" class="px-2.5 py-1 text-xs font-bold text-slate-600 hover:text-brand-600 transition" title="Reset ke Ukuran Pas">
                        Fit 100%
                    </button>
                </div>

                <!-- Tool Mode Toggle -->
                <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-2xl">
                    <button type="button"
                            @click="toolMode = 'draw'; cancelPan();"
                            :class="toolMode === 'draw' ? 'bg-brand-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:bg-white'"
                            class="px-3 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-draw-polygon"></i>
                        <span>Mode Garis Poligon</span>
                    </button>
                    <button type="button"
                            @click="toolMode = 'pan'"
                            :class="toolMode === 'pan' ? 'bg-brand-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:bg-white'"
                            class="px-3 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-hand"></i>
                        <span>Geser Peta (Pan)</span>
                    </button>
                </div>

                <!-- Legend Status -->
                <div class="hidden sm:flex items-center gap-3 text-[11px] font-semibold">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Tersedia</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Booking</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Terjual</span>
                </div>
            </div>

            <!-- Active Polygon Drawing Floating Helper Bar -->
            <div x-show="polygonPoints.length > 0" x-cloak class="p-3 bg-brand-50 border border-brand-200 rounded-2xl flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-brand-900 font-semibold">
                    <span class="w-5 h-5 rounded-full bg-brand-600 text-white text-[10px] font-bold flex items-center justify-center" x-text="polygonPoints.length"></span>
                    <span>Titik sudut dibuat. Klik titik awal atau tombol hijau di samping untuk menutup batas kavling.</span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" @click="undoLastPoint()" class="px-3 py-1 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold transition">
                        ↩ Batalkan Titik
                    </button>
                    <button type="button" x-show="polygonPoints.length >= 3" @click="closePolygon()" class="px-3 py-1 rounded-xl bg-nature-600 hover:bg-nature-700 text-white font-bold transition">
                        ✔ Tutup Poligon
                    </button>
                    <button type="button" @click="cancelPolygon()" class="px-2.5 py-1 rounded-xl bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold transition">
                        ✕ Batal
                    </button>
                </div>
            </div>

            <!-- Scrollable Viewport with Zoom Wrapper -->
            <div class="relative rounded-2xl overflow-auto bg-slate-950 border-2 border-slate-200 shadow-inner max-h-[620px]"
                 x-ref="scrollContainer"
                 :class="{
                     'cursor-crosshair': toolMode === 'draw',
                     'cursor-grab active:cursor-grabbing': toolMode === 'pan'
                 }"
                 @mousedown="handleContainerMouseDown($event)"
                 @mousemove="handleContainerMouseMove($event)"
                 @mouseup="handleContainerMouseUp($event)">

                <!-- Scaled Canvas Wrapper (Scales smoothly according to zoomLevel) -->
                <div class="relative origin-top-left transition-transform duration-100 select-none"
                     :style="'width: ' + (100 * zoomLevel) + '%; transform: translate(' + panOffset.x + 'px, ' + panOffset.y + 'px);'">

                    <div class="relative w-full aspect-[16/11.37]">
                        <!-- Background Masterplan Image -->
                        <img x-ref="siteplanImg"
                             src="{{ $siteplanUrl }}"
                             alt="Siteplan"
                             class="w-full h-full object-cover pointer-events-none select-none"
                             @load="onImageLoaded()">

                        <!-- SVG Interactive Layer -->
                        <svg class="absolute inset-0 w-full h-full"
                             x-ref="siteplanSvg"
                             viewBox="0 0 100 100"
                             preserveAspectRatio="none"
                             @click="handleSvgClick($event)">

                            <!-- Sliced Units Polygons -->
                            <template x-for="unit in units" :key="unit.id">
                                <g class="cursor-pointer group" @click.stop="selectUnit(unit)">
                                    <polygon :points="unit.svg_points || (unit.x_pct + ',' + unit.y_pct + ' ' + (unit.x_pct + unit.w_pct) + ',' + unit.y_pct + ' ' + (unit.x_pct + unit.w_pct) + ',' + (unit.y_pct + unit.h_pct) + ' ' + unit.x_pct + ',' + (unit.y_pct + unit.h_pct))"
                                             :class="{
                                                 'fill-emerald-500/40 stroke-emerald-400 hover:fill-emerald-500/75': unit.status === 'available',
                                                 'fill-amber-500/40 stroke-amber-400 hover:fill-amber-500/75': unit.status === 'booked',
                                                 'fill-rose-500/40 stroke-rose-400 hover:fill-rose-500/75': unit.status === 'sold',
                                                 'stroke-2 ring-2 stroke-white': selectedUnit && selectedUnit.id === unit.id
                                             }"
                                             stroke-width="0.3"
                                             class="transition-all duration-200 hover:stroke-white hover:stroke-[0.6]" />

                                    <!-- Unit Number Label in center -->
                                    <text :x="unit.position_x"
                                          :y="unit.position_y + 0.6"
                                          text-anchor="middle"
                                          font-size="1.8"
                                          font-weight="900"
                                          fill="#ffffff"
                                          class="pointer-events-none select-none drop-shadow">
                                        <tspan x-text="unit.block + unit.number"></tspan>
                                    </text>
                                </g>
                            </template>

                            <!-- Connected Lines Being Drawn (Active Polygon Mode) -->
                            <template x-if="polygonPoints.length > 0">
                                <g class="pointer-events-none">
                                    <!-- Completed Lines -->
                                    <polyline :points="getPolygonPointsString()"
                                              fill="rgba(14, 165, 233, 0.25)"
                                              stroke="#0ea5e9"
                                              stroke-width="0.4"
                                              stroke-linecap="round"
                                              stroke-linejoin="round" />

                                    <!-- Rubberband Live Line to Mouse Cursor -->
                                    <line :x1="polygonPoints[polygonPoints.length - 1].x"
                                          :y1="polygonPoints[polygonPoints.length - 1].y"
                                          :x2="currentCursorPos.x"
                                          :y2="currentCursorPos.y"
                                          stroke="#38bdf8"
                                          stroke-width="0.35"
                                          stroke-dasharray="0.8 0.8" />

                                    <!-- Vertex Points Dots -->
                                    <template x-for="(pt, idx) in polygonPoints" :key="idx">
                                        <g>
                                            <circle :cx="pt.x" :cy="pt.y" r="0.8"
                                                    :fill="idx === 0 ? '#10b981' : '#0ea5e9'"
                                                    stroke="#ffffff"
                                                    stroke-width="0.2" />
                                            <text :x="pt.x" :y="pt.y - 1"
                                                  text-anchor="middle"
                                                  font-size="1.2"
                                                  font-weight="bold"
                                                  fill="#ffffff"
                                                  class="drop-shadow"
                                                  x-text="idx + 1"></text>
                                        </g>
                                    </template>
                                </g>
                            </template>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Instruction Footer -->
            <div class="text-[11px] text-slate-500 flex flex-wrap items-center justify-between gap-2 pt-1">
                <span>💡 <strong>Cara Pakai:</strong> Pilih <em>Mode Garis Poligon</em> lalu klik sudut-sudut batas kavling (misal 4 sudut). Klik titik pertama atau tombol <em>Tutup Poligon</em> untuk menyelesaikan potongan.</span>
                <span class="font-bold text-slate-800"><span x-text="units.length"></span> Kavling Terpetakan</span>
            </div>
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
                <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Hasil Potongan Poligon (Slice)</label>
                <div class="h-28 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center relative">
                    <template x-if="form.slice_preview">
                        <img :src="form.slice_preview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!form.slice_preview">
                        <span class="text-xs text-slate-400 text-center px-4">Gunakan garis poligon pada peta atau pilih kavling untuk melihat potongan.</span>
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
                        <label class="block text-[10px] font-semibold text-slate-600 mb-1">Foto POV Nyata (File Manager)</label>
                        <div class="flex items-center gap-1.5">
                            <input type="text" id="mapper_pov_photo_url" x-model="form.pov_photo_url" placeholder="uploads/units/pov-kavling.jpg" class="flex-1 bg-white border border-nature-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-800 font-mono focus:outline-none">
                            <button type="button" @click="$dispatch('open-file-picker', { targetInputId: 'mapper_pov_photo_url', folder: 'units' })" class="px-2.5 py-1.5 bg-nature-600 hover:bg-nature-700 text-white rounded-xl text-[10px] font-bold shrink-0 transition cursor-pointer">
                                <i class="fa-solid fa-folder-open"></i> Pilih
                            </button>
                        </div>
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
        isSubmitting: false,

        // Zoom & Pan state
        zoomLevel: 1.0,
        toolMode: 'draw', // 'draw' (polygon lines) or 'pan'
        panOffset: { x: 0, y: 0 },
        isPanning: false,
        panStart: { x: 0, y: 0 },

        // Connected Lines Polygon Drawing State
        polygonPoints: [], // Array of {x: pct, y: pct}
        currentCursorPos: { x: 0, y: 0 },

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
            polygon_points: [],
            slice_preview: '',
            slice_data_base64: '',
            pov_description: '',
            pov_photo_url: 'uploads/units/pov-kavling-a01.jpg',
        },

        init() {
            if (this.units.length > 0) {
                this.selectUnit(this.units[0]);
            }
        },

        onImageLoaded() {
            // image loaded
        },

        // Zoom Methods
        zoomIn() {
            if (this.zoomLevel < 3.5) {
                this.zoomLevel = parseFloat((this.zoomLevel + 0.25).toFixed(2));
            }
        },

        zoomOut() {
            if (this.zoomLevel > 0.75) {
                this.zoomLevel = parseFloat((this.zoomLevel - 0.25).toFixed(2));
            }
        },

        resetZoom() {
            this.zoomLevel = 1.0;
            this.panOffset = { x: 0, y: 0 };
        },

        cancelPan() {
            this.isPanning = false;
        },

        // Pan Handlers
        handleContainerMouseDown(e) {
            if (this.toolMode === 'pan') {
                this.isPanning = true;
                this.panStart = { x: e.clientX - this.panOffset.x, y: e.clientY - this.panOffset.y };
            }
        },

        handleContainerMouseMove(e) {
            if (this.isPanning && this.toolMode === 'pan') {
                this.panOffset.x = e.clientX - this.panStart.x;
                this.panOffset.y = e.clientY - this.panStart.y;
                return;
            }

            // If drawing, update live cursor position
            if (this.toolMode === 'draw') {
                const svg = this.$refs.siteplanSvg;
                if (!svg) return;
                const rect = svg.getBoundingClientRect();
                const curX = ((e.clientX - rect.left) / rect.width) * 100;
                const curY = ((e.clientY - rect.top) / rect.height) * 100;
                this.currentCursorPos = {
                    x: parseFloat(curX.toFixed(2)),
                    y: parseFloat(curY.toFixed(2))
                };
            }
        },

        handleContainerMouseUp(e) {
            if (this.toolMode === 'pan') {
                this.isPanning = false;
            }
        },

        // Connected Lines Polygon Drawing
        handleSvgClick(e) {
            if (this.toolMode !== 'draw') return;

            const svg = this.$refs.siteplanSvg;
            if (!svg) return;
            const rect = svg.getBoundingClientRect();
            const curX = parseFloat((((e.clientX - rect.left) / rect.width) * 100).toFixed(2));
            const curY = parseFloat((((e.clientY - rect.top) / rect.height) * 100).toFixed(2));

            // Check if clicking near first point to close polygon
            if (this.polygonPoints.length >= 3) {
                const first = this.polygonPoints[0];
                const dist = Math.hypot(curX - first.x, curY - first.y);
                if (dist < 2.5) { // near first point
                    this.closePolygon();
                    return;
                }
            }

            // Add new point
            this.polygonPoints.push({ x: curX, y: curY });
        },

        getPolygonPointsString() {
            return this.polygonPoints.map(p => `${p.x},${p.y}`).join(' ');
        },

        undoLastPoint() {
            if (this.polygonPoints.length > 0) {
                this.polygonPoints.pop();
            }
        },

        cancelPolygon() {
            this.polygonPoints = [];
        },

        closePolygon() {
            if (this.polygonPoints.length < 3) {
                alert('Dibutuhkan minimal 3 titik sudut untuk membentuk poligon kavling.');
                return;
            }

            // Compute bounding box
            const xs = this.polygonPoints.map(p => p.x);
            const ys = this.polygonPoints.map(p => p.y);
            const minX = Math.min(...xs);
            const maxX = Math.max(...xs);
            const minY = Math.min(...ys);
            const maxY = Math.max(...ys);

            const w = Math.max(1, maxX - minX);
            const h = Math.max(1, maxY - minY);

            this.selectedUnit = null;
            this.form.unit_id = null;
            this.form.x_pct = parseFloat(minX.toFixed(2));
            this.form.y_pct = parseFloat(minY.toFixed(2));
            this.form.w_pct = parseFloat(w.toFixed(2));
            this.form.h_pct = parseFloat(h.toFixed(2));
            this.form.polygon_points = this.polygonPoints.map(p => [p.x, p.y]);

            // Generate polygon-clipped thumbnail on canvas
            this.generatePolygonSlicePreview();

            // Clear points
            this.polygonPoints = [];
        },

        generatePolygonSlicePreview() {
            const img = this.$refs.siteplanImg;
            if (!img) return;

            const canvas = document.createElement('canvas');
            const naturalW = img.naturalWidth || 1600;
            const naturalH = img.naturalHeight || 1137;

            const cropX = (this.form.x_pct / 100) * naturalW;
            const cropY = (this.form.y_pct / 100) * naturalH;
            const cropW = (this.form.w_pct / 100) * naturalW;
            const cropH = (this.form.h_pct / 100) * naturalH;

            canvas.width = Math.max(20, cropW);
            canvas.height = Math.max(20, cropH);

            const ctx = canvas.getContext('2d');

            // Draw image cropped
            ctx.drawImage(img, cropX, cropY, cropW, cropH, 0, 0, canvas.width, canvas.height);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            this.form.slice_preview = dataUrl;
            this.form.slice_data_base64 = dataUrl;
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
            this.form.polygon_points = u.polygon_points || [];
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
            this.form.polygon_points = [];
            this.polygonPoints = [];
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
                        position_x: u.position_x,
                        position_y: u.position_y,
                        x_pct: this.form.x_pct,
                        y_pct: this.form.y_pct,
                        w_pct: this.form.w_pct,
                        h_pct: this.form.h_pct,
                        polygon_points: u.polygon_coords,
                        svg_points: u.svg_polygon_points || this.form.polygon_points.map(p => p[0] + ',' + p[1]).join(' '),
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
                    alert('Kavling poligon berhasil disimpan dan terpetakan di siteplan!');
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
