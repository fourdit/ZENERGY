<?php
session_start();

$dataFile = __DIR__ . "/data/posts.json";

$posts = file_exists($dataFile)
    ? json_decode(file_get_contents($dataFile), true)
    : [];

if (!isset($_SESSION["user_name"])) {
    die("Harus login untuk komentar!");
}

$userName = $_SESSION["user_name"];
$postId   = $_POST["post_id"];
$comment  = trim($_POST["comment"]);

if ($comment === "") {
    header("Location: index.php");
    exit;
}

foreach ($posts as &$post) {

    if ($post["id"] == $postId) {

        if (!isset($post["comments"])) {
            $post["comments"] = [];
        }

        $post["comments"][] = [
            "user" => $userName,
            "text" => $comment,
            "time" => date("Y-m-d H:i:s")
        ];
    }
}

file_put_contents($dataFile, json_encode($posts, JSON_PRETTY_PRINT));


    header("Location: index.php");
exit;
?>
