<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HouseType;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'house_type_id' => 'nullable|exists:house_types,id',
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required|string|max:50',
            'message' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::create($validated);
        $project = Project::find($validated['project_id']);
        $houseType = $validated['house_type_id'] ? HouseType::find($validated['house_type_id']) : null;

        // WhatsApp direct link generator
        $waAdmin = Setting::get('whatsapp_number', '6281234567890');
        // Clean phone
        $waAdminClean = preg_replace('/[^0-9]/', '', $waAdmin);
        if (str_starts_with($waAdminClean, '0')) {
            $waAdminClean = '62'.substr($waAdminClean, 1);
        }

        $typeInfo = $houseType ? " (Tipe: {$houseType->name})" : '';
        $dateFormatted = date('d-m-Y', strtotime($validated['preferred_date']));

        $waText = 'Halo Admin *'.Setting::get('site_name', 'Perumahanku')."*,\n\n"
            ."Saya ingin konfirmasi *Booking Survei Lokasi*:\n"
            .'• Proyek: *'.($project->name ?? '-')."*{$typeInfo}\n"
            .'• Nama: *'.$validated['name']."*\n"
            .'• No HP/WA: '.$validated['phone']."\n"
            .'• Rencana Tanggal: *'.$dateFormatted."*\n"
            .'• Jam Kunjungan: *'.$validated['preferred_time']."*\n"
            .($validated['message'] ? ('• Catatan: '.$validated['message']."\n") : '')
            ."\nMohon info ketersediaan tim marketing untuk pendampingan survei. Terima kasih!";

        $waUrl = "https://wa.me/{$waAdminClean}?text=".rawurlencode($waText);

        return redirect()->back()->with([
            'success' => 'Permintaan survei berhasil dikirim! Silakan klik tombol di bawah untuk langsung terhubung ke WhatsApp Marketing.',
            'wa_url' => $waUrl,
            'booking_id' => $booking->id,
        ]);
    }
}
