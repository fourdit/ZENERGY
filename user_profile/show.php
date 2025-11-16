<?php
// GANTI: get_current_user() → get_logged_in_user()
$user = get_logged_in_user();

if (!$user) {
    set_flash_message('error', 'User tidak ditemukan');
    header('Location: ../auth/dispatcher_auth.php?fitur=logout');
    exit;
}

$flash = get_flash_message();
include '../views/layouts/header.php';
?>

<div class="profile-container">
    <a href="../public/index.php?page=dashboard" class="back-button">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type']; ?>">
            <i class="bi bi-<?php echo $flash['type'] == 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'; ?>"></i>
            <?php echo htmlspecialchars($flash['message']); ?>
        </div>
    <?php endif; ?>

    <div class="profile-card">
        <h1 class="profile-title">Profil</h1>
        
        <div class="profile-content">
            <div class="profile-left">
                <img src="<?php echo get_profile_photo_url($user['profile_photo_path']); ?>" alt="<?php echo htmlspecialchars($user['name']); ?>" class="profile-photo-large">
                <h2 class="profile-name"><?php echo htmlspecialchars($user['name']); ?></h2>
            </div>

            <div class="profile-right">
                <h2 class="biodata-title">Biodata</h2>
                
                <div class="biodata-grid">
                    <div class="biodata-item">
                        <span class="biodata-label">Nama</span>
                        <span class="biodata-value"><?php echo htmlspecialchars($user['name']); ?></span>
                    </div>

                    <div class="biodata-item">
                        <span class="biodata-label">Email</span>
                        <span class="biodata-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>

                    <div class="biodata-item">
                        <span class="biodata-label">Domisili</span>
                        <span class="biodata-value"><?php echo $user['domisili'] ? htmlspecialchars($user['domisili']) : '-'; ?></span>
                    </div>
                </div>

                <button onclick="window.location.href='dispatcher_profile.php?fitur=edit'" class="btn btn-primary">
                    Edit profile
                </button>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>