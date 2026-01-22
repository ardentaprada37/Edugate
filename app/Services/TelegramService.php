<?php

namespace App\Services;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Exception;

class TelegramService
{
    protected $telegram;
    protected $chatId;

    public function __construct()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $this->chatId = env('TELEGRAM_CHAT_ID');
        
        if ($token) {
            $this->telegram = new BotApi($token);
        }
    }

    /**
     * Kirim notifikasi siswa telat ke Telegram
     */
    public function sendLateNotification($lateAttendance)
    {
        if (!$this->telegram || !$this->chatId) {
            return false;
        }

        try {
            $student = $lateAttendance->student;
            $class = $lateAttendance->schoolClass;
            $reason = $lateAttendance->lateReason;
            $recordedBy = $lateAttendance->recordedBy;

            // Format pesan
            $message = $this->formatLateMessage($lateAttendance);

            // Kirim pesan
            $this->telegram->sendMessage(
                $this->chatId,
                $message,
                'HTML'
            );

            return true;
        } catch (Exception $e) {
            \Log::error('Telegram send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim batch notifikasi (banyak siswa sekaligus)
     */
    public function sendBatchNotification($lateAttendances)
    {
        if (!$this->telegram || !$this->chatId) {
            return false;
        }

        try {
            $message = $this->formatBatchMessage($lateAttendances);

            $this->telegram->sendMessage(
                $this->chatId,
                $message,
                'HTML'
            );

            return true;
        } catch (Exception $e) {
            \Log::error('Telegram batch send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format pesan untuk 1 siswa
     */
    private function formatLateMessage($lateAttendance)
    {
        $student = $lateAttendance->student;
        $class = $lateAttendance->schoolClass;
        $reason = $lateAttendance->lateReason;
        $recordedBy = $lateAttendance->recordedBy;

        $totalLate = $student->getTotalLateCount();
        $status = $student->getLateStatus();

        $statusEmoji = '✅';
        $statusText = 'Normal';
        if ($status == 'parent_notification') {
            $statusEmoji = '🚨';
            $statusText = 'PERLU NOTIFIKASI ORANG TUA';
        } elseif ($status == 'warning') {
            $statusEmoji = '⚠️';
            $statusText = 'Peringatan';
        }

        $message = "🚨 <b>SISWA TERLAMBAT</b>\n\n";
        $message .= "👤 <b>Nama:</b> {$student->name}\n";
        $message .= "🆔 <b>NIS:</b> {$student->student_number}\n";
        $message .= "🏫 <b>Kelas:</b> {$class->name}\n\n";
        
        $message .= "📅 <b>Tanggal:</b> " . $lateAttendance->late_date->format('d F Y') . "\n";
        $message .= "⏰ <b>Jam Kedatangan:</b> " . date('H:i', strtotime($lateAttendance->arrival_time)) . " WIB\n";
        $message .= "📝 <b>Alasan:</b> {$reason->reason}\n";
        
        if ($lateAttendance->notes) {
            $message .= "💬 <b>Catatan:</b> {$lateAttendance->notes}\n";
        }
        
        $message .= "\n📊 <b>Total Telat Bulan Ini:</b> {$totalLate}x\n";
        $message .= "{$statusEmoji} <b>Status:</b> {$statusText}\n\n";
        
        $message .= "👨‍🏫 <i>Dicatat oleh: {$recordedBy->name}</i>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━";

        return $message;
    }

    /**
     * Kirim notifikasi untuk single late attendance
     */
    public function sendSingleLateNotification($lateAttendance)
    {
        if (!$this->telegram || !$this->chatId) {
            return false;
        }

        try {
            $message = $this->formatSingleLateMessage($lateAttendance);

            $this->telegram->sendMessage(
                $this->chatId,
                $message,
                'HTML'
            );

            return true;
        } catch (Exception $e) {
            \Log::error('Telegram single late notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format pesan untuk single late attendance
     */
    private function formatSingleLateMessage($attendance)
    {
        $student = $attendance->student;
        $class = $attendance->schoolClass;
        $reason = $attendance->lateReason;
        $recordedBy = $attendance->recordedBy;
        $date = $attendance->late_date->format('l, d F Y');
        $arrivalTime = date('H:i', strtotime($attendance->arrival_time));

        $message = "🚨 <b>LAPORAN KETERLAMBATAN SISWA</b>\n\n";
        $message .= "👤 <b>Nama:</b> {$student->name}\n";
        $message .= "📌 <b>NIS:</b> {$student->student_number}\n";
        $message .= "🏫 <b>Kelas:</b> {$class->name}\n";
        $message .= "📅 <b>Tanggal:</b> {$date}\n";
        $message .= "⏰ <b>Jam Kedatangan:</b> {$arrivalTime} WIB\n";
        $message .= "📝 <b>Alasan:</b> {$reason->reason}\n";
        
        if (!empty($attendance->notes)) {
            $message .= "💬 <b>Catatan:</b> {$attendance->notes}\n";
        }
        
        $message .= "\n━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "👨‍🏫 <i>Dicatat oleh: {$recordedBy->name}</i>\n";
        $message .= "🤖 <i>Notifikasi otomatis dari Sistem Keterlambatan</i>";

        return $message;
    }

    /**
     * Kirim notifikasi bulk untuk late attendance dengan data individual setiap siswa
     */
    public function sendBulkIndividualLateNotification($lateAttendances)
    {
        if (!$this->telegram || !$this->chatId) {
            return false;
        }

        try {
            $message = $this->formatBulkIndividualLateMessage($lateAttendances);

            $this->telegram->sendMessage(
                $this->chatId,
                $message,
                'HTML'
            );

            return true;
        } catch (Exception $e) {
            \Log::error('Telegram bulk individual late notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format pesan untuk banyak siswa dengan data individual masing-masing
     */
    private function formatBulkIndividualLateMessage($lateAttendances)
    {
        if (empty($lateAttendances)) {
            return '';
        }

        $firstRecord = $lateAttendances->first();
        $class = $firstRecord->schoolClass;
        $recordedBy = $firstRecord->recordedBy;
        $date = $firstRecord->late_date->format('l, d F Y');
        $count = $lateAttendances->count();

        $message = "🚨 <b>LAPORAN KETERLAMBATAN SISWA</b>\n\n";
        $message .= "🏫 <b>Kelas:</b> {$class->name}\n";
        $message .= "📅 <b>Tanggal:</b> {$date}\n";
        $message .= "👥 <b>Total:</b> {$count} siswa\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━\n\n";

        foreach ($lateAttendances as $index => $attendance) {
            $student = $attendance->student;
            $reason = $attendance->lateReason;
            $arrivalTime = date('H:i', strtotime($attendance->arrival_time));
            $number = $index + 1;

            $message .= "<b>{$number}. {$student->name}</b>\n";
            $message .= "   📌 NIS: {$student->student_number}\n";
            $message .= "   ⏰ Jam: {$arrivalTime} WIB\n";
            $message .= "   📝 Alasan: {$reason->reason}\n";
            
            if (!empty($attendance->notes)) {
                $message .= "   💬 Catatan: {$attendance->notes}\n";
            }
            
            $message .= "\n";
        }

        $message .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "👨‍🏫 <i>Dicatat oleh: {$recordedBy->name}</i>\n";
        $message .= "🤖 <i>Notifikasi otomatis dari Sistem Keterlambatan</i>";

        return $message;
    }

    /**
     * Format pesan untuk banyak siswa (batch) - format lama (masih digunakan untuk telegram review)
     */
    private function formatBatchMessage($lateAttendances)
    {
        $date = now()->format('l, d F Y');
        $count = count($lateAttendances);

        $message = "📋 <b>LAPORAN KETERLAMBATAN HARI INI</b>\n";
        $message .= "📅 {$date}\n";
        $message .= "👥 Total: {$count} siswa\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━\n\n";

        foreach ($lateAttendances as $index => $attendance) {
            $student = $attendance->student;
            $class = $attendance->schoolClass;
            $reason = $attendance->lateReason;
            $time = date('H:i', strtotime($attendance->arrival_time));

            $number = $index + 1;
            $message .= "<b>{$number}. {$student->name}</b>\n";
            $message .= "   🏫 {$class->name}\n";
            $message .= "   ⏰ {$time} WIB\n";
            $message .= "   📝 {$reason->reason}\n\n";
        }

        $message .= "━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "🤖 <i>Laporan otomatis dari Sistem Keterlambatan</i>";

        return $message;
    }

    /**
     * Test koneksi bot
     */
    public function testConnection()
    {
        try {
            if (!$this->telegram) {
                return ['success' => false, 'message' => 'Bot token tidak ditemukan'];
            }

            $me = $this->telegram->getMe();
            
            return [
                'success' => true,
                'message' => 'Bot berhasil terhubung!',
                'bot_name' => $me->getFirstName(),
                'bot_username' => $me->getUsername()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
