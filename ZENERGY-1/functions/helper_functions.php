<?php

// Flash message functions
function set_flash_message($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

// Get base URL
function get_base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . $script;
}

// Format day name (Indonesia)
function format_day_name($date) {
    $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    
    $day_english = date('l', strtotime($date));
    return $days[$day_english];
}

// Sanitize input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Redirect function
function redirect($url, $message = null, $type = 'success') {
    if ($message) {
        set_flash_message($type, $message);
    }
    header("Location: $url");
    exit;
}

// Format currency
function format_rupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

// Debug helper
function dd($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}

?>