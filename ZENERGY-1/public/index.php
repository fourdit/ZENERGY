<?php
session_start();

// Load konfigurasi dan functions
require_once '../config/database.php';
require_once '../functions/helper_functions.php';
require_once '../functions/auth_functions.php';
require_once '../functions/profile_functions.php';

// Cek apakah user sudah login
$is_authenticated = isset($_SESSION['user_id']);

// Routing sederhana
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Redirect berdasarkan halaman
switch($page) {
    case 'login':
        header('Location: ../auth/dispatcher_auth.php?fitur=login');
        exit;
        
    case 'register':
        header('Location: ../auth/dispatcher_auth.php?fitur=register');
        exit;
        
    case 'dashboard':
        if (!$is_authenticated) {
            header('Location: ../auth/dispatcher_auth.php?fitur=login');
            exit;
        }
        include '../views/layouts/header.php';
        include '../dashboard/index.php';
        include '../views/layouts/footer.php';
        break;
        
    case 'catatan':
        if (!$is_authenticated) {
            header('Location: ../auth/dispatcher_auth.php?fitur=login');
            exit;
        }
        header('Location: ../catatan_listrik/dispatcher_catatan.php?fitur=catatan');
        exit;
        
    case 'profile':
        if (!$is_authenticated) {
            header('Location: ../auth/dispatcher_auth.php?fitur=login');
            exit;
        }
        header('Location: ../user_profile/dispatcher_profile.php?fitur=show');
        exit;
        
    case 'badge':
        if (!$is_authenticated) {
            header('Location: ../auth/dispatcher_auth.php?fitur=login');
            exit;
        }
        include '../views/layouts/header.php';
        include '../views/badge.php';
        include '../views/layouts/footer.php';
        break;
        
    case 'calculator':
        if (!$is_authenticated) {
            header('Location: ../auth/dispatcher_auth.php?fitur=login');
            exit;
        }
        include '../views/layouts/header.php';
        include '../views/calculator.php';
        include '../views/layouts/footer.php';
        break;
        
    case 'education':
        if (!$is_authenticated) {
            header('Location: ../auth/dispatcher_auth.php?fitur=login');
            exit;
        }
        include '../views/layouts/header.php';
        include '../views/education.php';
        include '../views/layouts/footer.php';
        break;
    
    case 'article':
        if (!$is_authenticated) {
            header('Location: ../auth/dispatcher_auth.php?fitur=login');
            exit;
        }
        include '../views/layouts/header.php';
        include '../views/artikel-detail.php';
        include '../views/layouts/footer.php';
        break;

        
    case 'discussion':
        if (!$is_authenticated) {
            header('Location: ../auth/dispatcher_auth.php?fitur=login');
            exit;
        }
        include '../views/layouts/header.php';
        include '../views/discussion.php';
        include '../views/layouts/footer.php';
        break;
        
    case 'other':
        if (!$is_authenticated) {
            header('Location: ../auth/dispatcher_auth.php?fitur=login');
            exit;
        }
        include '../views/layouts/header.php';
        include '../views/other_features.php';
        include '../views/layouts/footer.php';
        break;
        
    default:
        header('Location: ?page=dashboard');
        exit;
}
?>