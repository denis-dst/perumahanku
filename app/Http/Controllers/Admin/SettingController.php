<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        $user = auth()->user();

        return view('admin.settings.index', compact('settings', 'user'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index', ['tab' => 'website'])->with('success', 'Pengaturan website & profil perusahaan berhasil disimpan!');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama lengkap administrator wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'avatar.image' => 'File foto profil harus berupa gambar.',
            'avatar.mimes' => 'Format foto profil harus JPG, JPEG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        } elseif ($request->hasFile('avatar')) {
            if ($user->avatar && str_starts_with($user->avatar, 'uploads/') && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            } elseif ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/avatars');
            if (! file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $file->move($targetDir, $filename);
            $user->avatar = 'uploads/avatars/' . $filename;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('admin.settings.index', ['tab' => 'profile'])->with('success', 'Data profil & foto profil administrator berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok dengan akun Anda.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.settings.index', ['tab' => 'profile'])->with('success', 'Kata sandi akun administrator berhasil diubah!');
    }
}
