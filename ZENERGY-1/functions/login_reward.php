<?php
function check_daily_login($conn, $userId) {
    $today = date("Y-m-d");

    $stmt = $conn->prepare("SELECT last_login_date FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();

    if ($data['last_login_date'] !== $today) {
        $stmt = $conn->prepare("UPDATE users SET last_login_date = ? WHERE id = ?");
        $stmt->bind_param("si", $today, $userId);
        $stmt->execute();
    }


        $stmt->bind_param("si", $today, $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "UPDATE JALAN<br>";
        } else {
            echo "UPDATE GAGAL<br>";
        }

        return true;
    }

    return false;





