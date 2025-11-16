<?php
session_start();

// Load dependencies
require_once '../config/database.php';
require_once '../functions/auth_functions.php';
require_once '../functions/helper_functions.php';

// Ambil parameter fitur
$fitur = isset($_GET['fitur']) ? $_GET['fitur'] : 'login';

// CSPEC: Control Specification
// Fitur | Show Login | Show Register | Process Login | Process Register | Logout
// login | 1          | 0             | 0             | 0                | 0
// register | 0       | 1             | 0             | 0                | 0
// do_login | 0       | 0             | 1             | 0                | 0
// do_register | 0    | 0             | 0             | 1                | 0
// logout | 0         | 0             | 0             | 0                | 1

switch($fitur) {
    case 'login':
        // Tampilkan form login
        require_once 'login.php';
        break;
    
    case 'register':
        // Tampilkan form register
        require_once 'register.php';
        break;
    
    case 'do_login':
        // Proses login
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $result = process_login($email, $password);
        
        if ($result['success']) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['user_name'] = $result['user_name'];
            $_SESSION['user_email'] = $result['user_email'];
            
            set_flash_message('success', 'Login berhasil!');
            header('Location: ../public/index.php?page=dashboard');
            exit;
        } else {
            set_flash_message('error', $result['message']);
            header('Location: dispatcher_auth.php?fitur=login');
            exit;
        }
        break;
    
    case 'do_register':
        // Proses register
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirmation = $_POST['password_confirmation'] ?? '';
        
        $result = process_register($name, $email, $password, $password_confirmation);
        
        if ($result['success']) {
            set_flash_message('success', 'Registrasi berhasil! Silakan login.');
            header('Location: dispatcher_auth.php?fitur=login');
            exit;
        } else {
            set_flash_message('error', $result['message']);
            header('Location: dispatcher_auth.php?fitur=register');
            exit;
        }
        break;
    
    case 'logout':
        // Proses logout
        session_destroy();
        set_flash_message('success', 'Logout berhasil!');
        header('Location: dispatcher_auth.php?fitur=login');
        exit;
        break;
    
    default:
        header('Location: dispatcher_auth.php?fitur=login');
        exit;
}
?>