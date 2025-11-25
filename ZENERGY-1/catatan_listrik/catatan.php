<?php

// File ini HANYA berisi konten (tanpa include header/footer)

// Header & Footer sudah di-include oleh dispatcher_catatan.php

$user = get_logged_in_user();

// Ambil filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'daily';

// Ambil data catatan berdasarkan filter
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : intval(date('n'));
$currentYear  = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

$notes = get_electricity_notes_by_filter($_SESSION['user_id'], $filter, $currentMonth, $currentYear);

$total_cost = calculate_total_cost($notes);

// Ambil flash message
$flash = get_flash_message();

// Helper: dapatkan minggu ke berapa dalam sebuah bulan
function get_week_of_month($date) {
    $firstDay = date('Y-m-01', strtotime($date));
    $day     = date('j', strtotime($date));
    $weekDay = date('N', strtotime($firstDay));
    return intval(ceil(($day + $weekDay - 1) / 7));
}

// ==== GROUPING UNTUK FILTER KHUSUS ==== //
if ($filter === 'weekly') {
    $weeks = [];
    foreach ($notes as $note) {
        $weekNum = get_week_of_month($note['date']);
        $weeks[$weekNum][] = $note;
    }
}
if ($filter === 'monthly') {
    $recap_per_month = [];
    for($m = 1; $m <= 12; $m++) $recap_per_month[$m] = 0;
    foreach($notes as $note) {
        $month = intval(date('n', strtotime($note['date'])));
        $recap_per_month[$month] += $note['total_cost'];
    }
}
if ($filter === 'yearly') {
    $total = calculate_total_cost($notes);
}
?>

<!-- KONTEN HALAMAN -->
<div class="electricity-container">

    <div class="electricity-header">
        <h1>Catatan Listrik</h1>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type']; ?>">
            <i class="bi bi-<?php echo $flash['type'] == 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'; ?>"></i>
            <?php echo htmlspecialchars($flash['message']); ?>
        </div>
    <?php endif; ?>

   <div class="filter-section">
        <?php if ($filter === 'daily' || $filter === 'weekly') : ?>
            <div class="filter-navigation" style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <button class="nav-arrow" onclick="navigateMonth(-1)">
                 &#60;
                </button>
                <span class="current-month" id="current-month">
                    <?php 
                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                        echo "{$months[$currentMonth-1]} {$currentYear}";
                    ?>
                </span>
                <button class="nav-arrow" onclick="navigateMonth(1)">
                    &#62;
                </button>
            </div>
        <?php endif; ?>
        <button onclick="window.location.href='dispatcher_catatan.php?fitur=create'" class="btn btn-primary">
            Buat catatan
        </button>
    </div>

    <div class="filter-tabs">
        <a href="dispatcher_catatan.php?fitur=catatan&filter=daily" 
           class="filter-tab <?php echo $filter === 'daily' ? 'active' : ''; ?>">
            Harian
        </a>
        <a href="dispatcher_catatan.php?fitur=catatan&filter=weekly" 
           class="filter-tab <?php echo $filter === 'weekly' ? 'active' : ''; ?>">
            Mingguan
        </a>
        <a href="dispatcher_catatan.php?fitur=catatan&filter=monthly" 
           class="filter-tab <?php echo $filter === 'monthly' ? 'active' : ''; ?>">
            Bulanan
        </a>
        <a href="dispatcher_catatan.php?fitur=catatan&filter=yearly" 
           class="filter-tab <?php echo $filter === 'yearly' ? 'active' : ''; ?>">
            Tahunan
        </a>
    </div>

    <?php if (empty($notes)): ?>
        <div class="empty-state">
            <p>Belum ada catatan</p>
        </div>
    <?php else: ?>

        <div class="total-cost-section">
            <span class="total-label">Biaya Listrik</span>
            <span class="total-amount"><?= number_format($total_cost, 0, ',', '.'); ?></span>
        </div>

        <div class="notes-list">

        <?php if ($filter === 'weekly'): ?>
            <?php
            $maxWeeks = 5;
            $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
            for ($w = 1; $w <= $maxWeeks; $w++):
                $weekNotes = isset($weeks[$w]) ? $weeks[$w] : [];
                $amount = array_sum(array_column($weekNotes, 'total_cost'));
                // Hitung rentang tanggal (misal: 01.07 ~ 07.07)
                $startDate = date("Y-m-d", strtotime("{$currentYear}-{$currentMonth}-01 +".(($w-1)*7)." days"));
                $endDate = date("Y-m-d", strtotime("{$currentYear}-{$currentMonth}-01 +".(($w*7-1))." days"));
                // Format: 01.07 ~ 07.07
                $start = date('d.m', strtotime($startDate));
                $end = date('d.m', strtotime($endDate));
                $isActive = (date('W', strtotime(date('Y-m-d'))) == $w) ? ' current' : '';
            ?>
            <div class="week-row custom-week-row<?= $isActive ?>">
                <span class="week-badge">Minggu <?= $w ?><br><span class="week-range"><?= $start ?> ~ <?= $end ?></span></span>
                <span class="week-total"><?= number_format($amount, 0, ',', '.') ?></span>
            </div>
            <?php endfor; ?>

       <?php elseif ($filter === 'monthly'): ?>
            <?php
            $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
            for ($m = 1; $m <= 12; $m++):
                $isActive = ($currentMonth == $m) ? ' current' : '';
            ?>
            <div class="month-row custom-month-row<?= $isActive ?>">
                <span class="month-badge<?= $isActive ? ' active' : '' ?>"><?= $monthNames[$m-1] ?></span>
                <span class="month-total"><?= number_format($recap_per_month[$m], 0, ',', '.') ?></span>
            </div>
            <?php endfor; ?>

        <?php elseif ($filter === 'yearly'): ?>
            <div class="year-row custom-year-row current">
                <span class="year-badge"><?= $currentYear ?></span>
                <span class="year-total"><?= number_format($total, 0, ',', '.') ?></span>
            </div>

        <?php else: /* DAILY/default, tampilkan catatan harian format card default */ ?>
            <?php foreach ($notes as $note): ?>
            <div class="note-card" onclick="showActionModal(<?php echo $note['id']; ?>)" style="cursor: pointer;">
                <div class="note-header">
                    <div class="note-date">
                        <span class="date-day"><?php echo date('d', strtotime($note['date'])); ?></span>
                        <span class="date-month-year"><?php echo date('m.Y', strtotime($note['date'])); ?></span>
                        <span class="date-dayname"><?php echo format_day_name($note['date']); ?></span>
                    </div>
                    <div class="note-info">
                        <span>Biaya per kWh: <?php echo number_format($note['price_per_kwh'], 2, ',', '.'); ?></span>
                        <span>Daya rumah: <?php echo number_format($note['house_power']); ?> VA</span>
                        <span class="note-total"><?php echo number_format($note['total_cost'], 0, ',', '.'); ?></span>
                    </div>
                </div>
                <div class="note-items">
                    <?php 
                    $items = get_electricity_items_by_note_id($note['id']);
                    foreach ($items as $item): 
                    ?>
                        <div class="item-row">
                            <span class="item-name"><?php echo htmlspecialchars($item['appliance_name']); ?></span>
                            <span class="item-detail"><?php echo $item['wattage']; ?> watt</span>
                            <span class="item-detail"><?php echo $item['duration_hours']; ?> jam <?php echo $item['duration_minutes']; ?> menit</span>
                            <span class="item-cost"><?php echo number_format($item['cost'], 0, ',', '.'); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<!-- Modal Action -->
