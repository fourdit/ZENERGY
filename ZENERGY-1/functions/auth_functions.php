<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// PSPEC: Process Login
// Input: email, password
// Output: Array dengan status success dan data user
function process_login($email, $password) {
    // Validasi input
    if (empty($email) || empty($password)) {
        return [
            'success' => false,
            'message' => 'Email dan password harus diisi'
        ];
    }
    
    // Connect ke database
    $conn = get_db_connection();
    
    // Escape input untuk mencegah SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    
    // Query user
    $query = "SELECT id, name, email, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            close_db_connection($conn);
            return [
                'success' => true,
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_email' => $user['email']
            ];
        }
    }
    
    close_db_connection($conn);
    return [
        'success' => false,
        'message' => 'Email atau password salah'
    ];
}

// PSPEC: Process Register
function process_register($name, $email, $password, $password_confirmation) {
    // Validasi input tidak kosong
    if (empty($name) || empty($email) || empty($password)) {
        return [
            'success' => false,
            'message' => 'Semua field harus diisi'
        ];
    }
    
    // Validasi email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'message' => 'Format email tidak valid'
        ];
    }
    
    // Validasi password minimal 8 karakter
    if (strlen($password) < 8) {
        return [
            'success' => false,
            'message' => 'Password minimal 8 karakter'
        ];
    }
    
    // Validasi password confirmation
    if ($password !== $password_confirmation) {
        return [
            'success' => false,
            'message' => 'Konfirmasi password tidak cocok'
        ];
    }
    
    // Connect ke database
    $conn = get_db_connection();
    
    // Escape input
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    
    // Cek apakah email sudah terdaftar
    $check_query = "SELECT id FROM users WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        close_db_connection($conn);
        return [
            'success' => false,
            'message' => 'Email sudah terdaftar'
        ];
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user baru
    $insert_query = "INSERT INTO users (name, email, password, created_at, updated_at) 
                     VALUES ('$name', '$email', '$hashed_password', NOW(), NOW())";
    
    if (mysqli_query($conn, $insert_query)) {
        close_db_connection($conn);
        return [
            'success' => true,
            'message' => 'Registrasi berhasil'
        ];
    } else {
        close_db_connection($conn);
        return [
            'success' => false,
            'message' => 'Terjadi kesalahan saat registrasi'
        ];
    }
}

// Fungsi untuk cek apakah user sudah login
function check_authentication() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/dispatcher_auth.php?fitur=login');
        exit;
    }
}

// GANTI NAMA: get_current_user() → get_logged_in_user()
// Fungsi untuk mendapatkan data user yang sedang login
function get_logged_in_user() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    $conn = get_db_connection();
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    
    $query = "SELECT id, name, email, profile_photo_path, domisili 
              FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    
    $user = null;
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    }
    
    close_db_connection($conn);
    return $user;
}

?>