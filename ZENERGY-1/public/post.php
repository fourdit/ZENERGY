//GAKEPAKE INI KAYANYA

<?php
// dummy data



// ambil id dari URL
$id = $_GET['id'] ?? 1;



?>
<!DOCTYPE html>
<html>
<head>
<title>Detail Post</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
    margin:0;
    font-family: 'Montserrat', sans-serif;
    background:#ffffff;
}

.post-wrapper {
    width:90%;
    max-width:1100px;
    margin:40px auto;
    border:1px solid #000;
    border-radius:18px;
    overflow:hidden;
    background: linear-gradient(to right, #EF7722, #FAA533, #d89c60);
}

/* HEADER */
.post-header {
    background:#e88a24;
    padding:18px 20px;
    font-size:22px;
    font-weight:bold;
    color:black;
    display:flex;
    align-items:center;
    gap:10px;
    border-bottom:1px solid #000;
}

/* BODY */
.post-body {
    display:flex;
    min-height:700px;
    background: linear-gradient(to right, #FF8935 32%, #FAA533 88%);
}

/* LEFT */
.left {
    flex:2;
    padding:35px;
}

/* USER BAR */
.user-info {
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:10px;
}
.reacts {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-top: 10px;
}

.react-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 20px;
}

.like-btn img {
    width: 28px;
    transition: 0.2s;
}

.like-btn.liked img {
    filter: hue-rotate(-40deg) saturate(5);
    transform: scale(1.25);
}

.user-info img {
    width:45px;
    height:45px;
    border-radius:50%;
    border:1px solid #000;
    object-fit:cover;
}

.user-info .name {
    font-size:18px;
    font-weight:700;
}

/* CONTENT WRAPPER (caption + foto + reacts) */
.post-content {
    width: 80%;
    max-width: 450px;
    margin: 0 auto;
}

.caption {
    margin: 0 0 10px 0;
    font-size: 16px;
}

.post-img {
    width:100%;
    border-radius:20px;
    border:1px solid #000;
    display:block;
}

/* REACTS */
.reacts {
    width: 100%;
    display:flex;
    align-items:center;
    gap:12px;
    margin-top:10px;
    font-size:20px;
}

.like-btn.liked { color:red; transform:scale(1.25); cursor:pointer; }
.like-btn { cursor:pointer; }

/* RIGHT */
.right {
    flex:1.2;
    padding:25px 40px 25px 25px;
    border-left:2px solid #000;
    color:#000;
}

textarea {
    width:100%;
    height:90px;
    border-radius:12px;
    border:1px solid #000;
    padding:12px;
    resize:none;
    margin-bottom:10px;
    background:#fff;
    color:#000;
}

button {
    background:#e88a24;
    border:1px solid #000;
    padding:10px 18px;
    color:white;
    border-radius:10px;
    cursor:pointer;
    font-size:14px;
    font-weight:bold;
    margin-bottom:15px;
    transition:0.2s;
}
button:hover { background:#ce7218; }

/* COMMENT LIST */
.comment-item {
    display:flex;
    gap:10px;
    margin-bottom:12px;
}

.comment-item img {
    width:40px;
    height:40px;
    border-radius:50%;
    border:1px solid #000;
}

.comment-box {
    background:#ffffff;
    padding:10px 15px;
    border-radius:10px;
    border:1px solid #000;
    max-width:80%;
}

/* RESPONSIVE */
@media(max-width:900px) {
    .post-body { flex-direction:column; }
    .right {
        border-left:none;
        border-top:1px solid #000;
        padding-right:40px;
    }
}
</style>
</head>

<body>

<div class="post-wrapper">

    <div class="post-header">
        <span onclick="history.back()" style="cursor:pointer">←</span> Post
    </div>

    <div class="post-body">

        <!-- LEFT -->
        <div class="left">

            <div class="user-info">
              <img src="images/ELlipse 1.png">
                <div class="name"><?= $post['user']; ?></div>
            </div>

            <div class="post-content">
                <p class="caption"><?= $post['text']; ?></p>

                <img src="<?= $post['image'] ?>" class="post-img">

              <div class="reacts">

    <div class="react-item like-btn" id="likeBtn">
        <img src="images/like.png">
        <span id="likeCount"><?= $post['likes']; ?></span>
    </div>

    <div class="react-item">
        <img src="images/chat_bubble.png" width="28">
        <span id="commentCount"><?= count($comments) ?></span>
    </div>

</div>

            </div>

        </div>

        <div class="right">
            <h4>Komentar</h4>

            <textarea id="commentInput" placeholder="Tambahkan komentar Anda..."></textarea>
            <button onclick="addComment()">Kirim</button>

            <div id="commentList">
                <?php foreach($comments as $c): ?>
                <div class="comment-item">
              <img src="images/default-profile.jpg">
                    <div class="comment-box"><?= $c['text'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>


<script>
let liked = false;

document.getElementById("likeBtn").onclick = function() {
    liked = !liked;
    let count = parseInt(document.getElementById("likeCount").innerText);

    if (liked) {
        this.classList.add("liked");
        document.getElementById("likeCount").innerText = count + 1;
    } else {
        this.classList.remove("liked");
        document.getElementById("likeCount").innerText = count - 1;
    }
};

function addComment() {
    let text = document.getElementById("commentInput").value.trim();
    if (text === "") return;

    let box = document.createElement("div");
    box.className = "comment-item";
    box.innerHTML = `
        <img src="images/default-profile.jpg">
        <div class="comment-box">${text}</div>
    `;

    document.getElementById("commentList").prepend(box);

    document.getElementById("commentInput").value = "";

    let c = parseInt(document.getElementById("commentCount").innerText);
    document.getElementById("commentCount").innerText = c + 1;
}


</script>

</body>
</html> 
