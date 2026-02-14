<?php
require_once '../config/database.php';
require_once '../functions/auth_functions.php';
require_once '../functions/helper_functions.php';

check_authentication();
?>

<link rel="stylesheet" href="../public/css/edukasi.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- =============================== -->
<!--   CONTAINER ARTIKEL             -->
<!-- =============================== -->

<div class="orange-container">
    <h2 class="section-title">Artikel untuk kamu</h2>

    <div class="slider-row">

        <!-- CARD 1 -->
        <div class="article-card" onclick="location.href='../public/index.php?page=article&id=1'">
            <img src="../public/images/Artikel1.jpg">
            <div class="article-content">
                <div class="article-title">
                    1 Jam ke Seumur Hidup: Gaya Hidup Hemat Listrik lewat Earth Hour
                </div>
                <div class="article-desc">
                    Setiap tahun, jutaan orang di seluruh dunia berpartisipasi dalam Earth Hour, 
                    sebuah gerakan simbolis yang mengajak kita mematikan lampu selama satu jam.
                </div>
            </div>
        </div>

        <!-- CARD 2 -->
        <div class="article-card" onclick="location.href='../public/index.php?page=article&id=2'">
            <img src="../public/images/Artikel2.jpg">
            <div class="article-content">
                <div class="article-title">Hemat Listrik Itu Penting, Tapi Jangan Lupa Aman dari Setrum!</div>
                <div class="article-desc">
                    Listrik adalah kebutuhan primer yang tak terpisahkan dari kehidupan modern.
                </div>
            </div>
        </div>

        <!-- CARD 3 -->
        <div class="article-card" onclick="location.href='../public/index.php?page=article&id=3'">
            <img src="../public/images/Artikel3.jpg">
            <div class="article-content">
                <div class="article-title">Audit Energi Rumah Tangga: Menuju Tagihan Listrik yang Lebih Hemat</div>
                <div class="article-desc">
                    Di tengah kenaikan biaya hidup dan kesadaran perubahan iklim, penghematan energi menjadi prioritas.
                </div>
            </div>
        </div>

        <!-- CARD 4 -->
        <div class="article-card" onclick="location.href='../public/index.php?page=article&id=4'">
            <img src="../public/images/Artikel4.jpg">
            <div class="article-content">
                <div class="article-title">
                    Teknologi Smart Home sebagai Solusi Penghematan Energi Listrik
                </div>
                <div class="article-desc">
                    Era rumah pintar telah tiba, membawa kenyamanan dan efisiensi.
                </div>
            </div>
        </div>

    </div>
</div>

<!-- =============================== -->
<!--   CONTAINER VIDEO               -->
<!-- =============================== -->

<div class="orange-container">
    <h2 class="section-title">Biar lebih paham, nonton yuk !</h2>

    <div class="slider-row">

        <!-- VIDEO 1 -->
        <div class="video-card" onclick="window.open('https://youtu.be/Ow880CGkRxA?si=RDH_Kl5K6DNnHsrO')">
            <img src="../public/images/video3.png">
            <div class="video-title">Salah Kaprah Besar Tentang Hemat Energi</div>
        </div>

        <!-- VIDEO 2 -->
        <div class="video-card" onclick="window.open('https://youtu.be/fZmSc6Iw1rA?si=Y7LjBrIlI9MGUgc5')">
            <img src="../public/images/video2.png">
            <div class="video-title">Terang Tanpa Boros: Yuk, Beralih ke Lampu Hemat Energi!</div>
        </div>

        <!-- VIDEO 3 -->
        <div class="video-card" onclick="window.open('https://youtu.be/Y6v3tyUuzEU?si=QY1rusfZTLXmiC1C')">
            <img src="../public/images/video1.png">
            <div class="video-title">STOP Colok Kabel Kayak Gini! Bisa Bahaya Nyawa!</div>
        </div>

        <!-- VIDEO 4 -->
        <div class="video-card" onclick="window.open('https://youtu.be/pb6OTxIg3lE?si=-FkxD9imTYqlhztt')">
            <img src="../public/images/video4.png">
            <div class="video-title">Menghemat Energi di rumah kita</div>
        </div>

    </div>
</div>

<?php include 'layouts/footer.php'; ?>
