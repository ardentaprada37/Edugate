# 🚀 Quick Start Guide - Bulk Late Attendance

## ✅ Apa yang Sudah Selesai?

Fitur bulk late attendance dengan **data individual** untuk setiap siswa sudah selesai diimplementasi!

### Perbedaan dengan Request Awal:
- ❌ **Request awal**: 1 form untuk semua siswa (jam & alasan sama)
- ✅ **Implementasi sekarang**: 1 form per siswa (jam & alasan beda-beda)

## 🎯 Cara Kerja

### 1️⃣ Pilih Siswa (Class Page)
```
Guru → Buka kelas → Centang siswa yang telat → Klik "Submit Selection"
```

### 2️⃣ Isi Data Individual (Review Page)
```
Setiap siswa punya form sendiri:
- Siswa A: Jam 07:25, Alasan "Bus mogok", Catatan "..."
- Siswa B: Jam 07:30, Alasan "Bangun kesiangan", Catatan "-"
- Siswa C: Jam 07:45, Alasan "Hujan deras", Catatan "Jalanan macet"
```

### 3️⃣ Simpan & Kirim Telegram
```
Klik "Simpan Semua" → DB Transaction → Telegram Notification → Success!
```

## 📁 File yang Diubah

| File | Status | Deskripsi |
|------|--------|-----------|
| `resources/views/classes/show.blade.php` | ✅ Modified | Tambah checkbox & bulk selection |
| `resources/views/late-attendance/bulk-review.blade.php` | ✅ Created | Form individual per siswa |
| `app/Http/Controllers/LateAttendanceController.php` | ✅ Modified | Method `bulkReview()` & `bulkStore()` |
| `app/Services/TelegramService.php` | ✅ Modified | Method `sendBulkIndividualLateNotification()` |
| `routes/web.php` | ✅ Modified | Route bulk-review & bulk-store |

## 🧪 Testing Cepat

```bash
# 1. Clear cache
php artisan view:clear
php artisan config:clear

# 2. Check routes
php artisan route:list --name=late-attendance

# 3. Test di browser
# - Login sebagai teacher
# - Buka halaman kelas
# - Centang 2-3 siswa
# - Klik "Submit Selection"
# - Isi form untuk tiap siswa
# - Klik "Simpan Semua"
# - Check Telegram group
```

## 📊 Data Structure

### Input dari Form:
```php
students[0][student_id] = 1
students[0][late_date] = 2026-01-19
students[0][arrival_time] = 07:25
students[0][late_reason_id] = 2
students[0][notes] = Bus mogok

students[1][student_id] = 2
students[1][late_date] = 2026-01-19
students[1][arrival_time] = 07:30
students[1][late_reason_id] = 1
students[1][notes] = 
```

### Output Telegram:
```
🚨 LAPORAN KETERLAMBATAN SISWA

🏫 Kelas: 10 PPLG
📅 Tanggal: Friday, 19 January 2026
👥 Total: 2 siswa
━━━━━━━━━━━━━━━━━━━━━

1. Ahmad Fauzi
   📌 NIS: 12345
   ⏰ Jam: 07:25 WIB
   📝 Alasan: Terlambat kendaraan umum
   💬 Catatan: Bus mogok

2. Siti Nurhaliza
   📌 NIS: 12346
   ⏰ Jam: 07:30 WIB
   📝 Alasan: Bangun kesiangan

━━━━━━━━━━━━━━━━━━━━━
👨‍🏫 Dicatat oleh: Pak Budi
🤖 Notifikasi otomatis dari Sistem Keterlambatan
```

## 🔑 Key Features

✅ **Checkbox Selection** - Pilih banyak siswa sekaligus
✅ **Individual Forms** - Setiap siswa punya data berbeda
✅ **Pre-filled Date** - Tanggal otomatis hari ini
✅ **Remove Option** - Bisa hapus siswa saat review
✅ **Database Transaction** - Semua atau tidak sama sekali
✅ **Auto Telegram** - Notifikasi otomatis setelah simpan
✅ **Backward Compatible** - Tombol "Single" masih ada

## 🎨 UI Preview

### Class Page (Selection)
```
┌─────────────────────────────────────────┐
│ [✓] Ahmad Fauzi      [Single] [Riwayat] │
│ [ ] Siti Nurhaliza   [Single] [Riwayat] │
│ [✓] Budi Santoso     [Single] [Riwayat] │
│                                          │
│         [Submit Selection (2)]           │
└─────────────────────────────────────────┘
```

### Review Page (Individual Forms)
```
┌─────────────────────────────────────────┐
│ 10 PPLG - 2 Siswa Terpilih              │
├─────────────────────────────────────────┤
│ 📝 Siswa #1: Ahmad Fauzi        [Hapus] │
│ ├─ Tanggal: [2026-01-19]                │
│ ├─ Jam: [07:25]                         │
│ ├─ Alasan: [Terlambat kendaraan umum]   │
│ └─ Catatan: [Bus mogok]                 │
├─────────────────────────────────────────┤
│ 📝 Siswa #2: Budi Santoso       [Hapus] │
│ ├─ Tanggal: [2026-01-19]                │
│ ├─ Jam: [07:45]                         │
│ ├─ Alasan: [Membantu orang tua]         │
│ └─ Catatan: []                          │
├─────────────────────────────────────────┤
│               [Simpan Semua]            │
└─────────────────────────────────────────┘
```

## 🐛 Troubleshooting

### Error: "Some students do not belong to the selected class"
- **Penyebab**: Student ID tidak valid atau dari kelas lain
- **Solusi**: Refresh halaman dan pilih ulang

### Error: Validation failed
- **Penyebab**: Ada field yang kosong (jam/alasan)
- **Solusi**: Pastikan semua field wajib terisi

### Telegram tidak terkirim tapi data tersimpan
- **Normal**: Ini by design - data tidak boleh hilang
- **Check**: Log file untuk error Telegram
- **Action**: Fix Telegram credentials, data sudah aman tersimpan

### Tombol "Submit Selection" tidak muncul
- **Penyebab**: Tidak ada siswa yang dicentang
- **Solusi**: Centang minimal 1 siswa

## 📞 Support

Untuk dokumentasi lengkap, lihat: `BULK_LATE_ATTENDANCE_INDIVIDUAL.md`

---

**Status**: ✅ Production Ready
**Version**: 2.0 (Individual Data)
**Date**: 19 January 2026
