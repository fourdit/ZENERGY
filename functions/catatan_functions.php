<?php

// PSPEC: Get Electricity Notes By Filter
// Input: user_id, filter (daily/weekly/monthly/yearly)
// Output: Array of notes
function get_electricity_notes_by_filter($user_id, $filter = 'daily') {
    $conn = get_db_connection();
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    // Build query berdasarkan filter
    $where_clause = "user_id = '$user_id'";
    
    switch ($filter) {
        case 'weekly':
            $start_of_week = date('Y-m-d', strtotime('monday this week'));
            $end_of_week = date('Y-m-d', strtotime('sunday this week'));
            $where_clause .= " AND date BETWEEN '$start_of_week' AND '$end_of_week'";
            break;
        case 'monthly':
            $month = date('m');
            $year = date('Y');
            $where_clause .= " AND MONTH(date) = '$month' AND YEAR(date) = '$year'";
            break;
        case 'yearly':
            $year = date('Y');
            $where_clause .= " AND YEAR(date) = '$year'";
            break;
        default: // daily
            $today = date('Y-m-d');
            $where_clause .= " AND date = '$today'";
            break;
    }
    
    $query = "SELECT id, user_id, date, price_per_kwh, house_power, total_cost 
              FROM electricity_notes 
              WHERE $where_clause 
              ORDER BY date DESC";
    
    $result = mysqli_query($conn, $query);
    $notes = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $notes[] = $row;
    }
    
    close_db_connection($conn);
    return $notes;
}

// PSPEC: Get Electricity Items By Note ID
// Input: note_id
// Output: Array of items
function get_electricity_items_by_note_id($note_id) {
    $conn = get_db_connection();
    $note_id = mysqli_real_escape_string($conn, $note_id);
    
    $query = "SELECT id, note_id, appliance_name, quantity, duration_hours, 
              duration_minutes, wattage, cost 
              FROM electricity_items 
              WHERE note_id = '$note_id'";
    
    $result = mysqli_query($conn, $query);
    $items = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    
    close_db_connection($conn);
    return $items;
}

// PSPEC: Calculate Total Cost
// Input: Array of notes
// Output: Total cost (float)
function calculate_total_cost($notes) {
    $total = 0;
    foreach ($notes as $note) {
        $total += $note['total_cost'];
    }
    return $total;
}

