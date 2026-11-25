@extends('layouts.admin')

@section('title', 'File Manager - Media Library')

@section('content')
<div class="space-y-6" x-data="fileManager()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-xl bg-brand-50 text-brand-600">
                    <i class="fa-solid fa-folder-open text-lg"></i>
                </span>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">File Manager & Galeri Media</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Semua foto disimpan di <code class="bg-slate-100 text-brand-700 px-1.5 py-0.5 rounded font-mono text-[11px]">public/uploads</code> dan siap digunakan di seluruh katalog tanpa unsplash.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button @click="showUploadBox = !showUploadBox"
                    class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20 transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span x-text="showUploadBox ? 'Tutup Uploader' : 'Unggah Foto Baru'">Unggah Foto Baru</span>
            </button>
            <button @click="loadFiles()"
                    :disabled="loading"
                    class="px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition flex items-center gap-2 cursor-pointer shadow-2xs">
                <i class="fa-solid fa-arrows-rotate" :class="{ 'fa-spin': loading }"></i>
            </button>
        </div>
    </div>

    <!-- Upload Panel (Collapsible / Expandable) -->
    <div x-show="showUploadBox"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white rounded-3xl border border-brand-200 p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-upload text-brand-600"></i>
                    <span>Unggah Berkas Baru ke Folder Public</span>
                </h3>
                <p class="text-xs text-slate-500">Mendukung format JPG, PNG, WEBP, GIF, SVG (Maks. 10 MB per berkas)</p>
            </div>

            <!-- Target Folder Selector -->
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-600">Simpan ke Folder:</label>
                <select x-model="uploadFolder" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="projects">Katalog Perumahan (projects)</option>
                    <option value="house_types">Tipe Rumah & Denah (house_types)</option>
                    <option value="units">Kavling & POV (units)</option>
                    <option value="siteplans">Peta Siteplan (siteplans)</option>
                    <option value="blog">Artikel Blog (blog)</option>
                    <option value="general">Umum & Pengaturan (general)</option>
                </select>
            </div>
        </div>

        <!-- Drag & Drop Box -->
        <div @dragover.prevent="isDragging = true"
             @dragleave.prevent="isDragging = false"
             @drop.prevent="handleDrop($event)"
             :class="{ 'border-brand-500 bg-brand-50/50 ring-4 ring-brand-500/10': isDragging, 'border-slate-300 bg-slate-50/60 hover:bg-slate-50': !isDragging }"
             class="border-2 border-dashed rounded-2xl p-8 text-center transition cursor-pointer flex flex-col items-center justify-center space-y-3"
             @click="$refs.fileInput.click()">
            <input type="file"
                   x-refs="fileInput"
                   @change="handleFileSelect($event)"
                   multiple
                   accept="image/*"
                   class="hidden">

            <div class="w-14 h-14 rounded-2xl bg-white shadow-xs border border-slate-200/60 flex items-center justify-center text-brand-600 text-2xl">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800">Tarik & Lepas gambar di sini, atau <span class="text-brand-600 underline">Pilih Berkas</span></p>
                <p class="text-xs text-slate-400 mt-1">Bisa pilih sekaligus banyak gambar</p>
            </div>
        </div>

        <!-- Upload Progress & Status -->
        <div x-show="uploading" class="p-4 rounded-2xl bg-brand-50 border border-brand-200 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-notch fa-spin text-brand-600"></i>
                <span class="text-xs font-semibold text-brand-900" x-text="uploadStatusText">Sedang mengunggah...</span>
            </div>
        </div>
    </div>

    <!-- Filter & Folder Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-4 shadow-2xs space-y-3">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <!-- Folder Tabs -->
            <div class="flex flex-wrap items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0">
                <template x-for="(label, key) in folders" :key="key">
                    <button type="button"
                            @click="selectFolder(key)"
                            :class="currentFolder === key ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer flex items-center gap-1.5 whitespace-nowrap">
                        <i :class="getFolderIcon(key)" class="text-[11px]"></i>
                        <span x-text="label"></span>
                        <span x-show="counts[key] !== undefined"
                              :class="currentFolder === key ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600'"
                              class="ml-1 text-[10px] px-1.5 py-0.2 rounded-full font-bold"
                              x-text="counts[key] || 0">
                        </span>
                    </button>
                </template>
            </div>

            <!-- Search Field -->
            <div class="relative w-full lg:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text"
                       x-model="searchQuery"
                       @input.debounce.300ms="filterFiles()"
                       placeholder="Cari nama berkas..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <button x-show="searchQuery"
                        @click="searchQuery = ''; filterFiles()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- File Grid -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm min-h-[350px]">
        <!-- Loading State -->
        <div x-show="loading" class="py-16 text-center text-slate-400 space-y-3">
            <i class="fa-solid fa-circle-notch fa-spin text-3xl text-brand-600"></i>
            <p class="text-xs font-semibold">Memuat daftar berkas dari public/uploads...</p>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && displayedFiles.length === 0" class="py-16 text-center text-slate-400 space-y-3">
            <div class="w-16 h-16 rounded-3xl bg-slate-100 mx-auto flex items-center justify-center text-slate-400 text-2xl">
                <i class="fa-regular fa-image"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-700">Belum ada foto di folder ini</p>
                <p class="text-xs text-slate-400 mt-0.5">Silakan unggah foto baru melalui tombol di atas.</p>
            </div>
            <button @click="showUploadBox = true" class="px-4 py-2 bg-brand-50 hover:bg-brand-100 text-brand-600 font-bold text-xs rounded-xl transition cursor-pointer">
                <i class="fa-solid fa-plus mr-1"></i> Unggah Sekarang
            </button>
        </div>

        <!-- Files Grid Cards -->
        <div x-show="!loading && displayedFiles.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <template x-for="file in displayedFiles" :key="file.path">
                <div class="group bg-slate-50 border border-slate-200/80 rounded-2xl overflow-hidden hover:border-brand-400 hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    <!-- Image Box -->
                    <div class="relative aspect-square overflow-hidden bg-slate-200/60 cursor-pointer" @click="previewFile(file)">
                        <img :src="file.url"
                             :alt="file.name"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                        <!-- Hover Overlay Actions -->
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button @click.stop="copyPath(file)"
                                    title="Salin Path Relatif"
                                    class="w-8 h-8 rounded-lg bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center shadow-xs text-xs transition cursor-pointer">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                            <button @click.stop="previewFile(file)"
                                    title="Lihat Gambar"
                                    class="w-8 h-8 rounded-lg bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center shadow-xs text-xs transition cursor-pointer">
                                <i class="fa-solid fa-expand"></i>
                            </button>
                            <button @click.stop="deleteFile(file)"
                                    title="Hapus Gambar"
                                    class="w-8 h-8 rounded-lg bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center shadow-xs text-xs transition cursor-pointer">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>

                        <!-- Dimensions / Size Badge -->
                        <span x-show="file.dimensions"
                              x-text="file.dimensions"
                              class="absolute bottom-1.5 left-1.5 bg-slate-900/70 text-white text-[9px] px-1.5 py-0.5 rounded backdrop-blur-xs font-mono font-medium">
                        </span>
                    </div>

                    <!-- Meta Info -->
                    <div class="p-2.5 space-y-1">
                        <p class="text-[11px] font-bold text-slate-800 truncate" :title="file.name" x-text="file.name"></p>
                        <div class="flex items-center justify-between text-[10px] text-slate-500">
                            <span class="px-1.5 py-0.2 rounded bg-slate-200/70 font-semibold uppercase text-[9px] text-slate-600" x-text="file.folder"></span>
                            <span x-text="file.size"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="previewModalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
         @click.self="previewModalOpen = false"
         @keydown.escape.window="previewModalOpen = false">
        <div class="bg-white rounded-3xl max-w-3xl w-full overflow-hidden shadow-2xl space-y-4 p-6" x-show="selectedPreview">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="truncate pr-4">
                    <h3 class="text-sm font-bold text-slate-900 truncate" x-text="selectedPreview?.name"></h3>
                    <p class="text-xs text-slate-400" x-text="'Folder: public/' + selectedPreview?.path"></p>
                </div>
                <button @click="previewModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Image View -->
            <div class="max-h-[60vh] bg-slate-950/5 rounded-2xl overflow-hidden flex items-center justify-center p-2">
                <img :src="selectedPreview?.url" :alt="selectedPreview?.name" class="max-h-[55vh] max-w-full object-contain rounded-xl">
            </div>

            <!-- Details & Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                <div class="text-xs text-slate-600 space-y-0.5">
                    <p>Ukuran: <strong class="text-slate-900" x-text="selectedPreview?.size"></strong> | Resolusi: <strong class="text-slate-900" x-text="selectedPreview?.dimensions || 'N/A'"></strong></p>
                    <p>Jalur: <code class="bg-slate-100 px-1.5 py-0.5 rounded font-mono text-[11px] text-brand-700" x-text="selectedPreview?.path"></code></p>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="copyPath(selectedPreview)" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-copy"></i>
                        <span>Salin Jalur</span>
                    </button>
                    <button @click="copyUrl(selectedPreview)" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-link"></i>
                        <span>Salin Full URL</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div x-show="toast.show"
         x-cloak
         x-transition
         :class="toast.type === 'error' ? 'bg-rose-600' : 'bg-emerald-600'"
         class="fixed bottom-6 right-6 z-50 text-white px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 text-xs font-bold">
        <i :class="toast.type === 'error' ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check'"></i>
        <span x-text="toast.message"></span>
    </div>
