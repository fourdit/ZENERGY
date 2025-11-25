<?php
// Ambil data catatan berdasarkan ID
$note_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$note = get_electricity_note_by_id($note_id, $_SESSION['user_id']);

// Jika catatan tidak ditemukan
if (!$note) {
    set_flash_message('error', 'Catatan tidak ditemukan');
    header('Location: dispatcher_catatan.php?fitur=catatan');  // ✅ GANTI
    exit;
}

// Ambil items dari catatan
$items = get_electricity_items_by_note_id($note_id);

$flash = get_flash_message();
?>

<div class="create-note-container">
    <div class="create-note-header">
        <!-- ✅ GANTI: index → catatan -->
        <a href="dispatcher_catatan.php?fitur=catatan" class="back-button">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        <h1>Edit Catatan</h1>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type']; ?>">
            <?php echo htmlspecialchars($flash['message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="dispatcher_catatan.php?fitur=update" id="note-form" class="note-form">
        <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
        
        <!-- Rest of form... sama seperti sebelumnya -->
        <div class="form-top-section">
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Tanggal</label>
                    <input type="date" id="date" name="date" value="<?php echo $note['date']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="price_per_kwh">Harga per kWh</label>
                    <input type="number" id="price_per_kwh" name="price_per_kwh" value="<?php echo $note['price_per_kwh']; ?>" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="house_power">Daya listrik rumah</label>
                    <div class="input-with-unit">
                        <input type="number" id="house_power" name="house_power" value="<?php echo $note['house_power']; ?>" required>
                        <span class="unit">VA</span>
                    </div>
                </div>
            </div>
        </div>

        <div id="items-container">
            <?php foreach ($items as $index => $item): ?>
                <div class="item-box" data-index="<?php echo $index; ?>">
                    <button type="button" class="remove-item" onclick="removeItem(this)">×</button>
                    
                    <div class="item-box-header">
                        <div class="form-group">
                            <label>Nama Alat</label>
                            <input type="text" name="items[<?php echo $index; ?>][appliance_name]" value="<?php echo htmlspecialchars($item['appliance_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" name="items[<?php echo $index; ?>][quantity]" value="<?php echo $item['quantity']; ?>" min="1" required>
                        </div>
                    </div>

                    <div class="item-box-body">
                        <div class="form-group">
                            <label>Durasi</label>
                            <div class="duration-inputs">
                                <input type="number" name="items[<?php echo $index; ?>][duration_hours]" value="<?php echo $item['duration_hours']; ?>" min="0" max="23" placeholder="Jam" required>
                                <span>:</span>
                                <input type="number" name="items[<?php echo $index; ?>][duration_minutes]" value="<?php echo $item['duration_minutes']; ?>" min="0" max="59" placeholder="Menit" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Daya alat</label>
                            <div class="input-with-unit">
                                <input type="number" name="items[<?php echo $index; ?>][wattage]" value="<?php echo $item['wattage']; ?>" min="0" required>
                                <span class="unit">watt</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" class="btn btn-add-item" onclick="addItem()">+</button>

        <button type="submit" class="btn btn-primary btn-submit">Simpan Perubahan</button>
    </form>
</div>

<script>
let itemIndex = <?php echo count($items); ?>;

function addItem() {
    const container = document.getElementById('items-container');
    const newItem = document.createElement('div');
    newItem.className = 'item-box';
    newItem.setAttribute('data-index', itemIndex);
    
    newItem.innerHTML = `
        <button type="button" class="remove-item" onclick="removeItem(this)">×</button>
        
        <div class="item-box-header">
            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" name="items[${itemIndex}][appliance_name]" required>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" required>
            </div>
        </div>

        <div class="item-box-body">
            <div class="form-group">
                <label>Durasi</label>
                <div class="duration-inputs">
                    <input type="number" name="items[${itemIndex}][duration_hours]" min="0" max="23" placeholder="Jam" value="0" required>
                    <span>:</span>
                    <input type="number" name="items[${itemIndex}][duration_minutes]" min="0" max="59" placeholder="Menit" value="0" required>
                </div>
            </div>
            <div class="form-group">
                <label>Daya alat</label>
                <div class="input-with-unit">
                    <input type="number" name="items[${itemIndex}][wattage]" min="0" required>
                    <span class="unit">watt</span>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newItem);
    itemIndex++;
}

function removeItem(button) {
    const itemBox = button.closest('.item-box');
    const container = document.getElementById('items-container');
    
    if (container.children.length > 1) {
        itemBox.remove();
    } else {
        alert('Minimal harus ada satu alat!');
    }
}
</script>