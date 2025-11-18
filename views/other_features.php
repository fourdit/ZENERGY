<?php
// session_start();
require_once '../config/database.php';
require_once '../functions/auth_functions.php';
require_once '../functions/helper_functions.php';

check_authentication();

// include '../views/layouts/header.php';
?>

<?php
/**
 * File: index.php (Versi tanpa database)
 * Deskripsi: Halaman utama FAQ Zenergy dengan data dummy
 */

// Data dummy kategori
$categories = array(
    array('id' => 1, 'name' => 'Kalkulator', 'display_order' => 1),
    array('id' => 2, 'name' => 'Akun', 'display_order' => 2),
    array('id' => 3, 'name' => 'Komunitas', 'display_order' => 3)
);

// Data dummy pertanyaan FAQ
$all_questions = array(
    // Kategori Kalkulator
    array(
        'category_id' => 1,
        'category_name' => 'Kalkulator',
        'question' => 'Bagaimana cara menggunakan kalkulator konsumsi listrik?',
        'answer' => 'Untuk menggunakan kalkulator konsumsi listrik Zenergy, ikuti langkah berikut:

1. Buka menu Kalkulator Hemat Energi
2. Masukkan daya peralatan dalam watt (contoh: TV 100 watt)
3. Input durasi penggunaan harian dalam jam
4. Pilih tarif listrik PLN sesuai golongan Anda atau gunakan default
5. Klik tombol "Hitung" untuk melihat estimasi biaya dan potensi penghematan

Hasil yang ditampilkan adalah estimasi biaya harian, bulanan, dan tahunan dari penggunaan peralatan tersebut.'
    ),
    array(
        'category_id' => 1,
        'category_name' => 'Kalkulator',
        'question' => 'Mengapa hasil kalkulator berbeda dengan tagihan PLN saya?',
        'answer' => 'Hasil kalkulator merupakan estimasi berdasarkan data yang Anda input. Perbedaan bisa terjadi karena beberapa faktor:

• Tarif listrik yang berbeda sesuai golongan pelanggan (R1, R2, R3, dll)
• Biaya beban dan pajak yang tidak termasuk dalam kalkulasi dasar
• Fluktuasi voltase dan efisiensi peralatan
• Penggunaan aktual yang mungkin berbeda dari perkiraan

Kalkulator dirancang sebagai panduan untuk membantu Anda memahami pola konsumsi energi dan bukan sebagai pengganti tagihan resmi PLN.'
    ),
    
    // Kategori Akun
    array(
        'category_id' => 2,
        'category_name' => 'Akun',
        'question' => 'Bagaimana cara membuat akun Zenergy?',
        'answer' => 'Untuk membuat akun Zenergy, ikuti langkah-langkah berikut:

1. Klik tombol "Daftar" di halaman utama website Zenergy
2. Isi formulir pendaftaran dengan data lengkap:
   - Nama lengkap
   - Alamat email aktif
   - Password yang kuat (minimal 8 karakter)
   - Nomor telepon
3. Centang persetujuan syarat dan ketentuan
4. Klik "Daftar"
5. Cek email Anda untuk verifikasi akun
6. Klik link verifikasi yang dikirimkan untuk mengaktifkan akun

Setelah akun aktif, Anda bisa langsung login dan menikmati semua fitur Zenergy.'
    ),
    array(
        'category_id' => 2,
        'category_name' => 'Akun',
        'question' => 'Lupa password akun saya, bagaimana cara reset?',
        'answer' => 'Jika Anda lupa password, ikuti langkah berikut untuk mereset:

1. Klik "Lupa Password" di halaman login
2. Masukkan alamat email yang terdaftar di akun Zenergy Anda
3. Klik "Kirim Link Reset"
4. Sistem akan mengirimkan link reset password ke email tersebut
5. Buka email dan klik link yang diberikan (link berlaku 24 jam)
6. Masukkan password baru Anda (minimal 8 karakter)
7. Konfirmasi password baru
8. Klik "Reset Password"

Password Anda berhasil diubah dan dapat digunakan untuk login. Pastikan menggunakan password yang kuat dan mudah diingat.'
    ),
    
    // Kategori Komunitas
    array(
        'category_id' => 3,
        'category_name' => 'Komunitas',
        'question' => 'Bagaimana cara bergabung dengan komunitas Zenergy?',
        'answer' => 'Setelah memiliki akun Zenergy, Anda otomatis dapat mengakses fitur komunitas. Berikut caranya:

1. Login ke akun Zenergy Anda
2. Masuk ke menu "Diskusi" atau "Komunitas" di sidebar
3. Anda dapat membaca diskusi yang ada
4. Memberi like atau komentar pada postingan orang lain
5. Untuk membuat topik diskusi baru:
   - Klik tombol "Buat Diskusi Baru"
   - Pilih kategori yang sesuai
   - Tulis judul dan isi diskusi
   - Klik "Posting"

Patuhi etika komunitas dalam berinteraksi dan hindari konten yang melanggar aturan.'
    ),
    array(
        'category_id' => 3,
        'category_name' => 'Komunitas',
        'question' => 'Apa saja manfaat bergabung dengan komunitas?',
        'answer' => 'Bergabung dengan komunitas Zenergy memberikan banyak manfaat:

• Berbagi tips dan pengalaman hemat energi dengan pengguna lain
• Mendapat solusi praktis dari komunitas untuk masalah konsumsi listrik
• Mengikuti challenge hemat energi dan berkesempatan mendapat badge
• Belajar dari studi kasus nyata pengguna lain
• Mendapat update informasi terbaru tentang teknologi hemat energi
• Mengakses program pemerintah terkait efisiensi energi
• Membangun jaringan dengan sesama peduli lingkungan
• Mendapat motivasi untuk konsisten menerapkan gaya hidup hemat energi

Komunitas ini dirancang sebagai wadah kolaborasi dan pembelajaran bersama.'
    )
);

// Fungsi untuk mendapatkan pertanyaan berdasarkan kategori
function getQuestionsByCategory($questions, $categoryId) {
    $filtered = array();
    foreach ($questions as $question) {
        if ($question['category_id'] == $categoryId) {
            $filtered[] = $question;
        }
    }
    return $filtered;
}

// Include header
?>

<div class="faq-container">
    <div class="faq-header">
        <h1>Pusat Bantuan Zenergy</h1>
        <p class="subtitle">Semua jawaban atas pertanyaan umum ada di sini.</p>
    </div>
    
    <div class="tab-navigation">
        <button class="tab-btn active" data-category="semua">Semua</button>
        <?php foreach ($categories as $category): ?>
            <button class="tab-btn" data-category="<?php echo strtolower($category['name']); ?>">
                <?php echo htmlspecialchars($category['name']); ?>
            </button>
        <?php endforeach; ?>
    </div>
    
    <div class="faq-content">
        <?php foreach ($categories as $category): ?>
            <div class="faq-section" data-category="<?php echo strtolower($category['name']); ?>">
                <h2 class="section-title"><?php echo htmlspecialchars($category['name']); ?></h2>
                
                <?php 
                $questions = getQuestionsByCategory($all_questions, $category['id']);
                if (!empty($questions)):
                ?>
                    <div class="faq-list">
                        <?php foreach ($questions as $question): ?>
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span><?php echo htmlspecialchars($question['question']); ?></span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    <p><?php echo nl2br(htmlspecialchars($question['answer'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-data">Belum ada pertanyaan untuk kategori ini.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="help-footer">
        <p>Masih butuh bantuan? Hubungi customer service kami.</p>
    </div>
</div>

<?php
// Include footer

?>


<?php
//  include '../views/layouts/footer.php'; 
?>