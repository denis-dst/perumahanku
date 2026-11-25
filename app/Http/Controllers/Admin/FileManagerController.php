<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    /**
     * Standard subfolders in public/uploads
     */
    protected array $folders = [
        'all' => 'Semua Berkas',
        'projects' => 'Katalog Perumahan',
        'house_types' => 'Tipe Rumah & Denah',
        'units' => 'Kavling & POV',
        'siteplans' => 'Peta Siteplan',
        'blog' => 'Artikel Blog',
        'general' => 'Umum & Pengaturan',
    ];

    /**
     * View standalone file manager
     */
    public function index()
    {
        return view('admin.file_manager.index', [
            'folders' => $this->folders,
        ]);
    }

    /**
     * JSON API to list files in public/uploads
     */
    public function getFiles(Request $request): JsonResponse
    {
        $selectedFolder = $request->query('folder', 'all');
        $search = strtolower(trim((string) $request->query('search', '')));

        $baseUploadDir = public_path('uploads');
        if (! File::exists($baseUploadDir)) {
            File::makeDirectory($baseUploadDir, 0755, true);
        }

        $files = [];

        if ($selectedFolder !== 'all' && isset($this->folders[$selectedFolder])) {
            $folderPath = $baseUploadDir . DIRECTORY_SEPARATOR . $selectedFolder;
            if (File::exists($folderPath)) {
                $files = array_merge($files, $this->scanDirectory($folderPath, $selectedFolder));
            }
        } else {
            // Scan root uploads and all subfolders
            $files = array_merge($files, $this->scanDirectory($baseUploadDir, 'general'));

            $subdirs = File::directories($baseUploadDir);
            foreach ($subdirs as $dir) {
                $folderName = basename($dir);
                $files = array_merge($files, $this->scanDirectory($dir, $folderName));
            }
        }

        // Apply search filter
        if ($search !== '') {
            $files = array_values(array_filter($files, function ($f) use ($search) {
                return str_contains(strtolower($f['name']), $search) || str_contains(strtolower($f['folder']), $search);
            }));
        }

        // Sort latest first
        usort($files, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return response()->json([
            'status' => 'success',
            'count' => count($files),
            'files' => $files,
            'folders' => $this->folders,
        ]);
    }

    /**
     * Upload single or multiple files directly to public/uploads/{folder}
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'files' => 'nullable|array',
            'files.*' => 'file|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'file' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'folder' => 'nullable|string|max:50',
        ]);

        $folder = preg_replace('/[^a-zA-Z0-9_\-]/', '', (string) $request->input('folder', 'general'));
        if (empty($folder) || $folder === 'all') {
            $folder = 'general';
        }

        $targetDir = public_path('uploads' . DIRECTORY_SEPARATOR . $folder);
        if (! File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $uploadedList = [];

        // Collect all files from request
        $filesToProcess = [];
        if ($request->hasFile('files')) {
            $filesToProcess = (array) $request->file('files');
        } elseif ($request->hasFile('file')) {
            $filesToProcess = [$request->file('file')];
        }

        if (empty($filesToProcess)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada file yang dipilih untuk diunggah.',
            ], 422);
        }

        foreach ($filesToProcess as $file) {
            if (!$file->isValid()) {
                continue;
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = Str::slug($originalName);
            if (empty($cleanName)) {
                $cleanName = 'photo';
            }
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = $cleanName . '-' . time() . '-' . Str::random(4) . '.' . $extension;

            $file->move($targetDir, $filename);

            $filePath = 'uploads/' . $folder . '/' . $filename;
            $fullPath = public_path($filePath);

            $dimensions = null;
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $imgInfo = @getimagesize($fullPath);
                if ($imgInfo) {
                    $dimensions = $imgInfo[0] . 'x' . $imgInfo[1];
                }
            }

            $uploadedList[] = [
                'name' => $filename,
                'path' => $filePath,
                'url' => asset($filePath),
                'folder' => $folder,
                'size' => $this->formatBytes(File::size($fullPath)),
                'bytes' => File::size($fullPath),
                'dimensions' => $dimensions,
                'timestamp' => File::lastModified($fullPath),
                'updated_at' => date('d M Y H:i', File::lastModified($fullPath)),
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => count($uploadedList) . ' file berhasil diunggah ke folder public/uploads/' . $folder . '.',
            'files' => $uploadedList,
        ]);
    }

    /**
     * Delete file from public/uploads
     */
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $rawPath = ltrim(trim($request->input('path')), '/\\');

        // Prevent path traversal
        if (str_contains($rawPath, '..')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak: Jalur tidak valid.',
            ], 403);
        }

        $baseUploadDir = realpath(public_path('uploads'));
        $targetFile = realpath(public_path($rawPath));

        if (! $targetFile || ! str_starts_with($targetFile, $baseUploadDir) || ! File::isFile($targetFile)) {
            return response()->json([
                'status' => 'error',
                'message' => 'File tidak ditemukan atau di luar direktori unggahan.',
            ], 404);
        }

        File::delete($targetFile);

        return response()->json([
            'status' => 'success',
            'message' => 'File berhasil dihapus dari penyimpanan.',
        ]);
    }

    /**
     * Helper to scan directory for files
     */
    protected function scanDirectory(string $directory, string $folderName): array
    {
        $result = [];
        if (! File::exists($directory)) {
            return $result;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        $files = File::files($directory);

        foreach ($files as $file) {
            $ext = strtolower($file->getExtension());
            if (! in_array($ext, $allowedExtensions)) {
                continue;
            }

            $relative = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $relative = str_replace('\\', '/', $relative);

            $dimensions = null;
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $imgInfo = @getimagesize($file->getRealPath());
                if ($imgInfo) {
                    $dimensions = $imgInfo[0] . 'x' . $imgInfo[1];
                }
            }

            $result[] = [
                'name' => $file->getFilename(),
                'path' => $relative,
                'url' => asset($relative),
                'folder' => $folderName,
                'size' => $this->formatBytes($file->getSize()),
                'bytes' => $file->getSize(),
                'dimensions' => $dimensions,
                'timestamp' => $file->getMTime(),
                'updated_at' => date('d M Y H:i', $file->getMTime()),
            ];
        }

        return $result;
    }

    /**
     * Format bytes to human readable string
     */
    protected function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
