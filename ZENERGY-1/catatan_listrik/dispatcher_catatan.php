<?php
session_start();

// Load dependencies (PENTING: Urutan harus benar!)
require_once '../config/database.php';
require_once '../functions/helper_functions.php';
require_once '../functions/auth_functions.php';
require_once '../functions/profile_functions.php';  // ✅ TAMBAHKAN baris ini!
require_once '../functions/catatan_functions.php';

// Cek autentikasi - PENTING untuk memastikan session user tersedia
check_authentication();

// Ambil parameter fitur
$fitur = isset($_GET['fitur']) ? $_GET['fitur'] : 'catatan';

// CSPEC: Control Specification untuk Catatan Listrik
// Fitur   | Show List | Show Create | Store | Show Edit | Update | Delete
// catatan | 1         | 0           | 0     | 0         | 0      | 0
// create  | 0         | 1           | 0     | 0         | 0      | 0
// store   | 0         | 0           | 1     | 0         | 0      | 0
// edit    | 0         | 0           | 0     | 1         | 0      | 0
// update  | 0         | 0           | 0     | 0         | 1      | 0
// delete  | 0         | 0           | 0     | 0         | 0      | 1

switch($fitur) {
    case 'catatan':
        // Tampilkan list catatan dengan layout
        include '../views/layouts/header.php';
        require_once 'catatan.php';
        include '../views/layouts/footer.php';
        break;
        
    case 'create':
        // Tampilkan form create dengan layout
        include '../views/layouts/header.php';
        require_once 'create.php';
        include '../views/layouts/footer.php';
        break;
        
    case 'store':
        // Proses simpan catatan baru (TIDAK PERLU LAYOUT)
        require_once 'store.php';
        break;
        
    case 'edit':
        // Tampilkan form edit dengan layout
        $note_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($note_id > 0) {
            include '../views/layouts/header.php';
            require_once 'edit.php';
            include '../views/layouts/footer.php';
        } else {
            set_flash_message('error', 'ID catatan tidak valid');
            header('Location: dispatcher_catatan.php?fitur=catatan');
            exit;
        }
        break;
        
    case 'update':
        // Proses update catatan (TIDAK PERLU LAYOUT)
        require_once 'update.php';
        break;
        
    case 'delete':
        // Proses hapus catatan (TIDAK PERLU LAYOUT)
        require_once 'delete.php';
        break;
        
    default:
        header('Location: dispatcher_catatan.php?fitur=catatan');
        exit;
}
?>