// PSPEC: Store Electricity Note
// Input: user_id, date, price_per_kwh, house_power, items (array)
// Output: Array dengan status success dan message
// Algoritma:
// 1. Begin transaction
// 2. Insert electricity_notes
// 3. Loop items, calculate cost, insert electricity_items
// 4. Update total_cost di electricity_notes
// 5. Commit transaction
function store_electricity_note($user_id, $date, $price_per_kwh, $house_power, $items) {
    $conn = get_db_connection();
    
    // Begin transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Escape inputs
        $user_id = mysqli_real_escape_string($conn, $user_id);
        $date = mysqli_real_escape_string($conn, $date);
        $price_per_kwh = mysqli_real_escape_string($conn, $price_per_kwh);
        $house_power = mysqli_real_escape_string($conn, $house_power);
        
        // Insert electricity_notes
        $insert_note = "INSERT INTO electricity_notes (user_id, date, price_per_kwh, house_power, total_cost, created_at, updated_at) 
                        VALUES ('$user_id', '$date', '$price_per_kwh', '$house_power', 0, NOW(), NOW())";
        
        if (!mysqli_query($conn, $insert_note)) {
            throw new Exception('Gagal menyimpan catatan utama');
        }
        
        $note_id = mysqli_insert_id($conn);
        $total_cost = 0;
        
        // Loop items
        foreach ($items as $item) {
            $appliance_name = mysqli_real_escape_string($conn, $item['appliance_name']);
            $quantity = mysqli_real_escape_string($conn, $item['quantity']);
            $duration_hours = mysqli_real_escape_string($conn, $item['duration_hours']);
            $duration_minutes = mysqli_real_escape_string($conn, $item['duration_minutes']);
            $wattage = mysqli_real_escape_string($conn, $item['wattage']);
            
            // Calculate cost
            $hours = $duration_hours + ($duration_minutes / 60);
            $kwh = ($wattage * $quantity * $hours) / 1000;
            $cost = $kwh * $price_per_kwh;
            
            // Insert item
            $insert_item = "INSERT INTO electricity_items (note_id, appliance_name, quantity, duration_hours, duration_minutes, wattage, cost, created_at, updated_at) 
                            VALUES ('$note_id', '$appliance_name', '$quantity', '$duration_hours', '$duration_minutes', '$wattage', '$cost', NOW(), NOW())";
            
            if (!mysqli_query($conn, $insert_item)) {
                throw new Exception('Gagal menyimpan item catatan');
            }
            
            $total_cost += $cost;
        }
        
        // Update total_cost
        $update_total = "UPDATE electricity_notes SET total_cost = '$total_cost' WHERE id = '$note_id'";
        if (!mysqli_query($conn, $update_total)) {
            throw new Exception('Gagal update total biaya');
        }
        
        // Commit transaction
        mysqli_commit($conn);
        close_db_connection($conn);
        
        return [
            'success' => true,
            'message' => 'Catatan berhasil disimpan'
        ];
        
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        close_db_connection($conn);
        
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

// PSPEC: Get Electricity Note By ID
// Input: note_id, user_id
// Output: Note data or null
function get_electricity_note_by_id($note_id, $user_id) {
    $conn = get_db_connection();
    $note_id = mysqli_real_escape_string($conn, $note_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $query = "SELECT id, user_id, date, price_per_kwh, house_power, total_cost 
              FROM electricity_notes 
              WHERE id = '$note_id' AND user_id = '$user_id'";
    
    $result = mysqli_query($conn, $query);
    $note = null;
    
    if (mysqli_num_rows($result) > 0) {
        $note = mysqli_fetch_assoc($result);
    }
    
    close_db_connection($conn);
    return $note;
}

// PSPEC: Update Electricity Note
// Input: note_id, user_id, date, price_per_kwh, house_power, items
// Output: Array dengan status success
function update_electricity_note($note_id, $user_id, $date, $price_per_kwh, $house_power, $items) {
    $conn = get_db_connection();
    
    mysqli_begin_transaction($conn);
    
    try {
        // Escape inputs
        $note_id = mysqli_real_escape_string($conn, $note_id);
        $user_id = mysqli_real_escape_string($conn, $user_id);
        $date = mysqli_real_escape_string($conn, $date);
        $price_per_kwh = mysqli_real_escape_string($conn, $price_per_kwh);
        $house_power = mysqli_real_escape_string($conn, $house_power);
        
        // Update electricity_notes
        $update_note = "UPDATE electricity_notes 
                        SET date = '$date', price_per_kwh = '$price_per_kwh', house_power = '$house_power', updated_at = NOW() 
                        WHERE id = '$note_id' AND user_id = '$user_id'";
        
        if (!mysqli_query($conn, $update_note)) {
            throw new Exception('Gagal update catatan utama');
        }
        
        // Hapus items lama
        $delete_items = "DELETE FROM electricity_items WHERE note_id = '$note_id'";
        mysqli_query($conn, $delete_items);
        
        $total_cost = 0;
        
        // Insert items baru
        foreach ($items as $item) {
            $appliance_name = mysqli_real_escape_string($conn, $item['appliance_name']);
            $quantity = mysqli_real_escape_string($conn, $item['quantity']);
            $duration_hours = mysqli_real_escape_string($conn, $item['duration_hours']);
            $duration_minutes = mysqli_real_escape_string($conn, $item['duration_minutes']);
            $wattage = mysqli_real_escape_string($conn, $item['wattage']);
            
            // Calculate cost
            $hours = $duration_hours + ($duration_minutes / 60);
            $kwh = ($wattage * $quantity * $hours) / 1000;
            $cost = $kwh * $price_per_kwh;
            
            // Insert item
            $insert_item = "INSERT INTO electricity_items (note_id, appliance_name, quantity, duration_hours, duration_minutes, wattage, cost, created_at, updated_at) 
                            VALUES ('$note_id', '$appliance_name', '$quantity', '$duration_hours', '$duration_minutes', '$wattage', '$cost', NOW(), NOW())";
            
            if (!mysqli_query($conn, $insert_item)) {
                throw new Exception('Gagal menyimpan item catatan');
            }
            
            $total_cost += $cost;
        }
        
        // Update total_cost
        $update_total = "UPDATE electricity_notes SET total_cost = '$total_cost' WHERE id = '$note_id'";
        if (!mysqli_query($conn, $update_total)) {
            throw new Exception('Gagal update total biaya');
        }
        
        // Commit transaction
        mysqli_commit($conn);
        close_db_connection($conn);
        
        return [
            'success' => true,
            'message' => 'Catatan berhasil diperbarui'
        ];
        
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        close_db_connection($conn);
        
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

// PSPEC: Delete Electricity Note
// Input: note_id, user_id
// Output: Array dengan status success
function delete_electricity_note($note_id, $user_id) {
    $conn = get_db_connection();
    
    mysqli_begin_transaction($conn);
    
    try {
        $note_id = mysqli_real_escape_string($conn, $note_id);
        $user_id = mysqli_real_escape_string($conn, $user_id);
        
        // Hapus items terlebih dahulu (karena foreign key)
        $delete_items = "DELETE FROM electricity_items WHERE note_id = '$note_id'";
        mysqli_query($conn, $delete_items);
        
        // Hapus note
        $delete_note = "DELETE FROM electricity_notes WHERE id = '$note_id' AND user_id = '$user_id'";
        
        if (!mysqli_query($conn, $delete_note)) {
            throw new Exception('Gagal menghapus catatan');
        }
        
        mysqli_commit($conn);
        close_db_connection($conn);
        
        return [
            'success' => true,
            'message' => 'Catatan berhasil dihapus'
        ];
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        close_db_connection($conn);
        
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

?>