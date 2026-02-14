<?php
session_start();

// Load dependencies
require_once '../config/database.php';
require_once '../functions/auth_functions.php';
require_once '../functions/profile_functions.php';
require_once '../functions/helper_functions.php';

// Cek autentikasi
check_authentication();

// Ambil parameter fitur
$fitur = isset($_GET['fitur']) ? $_GET['fitur'] : 'show';

// CSPEC: Control Specification untuk Profile
// Fitur | Show Profile | Show Edit Form | Update Profile
// show  | 1            | 0              | 0
// edit  | 0            | 1              | 0
// update| 0            | 0              | 1

switch($fitur) {
    case 'show':
        // Tampilkan profile
        require_once 'show.php';
        break;
    
    case 'edit':
        // Tampilkan form edit
        require_once 'edit.php';
        break;
    
    case 'update':
        // Proses update profile
        require_once 'update.php';
        break;
    
    default:
        header('Location: dispatcher_profile.php?fitur=show');
        exit;
}
?>