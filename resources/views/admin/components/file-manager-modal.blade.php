<!-- Global File Manager Picker Modal (Stored in public/uploads) -->
<div x-data="globalFilePicker()"
     @open-file-picker.window="openPicker($event.detail)"
     x-show="isOpen"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
     @click.self="closePicker()"
     @keydown.escape.window="closePicker()">

    <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden shadow-2xl border border-slate-200"
         x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-brand-50 border border-brand-200/60 flex items-center justify-center text-brand-600 text-lg shrink-0">
                    <i class="fa-solid fa-photo-film"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight">File Manager - Pilih Foto</h3>
                    <p class="text-xs text-slate-500">Pilih dari folder <code class="bg-slate-200/70 text-brand-700 px-1 py-0.2 rounded font-mono text-[10px]">public/uploads</code> atau unggah langsung.</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button"
                        @click="showUpload = !showUpload"
                        class="px-3.5 py-2 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                    <span x-text="showUpload ? 'Daftar Foto' : '+ Unggah Baru'"></span>
                </button>
                <button type="button" @click="closePicker()" class="w-8 h-8 rounded-xl hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Quick Upload Area (Collapsible) -->
        <div x-show="showUpload"
             x-transition
             class="p-4 bg-brand-50/50 border-b border-brand-100 space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-brand-900">Pilih Folder Tujuan Unggah:</span>
                    <select x-model="uploadTargetFolder" class="bg-white border border-brand-200 rounded-xl px-2.5 py-1 text-xs text-brand-800 font-bold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="units">public/uploads/units (Kavling & POV)</option>
                        <option value="house_types">public/uploads/house_types (Tipe & Denah)</option>
                        <option value="projects">public/uploads/projects (Perumahan & Fasad)</option>
                        <option value="siteplans">public/uploads/siteplans (Peta Masterplan)</option>
                        <option value="blog">public/uploads/blog (Artikel)</option>
                        <option value="general">public/uploads/general (Umum)</option>
                    </select>
                </div>
                <span class="text-[11px] text-slate-500">JPG, PNG, WEBP, GIF (Maks 10MB)</span>
            </div>

            <div @dragover.prevent="isDragging = true"
                 @dragleave.prevent="isDragging = false"
                 @drop.prevent="handleDrop($event)"
                 :class="{ 'border-brand-500 bg-white ring-2 ring-brand-500/20': isDragging, 'border-slate-300 bg-white/70 hover:bg-white': !isDragging }"
                 class="border-2 border-dashed rounded-2xl p-4 text-center cursor-pointer transition flex items-center justify-center gap-3"
                 @click="$refs.quickFileInput.click()">
                <input type="file"
                       x-refs="quickFileInput"
                       @change="handleFileSelect($event)"
                       multiple
                       accept="image/*"
                       class="hidden">
                <i class="fa-solid fa-cloud-arrow-up text-brand-600 text-xl"></i>
                <div class="text-left">
                    <p class="text-xs font-bold text-slate-800">Klik di sini atau tarik file ke area ini</p>
                    <p class="text-[10px] text-slate-400">File langsung tersimpan ke folder yang Anda pilih</p>
                </div>
            </div>

            <div x-show="uploading" class="text-xs font-semibold text-brand-700 flex items-center gap-2">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
                <span>Mengunggah file ke server...</span>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="px-6 py-3 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
            <!-- Folder Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                <template x-for="(label, key) in folders" :key="key">
                    <button type="button"
                            @click="selectFolder(key)"
                            :class="currentFolder === key ? 'bg-brand-600 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold'"
                            class="px-2.5 py-1 rounded-lg text-xs transition cursor-pointer whitespace-nowrap">
                        <span x-text="label"></span>
                    </button>
                </template>
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-56">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text"
                       x-model="search"
                       @input.debounce.250ms="filterFiles()"
                       placeholder="Cari foto..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
        </div>

        <!-- File Grid Body -->
        <div class="p-6 overflow-y-auto flex-1 min-h-[300px] max-h-[50vh] bg-slate-50/40">
            <!-- Loading -->
            <div x-show="loading" class="py-16 text-center text-slate-400">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl text-brand-600"></i>
                <p class="text-xs mt-2 font-medium">Memuat berkas...</p>
            </div>

            <!-- Empty -->
            <div x-show="!loading && displayedFiles.length === 0" class="py-16 text-center text-slate-400">
                <i class="fa-regular fa-image text-3xl mb-2 text-slate-300"></i>
                <p class="text-xs font-semibold text-slate-600">Tidak ada foto ditemukan</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Silakan unggah foto baru melalui tombol di atas.</p>
            </div>

            <!-- Grid -->
            <div x-show="!loading && displayedFiles.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
                <template x-for="file in displayedFiles" :key="file.path">
                    <div @click="selectFile(file)"
                         @dblclick="selectFile(file); applySelection();"
                         :title="'Klik untuk memilih, klik 2x untuk langsung gunakan: ' + file.name"
                         :class="selectedFile?.path === file.path ? 'ring-3 ring-brand-500 border-brand-500 shadow-md bg-brand-50/30' : 'border-slate-200 hover:border-brand-300 hover:shadow-xs bg-white'"
                         class="border rounded-2xl p-2 cursor-pointer transition flex flex-col justify-between group relative select-none">

                        <!-- Checkmark if selected -->
                        <div x-show="selectedFile?.path === file.path" class="absolute top-3 right-3 z-10 w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs shadow-md">
                            <i class="fa-solid fa-check"></i>
                        </div>

                        <!-- Image -->
                        <div class="aspect-square rounded-xl overflow-hidden bg-slate-100 relative">
                            <img :src="file.url" :alt="file.name" loading="lazy" class="w-full h-full object-cover">
                            <span x-show="file.dimensions" x-text="file.dimensions" class="absolute bottom-1 left-1 bg-slate-900/70 text-white text-[8px] font-mono px-1 py-0.2 rounded"></span>
                        </div>

                        <!-- Info -->
                        <div class="mt-2 space-y-0.5">
                            <p class="text-[11px] font-bold text-slate-800 truncate" :title="file.name" x-text="file.name"></p>
                            <div class="flex items-center justify-between text-[10px] text-slate-500">
                                <span class="uppercase text-[9px] font-semibold text-slate-400" x-text="file.folder"></span>
                                <span x-text="file.size"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="text-xs text-slate-600 truncate">
                <template x-if="selectedFile">
                    <div class="flex items-center gap-2 truncate">
                        <img :src="selectedFile.url" class="w-7 h-7 rounded-lg object-cover border border-slate-200 shrink-0">
                        <div class="truncate">
                            <span class="font-bold text-slate-800 truncate block text-[11px]" x-text="selectedFile.name"></span>
                            <span class="text-[10px] text-brand-600 font-mono truncate" x-text="selectedFile.path"></span>
                        </div>
                    </div>
                </template>
                <template x-if="!selectedFile">
                    <span class="text-slate-400 italic">Pilih foto dari galeri di atas...</span>
                </template>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" @click="closePicker()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer">
                    Batal
                </button>
                <button type="button"
                        :disabled="!selectedFile"
                        @click="applySelection()"
                        class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs shadow-md shadow-brand-500/20 transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-check"></i>
                    <span>Gunakan Foto Ini</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function globalFilePicker() {
    return {
        isOpen: false,
        targetInputId: null,
        targetPreviewId: null,
        currentFolder: 'all',
        folders: {
            all: 'Semua',
            projects: 'Perumahan',
            house_types: 'Tipe & Denah',
            units: 'Kavling & POV',
            siteplans: 'Siteplan',
            blog: 'Blog',
            general: 'Umum'
        },
        allFiles: [],
        displayedFiles: [],
        search: '',
        loading: false,
        showUpload: false,
        isDragging: false,
        uploading: false,
        uploadTargetFolder: 'units',
        selectedFile: null,

        openPicker(detail) {
            this.targetInputId = detail?.targetInputId || null;
            this.targetPreviewId = detail?.targetPreviewId || null;
            const folder = detail?.folder || 'all';
            this.currentFolder = (folder in this.folders) ? folder : 'all';
            this.uploadTargetFolder = (folder !== 'all' && folder in this.folders) ? folder : 'units';
            this.selectedFile = null;
            this.showUpload = false;
            this.isOpen = true;
            this.loadFiles();
        },

        closePicker() {
            this.isOpen = false;
            this.selectedFile = null;
        },

        async loadFiles() {
            this.loading = true;
            try {
                const res = await fetch(`{{ route('admin.file-manager.files') }}?folder=${this.currentFolder}`);
                const data = await res.json();
                if (data.status === 'success') {
                    this.allFiles = data.files;
                    this.filterFiles();
                }
            } catch (e) {
                console.error('Error loading files:', e);
            } finally {
                this.loading = false;
            }
        },

        selectFolder(folderKey) {
            this.currentFolder = folderKey;
            if (folderKey !== 'all') {
                this.uploadTargetFolder = folderKey;
            }
            this.loadFiles();
        },

        filterFiles() {
            let list = this.allFiles;
            if (this.currentFolder !== 'all') {
                list = list.filter(f => f.folder === this.currentFolder);
            }
            if (this.search.trim() !== '') {
                const q = this.search.toLowerCase();
                list = list.filter(f => f.name.toLowerCase().includes(q) || f.folder.toLowerCase().includes(q));
            }
            this.displayedFiles = list;
        },

        selectFile(file) {
            this.selectedFile = file;
        },

        applySelection() {
            if (!this.selectedFile) return;

            // Update Target Input Element if ID specified
            if (this.targetInputId) {
                const inputEl = document.getElementById(this.targetInputId);
                if (inputEl) {
                    inputEl.value = this.selectedFile.path;
                    inputEl.dispatchEvent(new Event('input', { bubbles: true }));
                    inputEl.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            // Update Target Preview Img if ID specified
            if (this.targetPreviewId) {
                const imgEl = document.getElementById(this.targetPreviewId);
                if (imgEl) {
                    imgEl.src = this.selectedFile.url;
                    imgEl.classList.remove('hidden');
                }
                const wrapEl = document.getElementById(this.targetPreviewId + '_wrapper');
                if (wrapEl) {
                    wrapEl.classList.remove('hidden');
                }
            }

            // Dispatch global event with picked file
            window.dispatchEvent(new CustomEvent('file-picked', {
                detail: {
                    targetInputId: this.targetInputId,
                    file: this.selectedFile
                }
            }));

            this.closePicker();
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
            const target = this.uploadTargetFolder || (this.currentFolder === 'all' ? 'units' : this.currentFolder);
            const formData = new FormData();
            formData.append('folder', target);
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
                    this.currentFolder = target;
                    await this.loadFiles();
                    if (data.files && data.files.length > 0) {
                        this.selectedFile = data.files[0];
                    }
                    this.showUpload = false;
                }
            } catch (err) {
                console.error('Upload failed:', err);
            } finally {
                this.uploading = false;
            }
        }
    };
}
</script>
