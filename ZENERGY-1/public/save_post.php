<?php

session_start();
require_once "../config/database.php";
$conn = get_db_connection();

$dataFile = __DIR__ . "/data/posts.json";

// Jika JSON tidak ada → buat kosong
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, "[]");
}

$posts = json_decode(file_get_contents($dataFile), true);
if (!is_array($posts)) $posts = [];


// ===========================================
// VALIDASI LOGIN
// ===========================================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/dispatcher_auth.php?fitur=login");
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$userName = $user["name"] ?? "User";


// ===========================================
// MANA ACTION-NYA?
// ===========================================
$action = $_GET['action'] ?? 'create';


// ===========================================
// 1️⃣ BUAT POST BARU
// ===========================================
if ($action === "create") {

    $text = $_POST['text'] ?? "";
    $filename = "";

    // Upload gambar
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if (!empty($_FILES["image"]["name"])) {
        $tmp = $_FILES["image"]["tmp_name"];
        $name = time() . "_" . $_FILES["image"]["name"];
        move_uploaded_file($tmp, $uploadDir . $name);
        $filename = $name;
    }

    // ID baru
    $newId = count($posts) == 0 ? 1 : end($posts)["id"] + 1;

    // Simpan pakai username
    $newPost = [
        "id"    => $newId,
        "user"  => $userName,   // PEMILIK POST
        "text"  => $text,
        "image" => $filename,
        "time"  => date("Y-m-d H:i:s")
    ];

    array_unshift($posts, $newPost);
    file_put_contents($dataFile, json_encode($posts, JSON_PRETTY_PRINT));

    header("Location: index.php");
    exit;
}





// ===========================================
// 3️⃣ HAPUS POSTs
// ===========================================
if ($action === "delete") {

    $id = $_GET["id"];
    $newPosts = [];

    foreach ($posts as $post) {

        if ($post["id"] == $id) {

            // CHECK USERNAME
            if ($post["user"] != $userName) {
                die("Tidak boleh hapus post orang lain!");
            }

            continue; // skip → hapus
        }

        $newPosts[] = $post;
    }

    file_put_contents($dataFile, json_encode($newPosts, JSON_PRETTY_PRINT));

    header("Location: index.php");
    exit;
}

?>
