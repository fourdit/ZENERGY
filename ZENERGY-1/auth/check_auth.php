<?php
// Middleware untuk cek autentikasi
// File ini bisa di-include di halaman yang membutuhkan autentikasi

session_start();

// Load dependencies
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions/auth_functions.php';
require_once __DIR__ . '/../functions/helper_functions.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    set_flash_message('error', 'Silakan login terlebih dahulu');
    header('Location: ' . get_base_url() . '/auth/dispatcher_auth.php?fitur=login');
    exit;
}

// Cek apakah session masih valid
$user = get_logged_in_user();
if (!$user) {
    // Session tidak valid, logout paksa
    session_destroy();
    set_flash_message('error', 'Session telah berakhir. Silakan login kembali');
    header('Location: ' . get_base_url() . '/auth/dispatcher_auth.php?fitur=login');
    exit;
}

// Optional: Refresh session untuk keamanan
// Regenerate session ID setiap 30 menit
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} else if (time() - $_SESSION['last_regeneration'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
?>