</div>

@push('scripts')
<script>
function fileManager() {
    return {
        folders: @json($folders),
        currentFolder: 'all',
        allFiles: [],
        displayedFiles: [],
        counts: {},
        searchQuery: '',
        loading: true,
        showUploadBox: false,
        isDragging: false,
        uploading: false,
        uploadFolder: 'projects',
        uploadStatusText: '',
        previewModalOpen: false,
        selectedPreview: null,
        toast: { show: false, message: '', type: 'success' },

        init() {
            this.loadFiles();
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => { this.toast.show = false; }, 3000);
        },

        async loadFiles() {
            this.loading = true;
            try {
                const res = await fetch(`{{ route('admin.file-manager.files') }}?folder=${this.currentFolder}`);
                const data = await res.json();
                if (data.status === 'success') {
                    this.allFiles = data.files;
                    this.calculateCounts(data.files);
                    this.filterFiles();
                }
            } catch (e) {
                this.showToast('Gagal memuat berkas media.', 'error');
            } finally {
                this.loading = false;
            }
        },

        calculateCounts(files) {
            const counts = { all: files.length };
            for (const f of files) {
                counts[f.folder] = (counts[f.folder] || 0) + 1;
            }
            this.counts = counts;
        },

        selectFolder(folderKey) {
            this.currentFolder = folderKey;
            if (folderKey !== 'all') {
                this.uploadFolder = folderKey;
            }
            this.filterFiles();
        },

        filterFiles() {
            let list = this.allFiles;
            if (this.currentFolder !== 'all') {
                list = list.filter(f => f.folder === this.currentFolder);
            }
            if (this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase();
                list = list.filter(f => f.name.toLowerCase().includes(q) || f.folder.toLowerCase().includes(q));
            }
            this.displayedFiles = list;
        },

        getFolderIcon(key) {
            switch (key) {
                case 'projects': return 'fa-solid fa-city';
                case 'house_types': return 'fa-solid fa-house';
                case 'units': return 'fa-solid fa-street-view';
                case 'siteplans': return 'fa-solid fa-map-location-dot';
                case 'blog': return 'fa-solid fa-newspaper';
                case 'general': return 'fa-solid fa-gear';
                default: return 'fa-solid fa-layer-group';
            }
        },

        async handleFileSelect(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                await this.uploadFiles(files);
                e.target.value = '';
            }
        },

        async handleDrop(e) {
            this.isDragging = false;
            const files = e.dataTransfer.files;
            if (files && files.length > 0) {
                await this.uploadFiles(files);
            }
        },

        async uploadFiles(fileList) {
            this.uploading = true;
            this.uploadStatusText = `Mengunggah ${fileList.length} berkas ke folder public/uploads/${this.uploadFolder}...`;

            const formData = new FormData();
            formData.append('folder', this.uploadFolder);
            for (let i = 0; i < fileList.length; i++) {
                formData.append('files[]', fileList[i]);
            }

            try {
                const res = await fetch(`{{ route('admin.file-manager.upload') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok && data.status === 'success') {
                    this.showToast(data.message, 'success');
                    await this.loadFiles();
                } else {
                    this.showToast(data.message || 'Gagal mengunggah file.', 'error');
                }
            } catch (err) {
                this.showToast('Terjadi kesalahan koneksi saat mengunggah.', 'error');
            } finally {
                this.uploading = false;
            }
        },

        previewFile(file) {
            this.selectedPreview = file;
            this.previewModalOpen = true;
        },

        copyPath(file) {
            navigator.clipboard.writeText(file.path);
            this.showToast(`Jalur disalin: ${file.path}`);
        },

        copyUrl(file) {
            navigator.clipboard.writeText(file.url);
            this.showToast(`URL disalin ke clipboard!`);
        },

        async deleteFile(file) {
            if (!confirm(`Yakin ingin menghapus foto "${file.name}" dari public/uploads?`)) {
                return;
            }

            try {
                const res = await fetch(`{{ route('admin.file-manager.delete') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ path: file.path })
                });

                const data = await res.json();
                if (res.ok && data.status === 'success') {
                    this.showToast(data.message, 'success');
                    if (this.selectedPreview && this.selectedPreview.path === file.path) {
                        this.previewModalOpen = false;
                    }
                    await this.loadFiles();
                } else {
                    this.showToast(data.message || 'Gagal menghapus file.', 'error');
                }
            } catch (err) {
                this.showToast('Gagal menghapus file.', 'error');
            }
        }
    };
}
</script>
@endpush
@endsection
