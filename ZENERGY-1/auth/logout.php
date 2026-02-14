<?php
session_start();

// Load dependencies
require_once '../config/database.php';
require_once '../functions/helper_functions.php';

// Hapus semua session
$_SESSION = array();

// Hapus session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy session
session_destroy();

// Set flash message
session_start(); // Start lagi untuk set flash message
set_flash_message('success', 'Logout berhasil!');

// Redirect ke login
header('Location: dispatcher_auth.php?fitur=login');
exit;
?>