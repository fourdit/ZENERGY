<?php
session_start();
require_once '../config/database.php';
$conn = get_db_connection();


// Load dependencies
require_once '../config/database.php';
require_once '../functions/auth_functions.php';
require_once '../functions/helper_functions.php';

// Ambil parameter fitur
$fitur = isset($_GET['fitur']) ? $_GET['fitur'] : 'login';

switch($fitur) {
    case 'login':
        require_once 'login.php';
        break;
    
    case 'register':
        require_once 'register.php';
        break;
    
    case 'do_login':
        // Proses login
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $result = process_login($email, $password);
        
        if ($result['success']) {

            // SET SESSION USER
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['user_name'] = $result['user_name'];
            $_SESSION['user_email'] = $result['user_email'];

            /* ------------------------------------------------------
               DAILY LOGIN REWARD (+10 POINT PER HARI)
            ------------------------------------------------------ */

            // Ambil data user
           set_flash_message('success', 'Login berhasil! Jangan lupa untuk claim 10 point di halaman badge! 🎉');
            /* ------------------------------------------------------
               END DAILY LOGIN REWARD
            ------------------------------------------------------ */

            header('Location: ../public/index.php?page=dashboard');
            exit;
        
        } else {
            set_flash_message('error', $result['message']);
            header('Location: dispatcher_auth.php?fitur=login');
            exit;
        }
        break;
    
    case 'do_register':
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
