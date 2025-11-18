<?php
// Ambil data user yang sedang login
$current_user = get_logged_in_user();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZENERGY - Penghematan Energi Listrik</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/zenergy/public/css/app.css">
    <link rel="stylesheet" href="/zenergy/public/css/style.css">
    <link rel="stylesheet" href="/zenergy/public/css/calculator.css">
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <div class="header-left">
                <img src="/zenergy/public/images/logo-zenergy.png" alt="ZENERGY" class="logo">
                <span class="brand-name">ZENERGY</span>
            </div>
            <div class="header-right">
                <div class="user-profile" onclick="window.location.href='/zenergy/user_profile/dispatcher_profile.php?fitur=show'">
                    <img src="<?php echo get_profile_photo_url($current_user['profile_photo_path']); ?>" alt="<?php echo htmlspecialchars($current_user['name']); ?>" class="profile-photo">
                    <span class="user-name"><?php echo htmlspecialchars($current_user['name']); ?></span>
                </div>
            </div>
        </header>

        <div class="app-body">
            <!-- Sidebar -->
            <aside class="sidebar">
                <nav class="sidebar-nav">
                    <a href="/zenergy/public/index.php?page=dashboard" class="nav-item">
                        <i class="bi bi-bar-chart-line-fill nav-icon"></i>
                        <span class="nav-text">Dashboard Visual</span>
                    </a>
                    <!-- ✅ GANTI: index → catatan -->
                    <a href="/zenergy/catatan_listrik/dispatcher_catatan.php?fitur=catatan" class="nav-item">
                        <i class="bi bi-lightning-charge-fill nav-icon"></i>
                        <span class="nav-text">Catatan Listrik</span>
                    </a>
                    <a href="/zenergy/public/index.php?page=badge" class="nav-item">
                        <i class="bi bi-award-fill nav-icon"></i>
                        <span class="nav-text">Badge</span>
                    </a>
                    <a href="/zenergy/public/index.php?page=calculator" class="nav-item">
                        <i class="bi bi-calculator-fill nav-icon"></i>
                        <span class="nav-text">Kalkulator Hemat Energi</span>
                    </a>
                    <a href="/zenergy/public/index.php?page=education" class="nav-item">
                        <i class="bi bi-book-fill nav-icon"></i>
                        <span class="nav-text">Edukasi</span>
                    </a>
                    <a href="/zenergy/public/index.php?page=discussion" class="nav-item">
                        <i class="bi bi-chat-dots-fill nav-icon"></i>
                        <span class="nav-text">Diskusi</span>
                    </a>
                    <a href="/zenergy/public/index.php?page=other" class="nav-item">
                        <i class="bi bi-grid-3x3-gap-fill nav-icon"></i>
                        <span class="nav-text">Fitur Lain</span>
                    </a>
                    
                    <!-- Logout -->
                    <div class="nav-logout-form">
                        <a href="/zenergy/auth/dispatcher_auth.php?fitur=logout" class="nav-item nav-logout" onclick="return confirm('Yakin ingin logout?')">
                            <i class="bi bi-box-arrow-right nav-icon"></i>
                            <span class="nav-text">Logout</span>
                        </a>
                    </div>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">