# ZENERGY

Aplikasi web untuk monitoring dan manajemen catatan konsumsi listrik berbasis PHP.

## 📋 Deskripsi

ZENERGY adalah sistem manajemen catatan listrik yang memungkinkan pengguna untuk melacak dan memantau penggunaan energi listrik mereka. Aplikasi ini dibangun dengan PHP native dan menyediakan dashboard interaktif untuk visualisasi data konsumsi listrik.

## ✨ Fitur

- 🔐 **Sistem Autentikasi**: Login dan registrasi pengguna yang aman
- 📊 **Dashboard**: Visualisasi data konsumsi listrik
- 📝 **Catatan Listrik**: Pencatatan dan manajemen penggunaan listrik
- 👤 **Profil Pengguna**: Manajemen profil dan informasi personal
- 🗂️ **Database Management**: Sistem penyimpanan data terstruktur
- 📱 **Responsive Design**: Tampilan yang optimal di berbagai perangkat

## 🗂️ Struktur Proyek

```
ZENERGY/
├── auth/                  # Modul autentikasi (login, register, logout)
├── catatan_listrik/       # Modul pencatatan konsumsi listrik
├── config/                # File konfigurasi database dan sistem
├── dashboard/             # Dashboard dan visualisasi data
├── functions/             # Helper functions dan utilities
├── public/                # Assets (CSS, JS, images)
├── storage/               # Penyimpanan file upload
│   └── profiles/          # Foto profil pengguna
├── user_profile/          # Modul manajemen profil pengguna
├── views/                 # Template dan komponen UI
├── database.sql           # Schema dan struktur database
└── index.php              # Entry point aplikasi
```

## 🚀 Instalasi

### Prasyarat

- PHP >= 7.4
- MySQL/MariaDB
- Web Server (Apache/Nginx)
- Composer (opsional)

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/fourdit/ZENERGY.git
   cd ZENERGY
   ```

2. **Setup Database**
   - Buat database baru di MySQL/MariaDB
   ```sql
   CREATE DATABASE zenergy;
   ```
   - Import file `database.sql`
   ```bash
   mysql -u username -p zenergy < database.sql
   ```

3. **Konfigurasi Database**
   - Edit file konfigurasi di folder `config/`
   - Sesuaikan kredensial database:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'zenergy');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

4. **Set Permission**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 public/
   ```

5. **Jalankan Aplikasi**
   - Akses melalui web browser: `http://localhost/ZENERGY`
   - Atau gunakan PHP built-in server:
   ```bash
   php -S localhost:8000
   ```

## 📖 Penggunaan

### Registrasi Pengguna Baru
1. Klik menu "Daftar" atau "Register"
2. Isi form registrasi dengan data yang diperlukan
3. Submit dan lakukan login

### Mencatat Konsumsi Listrik
1. Login ke sistem
2. Navigasi ke menu "Catatan Listrik"
3. Tambah catatan baru dengan informasi:
   - Tanggal pembacaan
   - Angka meteran
   - Konsumsi (kWh)
   - Biaya
4. Simpan catatan

### Melihat Dashboard
- Dashboard menampilkan:
  - Grafik konsumsi listrik
  - Total penggunaan bulanan
  - Riwayat pembayaran
  - Statistik penggunaan

## 🛠️ Teknologi

- **Backend**: PHP (Native)
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Custom CSS (dalam folder `public/`)

## 👥 Kontributor

Proyek ini dikembangkan oleh tim [fourdit](https://github.com/fourdit) dengan kontribusi dari 4 kontributor.

## 📝 Lisensi

Proyek ini belum memiliki lisensi yang dispesifikasikan. Silakan hubungi pemilik repository untuk informasi lisensi.

## 🤝 Kontribusi

Kontribusi selalu diterima! Untuk berkontribusi:

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📧 Kontak

Untuk pertanyaan atau dukungan, silakan buka issue di repository atau hubungi tim pengembang.

## 🔄 Status Proyek

- **Bahasa Utama**: PHP (61.3%)
- **CSS**: 26.6%
- **JavaScript**: 12.0%
- **Hack**: 0.1%

## 📌 Catatan

- Pastikan PHP memiliki ekstensi yang diperlukan (mysqli, pdo, gd)
- Untuk produksi, pastikan menggunakan HTTPS
- Backup database secara berkala
- Perbarui kredensial default setelah instalasi

---

**Made with ⚡ by fourdit team**