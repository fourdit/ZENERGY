<?php
// Ambil ID catatan
$note_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($note_id <= 0) {
    set_flash_message('error', 'ID catatan tidak valid');
    header('Location: dispatcher_catatan.php?fitur=catatan');  // ✅ GANTI: index → catatan
    exit;
}

// Panggil fungsi untuk hapus catatan
$result = delete_electricity_note($note_id, $_SESSION['user_id']);

if ($result['success']) {
    set_flash_message('success', 'Catatan listrik berhasil dihapus!');
} else {
    set_flash_message('error', $result['message']);
}

header('Location: dispatcher_catatan.php?fitur=catatan');  // ✅ GANTI: index → catatan
exit;
?>