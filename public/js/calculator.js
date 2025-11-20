/**
 * File: js/calculator.js
 * Deskripsi: JavaScript untuk Kalkulator Hemat Energi Zenergy (data dummy, tanpa backend)
 * Version: 1.1.0 (perbaikan parsing angka dan pengecekan ID)
 */

document.addEventListener('DOMContentLoaded', function() {
    initCalculator();
});

function parseNum(val) {
    if (typeof val === "string") {
        return parseFloat(val.replace(',', '.').replace(/[^0-9.]/g, ''));
    } else {
        return parseFloat(val);
    }
}

function initCalculator() {
    const applianceSelect = document.getElementById('appliance');
    const wattInput = document.getElementById('watt');
    const durationInput = document.getElementById('duration');
    const tariffInput = document.getElementById('tariff');
    const newDurationSlider = document.getElementById('new-duration');
    const durationValue = document.getElementById('duration-value');
    const calculateBtn = document.getElementById('calculate-btn');
    const resultSection = document.getElementById('result-section');
    const saveBtn = document.getElementById('save-btn');
    const scenarioBtns = document.querySelectorAll('.scenario-btn');
    const scenarioContents = document.querySelectorAll('.scenario-content');

    // Auto-fill watt ketika pilih alat di dropdown
    if (applianceSelect) {
        applianceSelect.addEventListener('change', function() {
            const selectedWatt = this.value;
            if (selectedWatt && wattInput) {
                wattInput.value = selectedWatt;
            }
        });
    }

    // Update slider value label
    if (newDurationSlider && durationValue) {
        durationValue.textContent = (newDurationSlider.value || 1) + ' jam';
        newDurationSlider.addEventListener('input', function() {
            durationValue.textContent = this.value + ' jam';
        });
    }

    // Scenario tab switching
    scenarioBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const scenario = this.getAttribute('data-scenario');
            scenarioBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            scenarioContents.forEach(function(content) {
                if (content.id === 'scenario-' + scenario) {
                    content.style.display = 'block';
                    content.classList.add('active');
                } else {
                    content.style.display = 'none';
                    content.classList.remove('active');
                }
            });
        });
    });

    // Kalkulasi ketika tombol hitung ditekan
    if (calculateBtn) {
        calculateBtn.addEventListener('click', function() {
            calculateEnergyCost();
        });
    }

    // Simpan hasil simulasi memakai localStorage (hanya sisi client)
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            saveCalculation();
        });
    }
}

function calculateEnergyCost() {
    // Pastikan ID inputan tetap!
    const watt = parseNum(document.getElementById('watt').value);
    const duration = parseNum(document.getElementById('duration').value);
    const tariff = parseNum(document.getElementById('tariff').value);

    // Debug cek nilai
    // console.log('DEBUG:', {watt, duration, tariff});

    if (isNaN(watt) || isNaN(duration) || isNaN(tariff)) {
        showNotification('Mohon lengkapi semua field!', 'warning');
        return;
    }
    if (watt <= 0 || duration <= 0 || tariff <= 0) {
        showNotification('Nilai harus lebih dari 0!', 'error');
        return;
    }

    const activeScenarioBtn = document.querySelector('.scenario-btn.active');
    if (!activeScenarioBtn) {
        showNotification('Pilih skenario terlebih dahulu!', 'warning');
        return;
    }
    const activeScenario = activeScenarioBtn.getAttribute('data-scenario');

    let newDuration, newWatt;
    if (activeScenario === 'reduce') {
        newDuration = parseNum(document.getElementById('new-duration').value);
        newWatt = watt;
        if (!newDuration || newDuration >= duration) {
            showNotification('Durasi baru harus lebih kecil dari durasi saat ini dan lebih dari 0!', 'warning');
            return;
        }
    } else { // replace
        const newApplianceSelect = document.getElementById('new-appliance');
        newWatt = parseNum(newApplianceSelect.value);
        newDuration = duration;
        if (isNaN(newWatt) || newWatt <= 0) {
            showNotification('Pilih alat pengganti!', 'warning');
            return;
        }
        if (newWatt >= watt) {
            showNotification('Alat pengganti harus lebih hemat energi!', 'warning');
            return;
        }
    }

    // Kalkulasi biaya dan dampak lingkungan (per bulan)
    const currentKwhPerDay = (watt * duration) / 1000;
    const currentCostPerDay = currentKwhPerDay * tariff;
    const currentCostPerMonth = currentCostPerDay * 30;

    const newKwhPerDay = (newWatt * newDuration) / 1000;
    const newCostPerDay = newKwhPerDay * tariff;
    const newCostPerMonth = newCostPerDay * 30;

    const savingsPerMonth = currentCostPerMonth - newCostPerMonth;
    const kwhSavedPerYear = (currentKwhPerDay - newKwhPerDay) * 365;
    const co2ReductionKg = kwhSavedPerYear * 0.85;

    displayResults(currentCostPerMonth, savingsPerMonth, co2ReductionKg);

    // Simpan hasil kalkulasi di jendela JS (untuk save ke localStorage)
    window.calculationData = {
        watt: watt,
        duration: duration,
        tariff: tariff,
        newWatt: newWatt,
        newDuration: newDuration,
        currentCost: currentCostPerMonth,
        savings: savingsPerMonth,
        co2Reduction: co2ReductionKg
    };
}

function displayResults(currentCost, savings, co2Reduction) {
    document.getElementById('current-cost').textContent = formatCurrency(currentCost);
    document.getElementById('savings-amount').textContent = formatCurrency(savings > 0 ? savings : 0);
    document.getElementById('co2-reduction').innerHTML = `Setara mengurangi <strong>${co2Reduction.toFixed(1)} kg CO₂</strong> per tahun`;
    const resultSection = document.getElementById('result-section');
    resultSection.style.display = 'block';
    setTimeout(function() {
        resultSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
    showNotification('Perhitungan berhasil!', 'success');
}

function saveCalculation() {
    if (!window.calculationData) {
        showNotification('Belum ada data untuk disimpan!', 'warning');
        return;
    }
    const data = window.calculationData;
    const timestamp = new Date().toLocaleString('id-ID');
    let history = JSON.parse(localStorage.getItem('calculationHistory') || '[]');
    history.push({
        ...data,
        timestamp: timestamp
    });
    localStorage.setItem('calculationHistory', JSON.stringify(history));
    showNotification('Hasil berhasil disimpan! ✓', 'success');
    // console.log('Saved calculation:', data);
}

function formatCurrency(amount) {
    return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
}

function showNotification(message, type) {
    const existingNotif = document.querySelector('.notification');
    if (existingNotif) existingNotif.remove();
    const notif = document.createElement('div');
    notif.className = 'notification notification-' + type;
    notif.innerHTML = `
        <i class="fas fa-${getNotificationIcon(type)}"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(notif);
    setTimeout(() => notif.classList.add('show'), 10);
    setTimeout(() => {
        notif.classList.remove('show');
        setTimeout(() => notif.remove(), 300);
    }, 3000);
}

function getNotificationIcon(type) {
    switch(type) {
        case 'success': return 'check-circle';
        case 'error': return 'times-circle';
        case 'warning': return 'exclamation-triangle';
        default: return 'info-circle';
    }
}
