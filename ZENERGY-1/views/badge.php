<?php

// Hindari double session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/database.php";
$conn = get_db_connection();

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/dispatcher_auth.php?fitur=login");
    exit;
}

$userId = $_SESSION['user_id'];
$today = date("Y-m-d");
$message = "";

// Ambil data user
$stmt = $conn->prepare("
    SELECT points, last_claim, login_count 
    FROM users 
    WHERE id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$points = $user['points'];
$lastClaim = $user['last_claim'];
$loginCount = $user['login_count'];

// Proses klaim
if (isset($_POST['claim'])) {

    if ($lastClaim != $today) {

        $update = $conn->prepare("
            UPDATE users 
            SET points = points + 10, last_claim = ?, login_count = login_count + 1
            WHERE id = ?
        ");
        $update->bind_param("si", $today, $userId);
        $update->execute();

        // Update lokal
        $points += 10;
        $lastClaim = $today;
        $loginCount += 1;

        $message = "Berhasil klaim! +10 poin 🎉";

    } else {
        $message = "Kamu sudah klaim hari ini!";
    }
}
// Tentukan badge user berdasarkan poin
$earnedBadge = "";

if ($points >= 2500) {
    $earnedBadge = "Master Hemat Nusantara";
} elseif ($points >= 1000) {
    $earnedBadge = "Pahlawan Rumah Hijau";
} elseif ($points >= 500) {
    $earnedBadge = "Jagoan Energi";
} elseif ($points >= 250) {
    $earnedBadge = "Pasukan Hemat";
} elseif ($points >= 100) {
    $earnedBadge = "Pemula Hemat";
} else {
    $earnedBadge = "";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenergy - Badge</title>
    <link rel="stylesheet" href="badge.css">
</head>
<body>

<div class="badge-wrapper">

    <h2 class="badge-title">Ayo Lengkapi Badgenya!</h2>

    <!-- SECTION KLAIM -->
    <div class="badge-section">
<div class="left-info">
    <div class="badge-label">Badge Kamu :</div>

    <?php if ($earnedBadge === ""): ?>
        <!-- Tidak ada badge: tampilkan pesan saja (tidak menampilkan gambar) -->
        <p class="message-title">
            Belum Ada Badge
        </p>

        <p class="message-note">
            Waduhhh, kamu belum<br>
            memenuhi syarat untuk<br>
            mendapatkan badge
        </p>

    <?php else: ?>
        <!-- User punya badge: tampilkan judul badge + gambar + pesan sukses -->
        <p class="earned-badge-title"><?= htmlspecialchars($earnedBadge) ?></p>

        <?php
        // tentukan index file gambar berdasarkan badge (harus sesuai file di folder images)
        $badgeIndex = 0;
        if ($points >= 2500) { $badgeIndex = 5; }
        elseif ($points >= 1000) { $badgeIndex = 4; }
        elseif ($points >= 500)  { $badgeIndex = 3; }
        elseif ($points >= 250)  { $badgeIndex = 2; }
        elseif ($points >= 100)  { $badgeIndex = 1; }

        // pastikan file ada sebelum menampilkannya
        $badgeFile = $badgeIndex ? "images/badge{$badgeIndex}.png" : "";
        ?>
        <?php if ($badgeFile && file_exists($badgeFile)): ?>
            <img src="<?= $badgeFile ?>" alt="<?= htmlspecialchars($earnedBadge) ?>" class="streak-icon">
        <?php endif; ?>

        <p class="message-success">
            Kamu sudah mendapatkan badge ini! 🎉
        </p>
    <?php endif; ?>
</div>

        

        <div class="right-info">

            <form method="POST">
                <input type="hidden" name="claim" value="1">

                <button 
                    type="submit"
                    class="btn-claim"
                    <?= ($lastClaim == $today) ? "disabled" : "" ?>
                >
                    Klaim
                </button>
            </form>

            <p class="small-text">point kamu : <?= $points ?></p>
            <p class="small-text">total login : <?= $loginCount ?></p>
        </div>
    </div>

    <h3 class="streak-title">Streak Badge</h3>

    <div class="streak-container">

        <!-- BADGE 1 -->
        <div class="streak-item">
          
            <p class="streak-name">Pemula Hemat</p>
              <img src="images/badge1.png" class="streak-icon">
            <p class="streak-points">100</p>
            <p class="streak-days">10 days</p>
        </div>

        <span class="streak-divider">—</span>

        <!-- BADGE 2 -->
        <div class="streak-item">

            <p class="streak-name">Pasukan Hemat</p>
             <img src="images/badge2.png" class="streak-icon">
            <p class="streak-points">250</p>
            <p class="streak-days">25 days</p>
        </div>

        <span class="streak-divider">—</span>

        <!-- BADGE 3 -->
        <div class="streak-item">
           
            <p class="streak-name">Jagoan Energi</p>
             <img src="images/badge3.png" class="streak-icon">
            <p class="streak-points">500</p>
            <p class="streak-days">50 days</p>
        </div>

        <span class="streak-divider">—</span>

        <!-- BADGE 4 -->
        <div class="streak-item">
           
            <p class="streak-name">Pahlawan Rumah Hijau</p>
             <img src="images/badge4.png" class="streak-icon">
            <p class="streak-points">1000</p>
            <p class="streak-days">100 days</p>
        </div>

        <span class="streak-divider">—</span>

        <!-- BADGE 5 -->
        <div class="streak-item">
              <p class="streak-name">Master Hemat Nusantara</p>
            <img src="images/badge5.png" class="streak-icon">
            <p class="streak-points">2500</p>
            <p class="streak-days">250 days</p>
        </div>

    </div>

</div>

</body>
</html>
