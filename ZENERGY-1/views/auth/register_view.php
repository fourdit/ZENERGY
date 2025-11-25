<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ZENERGY</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/auth.css">
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
                    <img src="../../public/images/logo-zenergy.png" alt="ZENERGY">
                    <span class="brand-name">ZENERGY</span>
                </div>

                <?php if (isset($flash) && $flash): ?>
                    <div class="alert alert-<?php echo $flash['type']; ?>">
                        <?php echo htmlspecialchars($flash['message']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="../auth/dispatcher_auth.php?fitur=do_register" class="auth-form">
                    <div class="form-group">
                        <label for="name">Nama Pengguna</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password Anda" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Register</button>
                    
                    <div style="text-align: center; margin-top: 10px;">
                        <span style="color: #666;">Sudah punya akun?</span>
                        <a href="../auth/dispatcher_auth.php?fitur=login" class="link">Login di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>