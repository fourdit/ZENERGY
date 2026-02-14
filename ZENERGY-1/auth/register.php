<?php
// Cek jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../public/index.php?page=dashboard');
    exit;
}

// Ambil flash message
$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ZENERGY</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <h1 class="welcome-title">Selamat Datang!</h1>
            <p class="welcome-subtitle">Mari bergabung menjadi<br>penhemat<br>listrik yang handal.</p>
        </div>
        
        <div class="auth-right">
            <div class="auth-card">
                <div class="auth-logo">
                    <img src="../public/images/logo-zenergy.png" alt="ZENERGY">
                    <span class="brand-name">ZENERGY</span>
                </div>

                <?php if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type']; ?>">
                        <?php echo htmlspecialchars($flash['message']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="dispatcher_auth.php?fitur=do_register" class="auth-form">
                    <div class="form-group">
                        <label for="name">Nama Pengguna</label>
                        <input type="text" id="name" name="name" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>