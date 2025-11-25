<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
$conn = get_db_connection();

// Ambil username dari DB berdasarkan session user_id
$userName = null;

if (!empty($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $userName = $res['name'] ?? null;
}
?>

<?php
$dataFile = __DIR__ . "/../public/data/posts.json";
$data = [];
if (file_exists($dataFile)) {
    $data = json_decode(file_get_contents($dataFile), true);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Zenergy UI</title>
<link rel="stylesheet" href="/ZENERGY/public/css/diskusi.css">




</head>
<body>

<div class="device-frame">

    <div class="top-bar">
        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search Bar" onkeyup="searchPosts()"/>
                <span><img src=""></span>
            </div>
        </div>
    </div>

   
<div class="forum-section-title">
    <img src="images/chat_bubble.png">
    <span style="color:#F7931E !important;">Forum Discussion</span>
</div>


    <?php foreach ($data as $key => $post): ?>
    <div class="forum-card">
        <div class="forum-header">
            <div class="forum-user">
                <img src="images/default-profile.jpg" />
                <div>
                    <strong><?= $post["user"] ?></strong>
                </div>
            </div>
            <small><?= $post["time"] ?></small>
        </div>

        <p><?= $post["text"] ?></p>

        <?php if (!empty($post["image"])): ?>
            <img src="uploads/<?= $post["image"] ?>" class="post-img" />
        <?php endif; ?>

        <!-- KOMENTAR -->
<?php if (!empty($post["comments"])): ?>
<div class="comment-section">
    <?php foreach ($post["comments"] as $c): ?>
        <div class="comment-item">
            <strong><?= $c["user"] ?>:</strong>
            <span><?= $c["text"] ?></span>
            <div class="comment-time"><?= $c["time"] ?></div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<form action="../public/save_comment.php" method="POST" class="comment-form">
    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
    <input type="text" name="comment" placeholder="Tulis komentar..." required>

</form>

   <?php if (!empty($userName) && $post['user'] === $userName): ?>
    <div class="forum-actions">
        <a href="save_post.php?action=delete&id=<?= $post['id'] ?>" 
           class="delete-btn"
           onclick="return confirm('Yakin hapus postingan?');">Hapus</a>
    </div>
<?php endif; ?>


    </div>
<?php endforeach; ?>
    </div>
</div>

<div class="fab" onclick="openModal()">+</div>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h3>Buat Postingan</h3>
        <form method="POST" action="save_post.php" enctype="multipart/form-data">
            <textarea name="text" placeholder="Apa yang ada dipikiran Anda?"></textarea>
            <input type="file" name="image">
            <br><br>
            <button>Kirim</button>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById("modal").style.display = "flex";
    }
    window.onclick = e => {
        if (e.target.id == "modal") document.getElementById("modal").style.display = "none";
    }
</script>

<script>
function searchPosts() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let cards = document.querySelectorAll(".post-card, .forum-card");

    cards.forEach(card => {
        let text = card.innerText.toLowerCase();
        card.style.display = text.includes(input) ? "" : "none";
    });
}
</script>

</body>
</html>

<?php
?>
