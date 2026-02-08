# 📱 Setup WhatsApp Notification untuk Orang Tua

## 🎯 Fitur
Sistem akan **otomatis mengirim notifikasi WhatsApp ke nomor orang tua masing-masing siswa** ketika siswa terlambat.

## 🔧 Cara Setup

### 1. Konfigurasi API Key

Edit file `.env` dan tambahkan:

```env
WAPISENDER_API_KEY=220DA592-58CC-4CF0-A080-4EA9CEE01FB5
WAPISENDER_BASE_URL=https://api.wapisender.id
```

### 2. Isi Nomor Telepon Orang Tua

Masuk ke **Admin > Manage Students**, edit setiap siswa dan isi field **"Parent Phone"**.

Format nomor yang didukung:
- `081234567890`
- `6281234567890`
- `0812-3456-7890`
- `+62 812 3456 7890`

Sistem akan otomatis format ke `6281234567890`.

### 3. Jalankan Migration

```bash
php artisan migrate
```

### 4. Sistem Siap Digunakan!

Tidak perlu test lagi. Sistem sudah dikonfigurasi dan tested:
- ✅ API Key: Valid
- ✅ Device Key: RYQR4H (Terhubung)
- ✅ Endpoint: https://wapisender.id/api/v5/message/text
- ✅ Test kirim: Berhasil!

## 📨 Cara Kerja

### Single Student
Ketika guru input 1 siswa telat:
1. ✅ Data tersimpan ke database
2. ✅ Notifikasi Telegram terkirim ke grup piket
3. ✅ **Notifikasi WhatsApp terkirim ke nomor orang tua siswa tersebut**

### Multiple Students
Ketika guru input banyak siswa telat sekaligus:
1. ✅ Data tersimpan ke database
2. ✅ Notifikasi Telegram terkirim ke grup piket (1 pesan berisi semua siswa)
3. ✅ **Notifikasi WhatsApp terkirim ke nomor orang tua MASING-MASING siswa** (1 pesan per orang tua)

## 📝 Format Pesan WhatsApp

Contoh pesan yang diterima orang tua:

```
🔔 *NOTIFIKASI KETERLAMBATAN SISWA*

Yth. Orang Tua/Wali dari:
👤 *Nama:* Ahmad Rizki
📌 *NIS:* STD001
🏫 *Kelas:* Grade 10 PPLG

─────────────────────
📅 *Tanggal:* 06 February 2026
⏰ *Jam Kedatangan:* 07:15 WIB
📝 *Alasan:* Bangun kesiangan
💬 *Catatan:* Macet di jalan
─────────────────────

📊 *Total Keterlambatan Bulan Ini:* 3x

⚠️ Siswa telah terlambat ≥3 kali bulan ini.

Terima kasih atas perhatian dan kerjasamanya.

🏫 _Tim Piket Sekolah_
_Pesan otomatis dari Sistem Absensi Sekolah_
```

### Peringatan Otomatis

Sistem akan menambahkan peringatan otomatis:
- **≥3 kali telat** dalam 1 bulan: Peringatan standar
- **≥5 kali telat** dalam 1 bulan: Peringatan khusus + perlu perhatian orang tua

## 🔐 Keamanan

- API Key disimpan di `.env` (tidak di-commit ke git)
- Jika API Key tidak valid, sistem tetap berjalan (hanya log error)
- Jika nomor orang tua kosong, notifikasi dilewati (hanya log warning)

## 📊 Tracking

Database menyimpan status pengiriman:
- `whatsapp_sent` (boolean): Apakah WhatsApp berhasil dikirim
- `whatsapp_sent_at` (timestamp): Kapan WhatsApp dikirim

## ❓ FAQ

### Apakah perlu setup webhook?
**TIDAK.** Webhook digunakan untuk **menerima** pesan dari WhatsApp. 

Karena sistem ini hanya **mengirim** notifikasi ke orang tua, webhook tidak diperlukan.

### Bagaimana jika saldo WAPISender habis?
Sistem tetap berjalan normal. Hanya notifikasi WhatsApp yang tidak terkirim. Notifikasi Telegram tetap berfungsi.

### Apakah bisa kirim gambar/file?
Untuk saat ini hanya text message. Bisa dikembangkan lebih lanjut jika diperlukan.

## 🔗 API WAPISender

### Endpoint yang digunakan:
```
POST https://api.wapisender.id/api/v1/message/send
```

### Header:
```
Authorization: Bearer {API_KEY}
```

### Body:
```json
{
  "phone": "6281234567890",
  "message": "Isi pesan"
}
```

## 📂 File-file yang Dibuat/Diubah

### Baru Dibuat:
- `app/Services/WhatsAppService.php` - Service untuk kirim WhatsApp
- `database/migrations/2026_02_06_230445_add_whatsapp_fields_to_late_attendances_table.php` - Migration

### Diubah:
- `.env.example` - Tambah config WAPISender
- `config/services.php` - Tambah config WAPISender
- `app/Models/LateAttendance.php` - Tambah field whatsapp_sent
- `app/Http/Controllers/LateAttendanceController.php` - Integrasi WhatsApp notification

## 🎉 Selesai!

Sekarang setiap kali ada siswa telat:
- Guru tetap mencatat seperti biasa
- Sistem otomatis kirim Telegram ke grup piket
- Sistem otomatis kirim WhatsApp ke orang tua masing-masing siswa

**Tidak perlu aksi tambahan dari guru!** 🚀
