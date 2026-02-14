<?php
// session_start();
require_once '../config/database.php';
require_once '../functions/auth_functions.php';
require_once '../functions/helper_functions.php';

check_authentication();

// include '../views/layouts/header.php';
?>

<?php
/**
 * File: calculator.php
 * Deskripsi: Halaman Kalkulator Hemat Energi Zenergy
 * Version: 1.0.0
 */

// Data peralatan listrik (tanpa database - versi dummy)
$appliances = array(
    array('id' => 1, 'name' => 'Lampu LED 9W', 'watt' => 9, 'category' => 'Penerangan'),
    array('id' => 2, 'name' => 'Lampu LED 12W', 'watt' => 12, 'category' => 'Penerangan'),
    array('id' => 3, 'name' => 'TV LED 32 inch', 'watt' => 50, 'category' => 'Hiburan'),
    array('id' => 4, 'name' => 'TV LED 43 inch', 'watt' => 80, 'category' => 'Hiburan'),
    array('id' => 5, 'name' => 'Kulkas 1 Pintu', 'watt' => 100, 'category' => 'Dapur'),
    array('id' => 6, 'name' => 'Kulkas 2 Pintu', 'watt' => 150, 'category' => 'Dapur'),
    array('id' => 7, 'name' => 'AC 0.5 PK', 'watt' => 390, 'category' => 'Pendingin'),
    array('id' => 8, 'name' => 'AC 1 PK', 'watt' => 840, 'category' => 'Pendingin'),
    array('id' => 9, 'name' => 'Kipas Angin', 'watt' => 50, 'category' => 'Pendingin'),
    array('id' => 10, 'name' => 'Rice Cooker', 'watt' => 350, 'category' => 'Dapur'),
    array('id' => 11, 'name' => 'Setrika', 'watt' => 350, 'category' => 'Rumah Tangga'),
    array('id' => 12, 'name' => 'Laptop', 'watt' => 65, 'category' => 'Elektronik'),
);

// Default tarif listrik PLN (Rp per kWh)
$default_tariff = 1352; // Tarif R1 900 VA

// Include header
?>
<head>
    <link rel="stylesheet" href="/zenergy/public/css/calculator.css">
</head>


<!-- Tambahkan wrapper class di sini -->
<div class="calc-page-wrapper">
    <div class="calculator-container">
        <!-- Semua konten calculator di sini -->
        <div class="calculator-header">
            <h1>Hitung Potensi Hematmu!</h1>
        </div>
    
    <!-- Form Container -->
    <div class="calculator-form">
        <!-- Step 1: Konsumsi Saat Ini -->
        <div class="form-section" id="step1">
            <div class="step-header">
                <span class="step-number">1</span>
                <h2>Konsumsi Alatmu Sekarang</h2>
            </div>
            
            <div class="form-group">
                <label for="appliance">Peralatan</label>
                <select id="appliance" name="appliance" class="form-control">
                    <option value="">Pilih Peralatan</option>
                    <?php foreach ($appliances as $appliance): ?>
                        <option value="<?php echo $appliance['watt']; ?>" data-name="<?php echo htmlspecialchars($appliance['name']); ?>">
                            <?php echo htmlspecialchars($appliance['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="watt">Watt</label>
                    <input type="number" id="watt" name="watt" class="form-control" placeholder="9" min="1" max="10000">
                </div>
                
                <div class="form-group">
                    <label for="duration">Durasi (jam/hari)</label>
                    <input type="number" id="duration" name="duration" class="form-control" placeholder="8" min="0" max="24" step="0.5">
                </div>
            </div>
            
            <div class="form-group">
                <label for="tariff">Tarif Listrik</label>
                <div class="tariff-input">
                    <span class="currency">Rp</span>
                    <input type="number" id="tariff" name="tariff" class="form-control" value="<?php echo $default_tariff; ?>" min="0" step="0.01">
                    <span class="unit">/kWh</span>
                </div>
                <small class="form-text">Default tarif PLN R1 900 VA</small>
            </div>
        </div>
        
        <!-- Step 2: Pilih Skenario -->
        <div class="form-section" id="step2">
            <div class="step-header">
                <span class="step-number">2</span>
                <h2>Pilih Skenario Perubahan</h2>
            </div>
            
            <div class="scenario-tabs">
                <button class="scenario-btn active" data-scenario="reduce">
                    <i class="fas fa-clock"></i>
                    Kurangi Durasi
                </button>
                <button class="scenario-btn" data-scenario="replace">
                    <i class="fas fa-exchange-alt"></i>
                    Ganti Alat
                </button>
            </div>
            
            <!-- Scenario: Kurangi Durasi -->
            <div class="scenario-content active" id="scenario-reduce">
                <div class="form-group">
                    <label for="new-duration">Durasi Baru (jam/hari)</label>
                    <input type="range" id="new-duration" name="new_duration" class="range-slider" min="1" max="24" value="8" step="0.5">
                    <div class="range-labels">
                        <span>1 jam</span>
                        <span id="duration-value" class="value-display">8 jam</span>
                        <span>24 jam</span>
                    </div>
                </div>
            </div>
            
            <!-- Scenario: Ganti Alat -->
            <div class="scenario-content" id="scenario-replace" style="display: none;">
                <div class="form-group">
                    <label for="new-appliance">Alat Pengganti</label>
                    <select id="new-appliance" name="new_appliance" class="form-control">
                        <option value="">Pilih alat yang lebih hemat</option>
                        <?php foreach ($appliances as $appliance): ?>
                            <option value="<?php echo $appliance['watt']; ?>" data-name="<?php echo htmlspecialchars($appliance['name']); ?>">
                                <?php echo htmlspecialchars($appliance['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <button class="btn-calculate" id="calculate-btn">
                <i class="fas fa-calculator"></i> HITUNG BIAYA DASAR
            </button>
        </div>
    </div>
    
    <!-- Result Section -->
    <div class="result-container" id="result-section" style="display: none;">
        <h2 class="result-title">Hasil & Dampak</h2>
        
        <div class="result-cards">
            <!-- Card: Biaya Saat Ini -->
            <div class="result-card current-cost">
                <h3>Biaya Saat Ini</h3>
                <div class="amount" id="current-cost">Rp 0</div>
                <p class="period">/bulan</p>
            </div>
            
            <!-- Card: Potensi Hemat -->
            <div class="result-card savings">
                <h3>Potensi Hemat</h3>
                <div class="amount" id="savings-amount">Rp 0</div>
                <p class="period">/bulan</p>
            </div>
        </div>
        
        <!-- Environmental Impact -->
        <div class="environmental-impact">
            <div class="impact-header">
                <i class="fas fa-leaf"></i>
                <span>Dampak Lingkungan</span>
            </div>
            <p class="impact-text" id="co2-reduction">
                Setara mengurangi <strong>0 kg CO₂</strong> per tahun
            </p>
        </div>
        
        <button class="btn-save" id="save-btn">
            <i class="fas fa-save"></i> SIMPAN HASIL
        </button>
    </div>
</div>

<?php

?>


<?php
//  include '../views/layouts/footer.php'; 
?>