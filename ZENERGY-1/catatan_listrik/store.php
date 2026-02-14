<?php
// Validasi input
$date = $_POST['date'] ?? '';
$price_per_kwh = $_POST['price_per_kwh'] ?? 0;
$house_power = $_POST['house_power'] ?? 0;
$items = $_POST['items'] ?? [];

// Validasi dasar
if (empty($date) || empty($price_per_kwh) || empty($house_power) || empty($items)) {
    set_flash_message('error', 'Semua field harus diisi');
    header('Location: dispatcher_catatan.php?fitur=create');
    exit;
}

// Panggil fungsi untuk menyimpan catatan
$result = store_electricity_note($_SESSION['user_id'], $date, $price_per_kwh, $house_power, $items);

if ($result['success']) {
    set_flash_message('success', 'Catatan listrik berhasil disimpan!');
    header('Location: dispatcher_catatan.php?fitur=catatan');  // ✅ GANTI: index → catatan
    exit;
} else {
    set_flash_message('error', $result['message']);
    header('Location: dispatcher_catatan.php?fitur=create');
    exit;
}
?>