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

<div class="profile-edit-overlay">
    <div class="profile-edit-modal">
        <a href="dispatcher_profile.php?fitur=show" class="modal-close-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

        <div class="profile-edit-content">
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <?php echo htmlspecialchars($flash['message']); ?>
                </div>
            <?php endif; ?>

            <div class="profile-photo-section">
                <img src="<?php echo get_profile_photo_url($user['profile_photo_path']); ?>" alt="<?php echo htmlspecialchars($user['name']); ?>" class="profile-photo-edit" id="preview-photo">
                <label for="profile_photo" class="change-photo-label">Ganti Foto Profil</label>
            </div>

            <h2 class="modal-title">Biodata</h2>

            <form method="POST" action="dispatcher_profile.php?fitur=update" enctype="multipart/form-data" class="profile-form">
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="display: none;" onchange="previewImage(event)">

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="domisili">Domisili</label>
                    <input type="text" id="domisili" name="domisili" value="<?php echo htmlspecialchars($user['domisili'] ?? ''); ?>" placeholder="Masukkan kota domisili">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    <small class="input-note">Email tidak dapat diubah</small>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('preview-photo');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php include '../views/layouts/footer.php'; ?>