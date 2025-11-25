<?php

// ============================================
// DATABASE UTILITY FUNCTIONS
// ============================================

// Fungsi untuk execute query dengan error handling
function db_query($query) {
    $conn = get_db_connection();
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        error_log("Database Query Error: " . mysqli_error($conn));
        close_db_connection($conn);
        return false;
    }
    
    close_db_connection($conn);
    return $result;
}

// Fungsi untuk insert data
function db_insert($table, $data) {
    $conn = get_db_connection();
    
    // Escape semua value
    $escaped_data = array();
    foreach ($data as $key => $value) {
        $escaped_data[$key] = mysqli_real_escape_string($conn, $value);
    }
    
    $columns = implode(', ', array_keys($escaped_data));
    $values = "'" . implode("', '", array_values($escaped_data)) . "'";
    
    $query = "INSERT INTO $table ($columns) VALUES ($values)";
    $result = mysqli_query($conn, $query);
    
    $insert_id = false;
    if ($result) {
        $insert_id = mysqli_insert_id($conn);
    } else {
        error_log("Database Insert Error: " . mysqli_error($conn));
    }
    
    close_db_connection($conn);
    return $insert_id;
}

// Fungsi untuk update data
function db_update($table, $data, $where) {
    $conn = get_db_connection();
    
    // Build SET clause
    $set_parts = array();
    foreach ($data as $key => $value) {
        $escaped_value = mysqli_real_escape_string($conn, $value);
        $set_parts[] = "$key = '$escaped_value'";
    }
    $set_clause = implode(', ', $set_parts);
    
    // Build WHERE clause
    $where_parts = array();
    foreach ($where as $key => $value) {
        $escaped_value = mysqli_real_escape_string($conn, $value);
        $where_parts[] = "$key = '$escaped_value'";
    }
    $where_clause = implode(' AND ', $where_parts);
    
    $query = "UPDATE $table SET $set_clause WHERE $where_clause";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        error_log("Database Update Error: " . mysqli_error($conn));
    }
    
    close_db_connection($conn);
    return $result;
}

// Fungsi untuk delete data
function db_delete($table, $where) {
    $conn = get_db_connection();
    
    // Build WHERE clause
    $where_parts = array();
    foreach ($where as $key => $value) {
        $escaped_value = mysqli_real_escape_string($conn, $value);
        $where_parts[] = "$key = '$escaped_value'";
    }
    $where_clause = implode(' AND ', $where_parts);
    
    $query = "DELETE FROM $table WHERE $where_clause";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        error_log("Database Delete Error: " . mysqli_error($conn));
    }
    
    close_db_connection($conn);
    return $result;
}

// Fungsi untuk select data (single row)
function db_select_one($table, $where = array(), $columns = '*') {
    $conn = get_db_connection();
    
    $query = "SELECT $columns FROM $table";
    
    if (!empty($where)) {
        $where_parts = array();
        foreach ($where as $key => $value) {
            $escaped_value = mysqli_real_escape_string($conn, $value);
            $where_parts[] = "$key = '$escaped_value'";
        }
        $where_clause = implode(' AND ', $where_parts);
        $query .= " WHERE $where_clause";
    }
    
    $query .= " LIMIT 1";
    
    $result = mysqli_query($conn, $query);
    $data = null;
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    }
    
    close_db_connection($conn);
    return $data;
}

// Fungsi untuk select data (multiple rows)
function db_select_all($table, $where = array(), $columns = '*', $order_by = null, $limit = null) {
    $conn = get_db_connection();
    
    $query = "SELECT $columns FROM $table";
    
    if (!empty($where)) {
        $where_parts = array();
        foreach ($where as $key => $value) {
            $escaped_value = mysqli_real_escape_string($conn, $value);
            $where_parts[] = "$key = '$escaped_value'";
        }
        $where_clause = implode(' AND ', $where_parts);
        $query .= " WHERE $where_clause";
    }
    
    if ($order_by) {
        $query .= " ORDER BY $order_by";
    }
    
    if ($limit) {
        $query .= " LIMIT $limit";
    }
    
    $result = mysqli_query($conn, $query);
    $data = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    
    close_db_connection($conn);
    return $data;
}

// Fungsi untuk count data
function db_count($table, $where = array()) {
    $conn = get_db_connection();
    
    $query = "SELECT COUNT(*) as total FROM $table";
    
    if (!empty($where)) {
        $where_parts = array();
        foreach ($where as $key => $value) {
            $escaped_value = mysqli_real_escape_string($conn, $value);
            $where_parts[] = "$key = '$escaped_value'";
        }
        $where_clause = implode(' AND ', $where_parts);
        $query .= " WHERE $where_clause";
    }
    
    $result = mysqli_query($conn, $query);
    $count = 0;
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['total'];
    }
    
    close_db_connection($conn);
    return $count;
}

// Fungsi untuk cek apakah data exists
function db_exists($table, $where) {
    $count = db_count($table, $where);
    return $count > 0;
}

// Fungsi untuk escape string
function db_escape($value) {
    $conn = get_db_connection();
    $escaped = mysqli_real_escape_string($conn, $value);
    close_db_connection($conn);
    return $escaped;
}

// Fungsi untuk begin transaction
function db_begin_transaction() {
    $conn = get_db_connection();
    mysqli_begin_transaction($conn);
    return $conn;
}

// Fungsi untuk commit transaction
function db_commit($conn) {
    mysqli_commit($conn);
    close_db_connection($conn);
}

// Fungsi untuk rollback transaction
function db_rollback($conn) {
    mysqli_rollback($conn);
    close_db_connection($conn);
}

?>