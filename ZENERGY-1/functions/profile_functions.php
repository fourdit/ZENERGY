<?php

// PSPEC: Update User Profile
// Input: user_id, name, domisili, profile_photo (file)
// Output: Array dengan status success
// Algoritma:
// 1. Validasi input
// 2. Jika ada file foto, upload dan simpan path
// 3. Update data user di database
// 4. Return hasil

function update_user_profile($user_id, $name, $domisili, $profile_photo) {
    $conn = get_db_connection();
    
    try {
        // Escape inputs
        $user_id = mysqli_real_escape_string($conn, $user_id);
        $name = mysqli_real_escape_string($conn, $name);
        $domisili = mysqli_real_escape_string($conn, $domisili);
        
        $photo_path = null;
        
        // Handle upload foto
        if ($profile_photo && $profile_photo['error'] === UPLOAD_ERR_OK) {
            $upload_result = upload_profile_photo($profile_photo, $user_id);
            
            if (!$upload_result['success']) {
                throw new Exception($upload_result['message']);
            }
            
            $photo_path = $upload_result['filename'];
            
            // Hapus foto lama jika ada
            $old_photo_query = "SELECT profile_photo_path FROM users WHERE id = '$user_id'";
            $old_photo_result = mysqli_query($conn, $old_photo_query);
            $old_photo_data = mysqli_fetch_assoc($old_photo_result);
            
            if ($old_photo_data && $old_photo_data['profile_photo_path']) {
                delete_profile_photo($old_photo_data['profile_photo_path']);
            }
        }
        
        // Build update query
        if ($photo_path) {
            $update_query = "UPDATE users 
                            SET name = '$name', domisili = '$domisili', profile_photo_path = '$photo_path', updated_at = NOW() 
                            WHERE id = '$user_id'";
        } else {
            $update_query = "UPDATE users 
                            SET name = '$name', domisili = '$domisili', updated_at = NOW() 
                            WHERE id = '$user_id'";
        }
        
        if (!mysqli_query($conn, $update_query)) {
            throw new Exception('Gagal update profile');
        }
        
        close_db_connection($conn);
        
        return [
            'success' => true,
            'message' => 'Profile berhasil diperbarui'
        ];
        
    } catch (Exception $e) {
        close_db_connection($conn);
        
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

// Fungsi untuk upload foto profile
function upload_profile_photo($file, $user_id) {
    // Validasi file
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    if (!in_array($file['type'], $allowed_types)) {
        return [
            'success' => false,
            'message' => 'Format file harus JPG, JPEG, atau PNG'
        ];
    }
    
    if ($file['size'] > $max_size) {
        return [
            'success' => false,
            'message' => 'Ukuran file maksimal 2MB'
        ];
    }
    
    // Generate nama file
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . $user_id . '.' . $extension;
    $upload_path = '../storage/profiles/' . $filename;
    
    // Pastikan folder ada
    if (!file_exists('../storage/profiles')) {
        mkdir('../storage/profiles', 0777, true);
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return [
            'success' => true,
            'filename' => $filename
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Gagal upload file'
        ];
    }
}

// Fungsi untuk hapus foto profile
function delete_profile_photo($filename) {
    $file_path = '../storage/profiles/' . $filename;
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

// Fungsi untuk mendapatkan URL foto profile
function get_profile_photo_url($photo_path) {
    if ($photo_path) {
        return '../storage/profiles/' . $photo_path;
    } else {
        return '../public/images/default-profile.jpg';
    }
}

?>