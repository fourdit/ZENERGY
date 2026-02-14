<?php
// Ambil note_id dari POST
$note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;

// Validasi ID
if ($note_id <= 0) {
    set_flash_message('error', 'ID catatan tidak valid');
    header('Location: dispatcher_catatan.php?fitur=catatan');
    exit;
}

// Validasi input
$date = $_POST['date'] ?? '';
$price_per_kwh = $_POST['price_per_kwh'] ?? 0;
$house_power = $_POST['house_power'] ?? 0;
$items = $_POST['items'] ?? [];

// Validasi dasar
if (empty($date) || empty($price_per_kwh) || empty($house_power) || empty($items)) {
    set_flash_message('error', 'Semua field harus diisi');
    header('Location: dispatcher_catatan.php?fitur=edit&id=' . $note_id);
    exit;
}

// Panggil fungsi untuk update catatan
$result = update_electricity_note($note_id, $_SESSION['user_id'], $date, $price_per_kwh, $house_power, $items);

if ($result['success']) {
    set_flash_message('success', 'Catatan listrik berhasil diperbarui!');
    header('Location: dispatcher_catatan.php?fitur=catatan');
    exit;
} else {
    set_flash_message('error', $result['message']);
    header('Location: dispatcher_catatan.php?fitur=edit&id=' . $note_id);
    exit;
}
?>