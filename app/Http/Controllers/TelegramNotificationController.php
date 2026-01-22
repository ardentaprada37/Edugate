<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TelegramNotificationController extends Controller
{
    protected $telegramService;

    public function __construct()
    {
        $this->telegramService = new \App\Services\TelegramService();
    }

    /**
     * Halaman review sebelum kirim ke Telegram
     */
    public function review()
    {
        // Ambil data keterlambatan hari ini
        $today = now()->format('Y-m-d');
        
        // Ambil semua data hari ini
        $allToday = \App\Models\LateAttendance::with(['student', 'schoolClass', 'lateReason', 'recordedBy'])
            ->whereDate('late_date', $today)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Filter yang belum dikirim
        $lateAttendances = $allToday->where('telegram_sent', false);
        
        // Hitung yang sudah dikirim
        $sentCount = $allToday->where('telegram_sent', true)->count();

        return view('telegram.review', compact('lateAttendances', 'sentCount'));
    }

    /**
     * Kirim notifikasi ke Telegram (batch)
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'attendance_ids' => 'required|array',
            'attendance_ids.*' => 'exists:late_attendances,id',
        ]);

        $attendances = \App\Models\LateAttendance::with(['student', 'schoolClass', 'lateReason', 'recordedBy'])
            ->whereIn('id', $validated['attendance_ids'])
            ->get();

        if ($attendances->isEmpty()) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $result = $this->telegramService->sendBatchNotification($attendances);

        if ($result) {
            
            foreach ($attendances as $attendance) {
                $attendance->update(['telegram_sent' => true, 'telegram_sent_at' => now()]);
            }

            return back()->with('success', 'Notifikasi berhasil dikirim ke Telegram! (' . count($attendances) . ' siswa)');
        } else {
            return back()->with('error', 'Gagal mengirim notifikasi. Pastikan Bot Token dan Chat ID sudah benar.');
        }
    }

    
     
    public function test()
    {
        $result = $this->telegramService->testConnection();

        if ($result['success']) {
            return back()->with('success', 'Bot berhasil terhubung! Bot: @' . $result['bot_username']);
        } else {
            return back()->with('error', 'Gagal terhubung: ' . $result['message']);
        }
    }

    /**
     * Reset status telegram_sent untuk hari ini
     */
    public function reset()
    {
        $today = now()->format('Y-m-d');
        
        $count = \App\Models\LateAttendance::whereDate('late_date', $today)
            ->where('telegram_sent', true)
            ->update([
                'telegram_sent' => false,
                'telegram_sent_at' => null
            ]);

        if ($count > 0) {
            return back()->with('success', "Berhasil reset $count data! Sekarang bisa kirim ulang ke Telegram.");
        } else {
            return back()->with('info', 'Tidak ada data yang perlu direset.');
        }
    }
}