<div id="actionModal" class="action-modal" style="display: none;">
    <div class="action-modal-content">
        <h3>Pilih Aksi</h3>
        <div class="action-buttons">
            <button id="editBtn" class="btn btn-edit">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Edit
            </button>
            <button id="deleteBtn" class="btn btn-delete">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Hapus
            </button>
            <button onclick="closeActionModal()" class="btn btn-cancel">Batal</button>
        </div>
    </div>
</div>

<script>
let selectedNoteId = null;

function showActionModal(noteId) {
    selectedNoteId = noteId;
    document.getElementById('actionModal').style.display = 'flex';
    document.getElementById('editBtn').onclick = function() {
        window.location.href = 'dispatcher_catatan.php?fitur=edit&id=' + noteId;
    };
    document.getElementById('deleteBtn').onclick = function() {
        if (confirm('Apakah Anda yakin ingin menghapus catatan ini?')) {
            window.location.href = 'dispatcher_catatan.php?fitur=delete&id=' + noteId;
        }
    };
}

function closeActionModal() {
    document.getElementById('actionModal').style.display = 'none';
    selectedNoteId = null;
}

window.onclick = function(event) {
    const modal = document.getElementById('actionModal');
    if (event.target == modal) {
        closeActionModal();
    }
}

function navigateMonth(direction) {
    let bulan = <?= $currentMonth ?>;
    let tahun = <?= $currentYear ?>;
    bulan = bulan + direction;
    if(bulan < 1) {
        bulan = 12;
        tahun -= 1;
    }
    if(bulan > 12) {
        bulan = 1;
        tahun += 1;
    }
    // Pertahankan filter aktif
    const filter = '<?= $filter ?>';
    window.location.href = `dispatcher_catatan.php?fitur=catatan&filter=${filter}&month=${bulan}&year=${tahun}`;
}
</script>