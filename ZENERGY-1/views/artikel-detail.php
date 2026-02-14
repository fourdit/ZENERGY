<?php
require_once '../config/database.php';
require_once '../functions/auth_functions.php';
require_once '../functions/helper_functions.php';

check_authentication();


// =========================
// DATA ARTIKEL
// =========================
$artikels = [
    1 => [
        "judul" => "1 Jam ke Seumur Hidup: Gaya Hidup Hemat Listrik lewat Earth Hour",
        "gambar" => "Artikel1.jpg",
        "konten" => "
            <p>Setiap tahun, jutaan orang di seluruh dunia berpartisipasi dalam Earth Hour, sebuah gerakan simbolis 
            yang mengajak kita mematikan lampu selama satu jam. Tindakan kolektif ini adalah pengingat visual betapa 
            besarnya ketergantungan kita pada energi listrik dan betapa kuatnya dampak perubahan perilaku sederhana.
            Tantangan utama adalah membawa semangat satu jam ke dalam rutinitas seumur hidup. </p>

            <p>1. Maksimalkan Cahaya Alami: Terapkan prinsip Earth Hour di siang hari. Buka tirai dan manfaatkan cahaya 
            matahari sepenuhnya, menunda menyalakan lampu hingga senja benar-benar tiba.</p>

            <p>2. Disiplin Mematikan: Jadikan kebiasaan untuk selalu mematikan lampu saat meninggalkan ruangan, bahkan 
            hanya untuk beberapa menit. Disiplin kecil ini menjadi fondasi bagi penghematan energi berkelanjutan.</p>
        "
    ],

    2 => [
        "judul" => "Hemat Listrik Itu Penting, Tapi Jangan Lupa Aman dari Setrum!",
        "gambar" => "Artikel2.jpg",
        "konten" => "
            <p>Di tengah kenaikan biaya hidup, penghematan energi listrik menjadi prioritas finansial. Kunci 
            untuk menurunkan tagihan bukanlah mematikan semua perangkat, melainkan melakukan Audit Energi Rumah 
            Tangga—proses identifikasi pemborosan listrik yang sering tidak disadari.</p>

            <h2>A. Memerangi Vampire Power</h2>
            <p>Pemborosan terbesar yang terjadi setiap hari adalah Daya Siaga (Standby Power)
            atau Vampire Power. Ini energi yang diisap oleh perangkat yang mati tapi tetap tercolok seperti TV, charger,
            modem, dan lainnya.</p>

            <p>Solusi: Gunakan power strip ber-sakelar, lalu matikan sakelar utama setelah selesai.</p>
        "
    ],

    3 => [
        "judul" => "Audit Energi Rumah Tangga: Menuju Tagihan Listrik yang Lebih Hemat",
        "gambar" => "Artikel3.jpg",
        "konten" => "
            <p>Di tengah kenaikan biaya hidup, penghematan energi listrik menjadi prioritas finansial. Kunci 
            untuk menurunkan tagihan bukanlah mematikan semua perangkat, melainkan melakukan Audit Energi Rumah 
            Tangga—proses identifikasi pemborosan listrik yang sering tidak disadari.</p>

            <h2>A. Memerangi Vampire Power</h2>
            <p>Daya siaga adalah pemborosan terbesar yang terjadi tiap hari tanpa disadari.</p>

            <h2>B. Ganti Peralatan Strategis</h2>
            <p>1. Gunakan lampu LED.</p>
            <p>2. Atur AC di suhu ideal 24–26°C dan rutin bersihkan filter.</p>
        "
    ],

    4 => [
        "judul" => "Teknologi Smart Home sebagai Solusi Cerdas Penghematan Energi Listrik",
        "gambar" => "Artikel4.jpg",
        "konten" => "
            <p>Teknologi smart home kini menjadi solusi modern untuk mengendalikan penggunaan listrik secara efisien.</p>

            <h2>A. Sistem Otomasi</h2>
            <p>Smart home memungkinkan perangkat menyala dan mati otomatis sesuai kebutuhan.</p>

            <h2>B. Monitoring Konsumsi Energi</h2>
            <p>Pengguna dapat memantau konsumsi langsung dari aplikasi sehingga penghematan lebih terarah.</p>
        "
    ],
];


// =========================
// AMBIL ID ARTIKEL
// =========================
$id = $_GET['id'] ?? 1;
$artikel = $artikels[$id] ?? $artikels[1];
?>

<link rel="stylesheet" href="../public/css/artikel-detail.css">

<div class="art-detail-wrapper">
    <div class="art-detail-card">

        <!-- BUTTON KEMBALI -->
        <a href="../public/index.php?page=education" class="art-back">
            <img src="../public/images/back-button.png" class="back-icon">
        </a>

        <!-- JUDUL -->
        <h1 class="art-title"><?= $artikel['judul'] ?></h1>

        <!-- GAMBAR -->
        <img src="../public/images/<?= $artikel['gambar'] ?>" class="art-img">

        <!-- KONTEN -->
        <div class="art-content">
            <?= $artikel['konten'] ?>
        </div>

    </div>
</div>
