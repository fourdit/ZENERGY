<?php
// Ambil data dari form
$name = $_POST['name'] ?? '';
$domisili = $_POST['domisili'] ?? '';
$profile_photo = $_FILES['profile_photo'] ?? null;

// Validasi
if (empty($name)) {
    set_flash_message('error', 'Nama harus diisi');
    header('Location: dispatcher_profile.php?fitur=edit');
    exit;
}

// Panggil fungsi untuk update profile
$result = update_user_profile($_SESSION['user_id'], $name, $domisili, $profile_photo);

if ($result['success']) {
    // Update session name jika berubah
    $_SESSION['user_name'] = $name;
    
    set_flash_message('success', 'Profil berhasil diperbarui!');
    header('Location: dispatcher_profile.php?fitur=show');
    exit;
} else {
    set_flash_message('error', $result['message']);
    header('Location: dispatcher_profile.php?fitur=edit');
    exit;
}
?>