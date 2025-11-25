// =======================
//  DATA LIST (3 TAHUN)
// =======================

function getMaxYear(selectId) {
    const sel = document.getElementById(selectId);
    const years = [];

    for (let i = 0; i < sel.options.length; i++) {
        let v = sel.options[i].value.trim();
        let n = parseInt(v, 10);
        if (!isNaN(n)) years.push(n);
    }

    return Math.max(...years);
}


// KWH
const kwhList = {
    "2025": kwhData,
    "2024": [140,200,280,350,500,450,380,420,500,580,600,300],
    "2023": [120,180,250,300,420,300,260,280,350,420,480,260]
};

// Biaya
const biayaList = {
    "2025": biayaData,
    "2024": [300,320,500,720,900,850,700,650,900,1300,1600,450],
    "2023": [250,300,400,530,700,600,450,500,700,1100,1400,380]
};


// ===================================================
// 1) LINE CHART — KWH
// ===================================================
const kwhChart = new Chart(document.getElementById("kwhChart"), {
    type: "line",
    data: {
        labels: ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"],
        datasets: [{
            data: kwhData,
            borderColor: "#ff7b00",
            backgroundColor: "rgba(255,123,0,0.12)",
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: "#fff",
            pointBorderColor: "#ff7b00",
            pointBorderWidth: 2,
            tension: 0.35,
            fill: true
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                enabled: true,
                backgroundColor: "#ff7b00",
                titleColor: "#fff",
                bodyColor: "#fff",
                displayColors: false,
                padding: 10,
                callbacks: { label: (ctx) => ctx.raw + " kWh" }
            }
        },
        scales: {
            y: {
                min: 0,
                max: 900,
                ticks: { stepSize: 100 },
                grid: { color: "rgba(0,0,0,0.15)" }
            },
            x: { grid: { display: false } }
        }
    }
});

document.getElementById("selectYearKwh").addEventListener("change", function () {
    let selectedYear = Number(this.value);
    let maxYear = getMaxYear("selectYearKwh");

    // Kalau tahun paling baru → data belum lengkap
    if (selectedYear === maxYear) {
        alert(`Data kWh tahun ${maxYear} belum lengkap.`);
        const previousYear = maxYear - 1;

        this.value = previousYear; // Kembalikan ke tahun sebelumnya

        kwhChart.data.datasets[0].data = kwhList[previousYear];
        kwhChart.update();
        return;
    }

    // Kalau pilih tahun 2024 / 2023
    kwhChart.data.datasets[0].data = kwhList[selectedYear];
    kwhChart.update();
});

// ===================================================
// 2) BAR CHART — BIAYA
// ===================================================
const biayaChart = new Chart(document.getElementById("biayaChart"), {
    type: "bar",
    data: {
        labels: ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"],
        datasets: [{
            data: biayaData,
            backgroundColor: "#ff7b00",
            borderRadius: 8
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                enabled: true,
                backgroundColor: "#ff7b00",
                titleColor: "#fff",
                bodyColor: "#fff",
                displayColors: false,
                callbacks: {
                    label: (ctx) => "Rp " + ctx.raw.toLocaleString("id-ID")
                }
            }
        },
        scales: {
            y: {
                min: 0,
                max: 2000,
                ticks: { stepSize: 200 },
                grid: { color: "rgba(0,0,0,0.15)", borderDash: [4,4] }
            },
            x: { grid: { display: false } }
        }
    }
});

document.getElementById("selectYearBiaya").addEventListener("change", function () {
    let selectedYear = Number(this.value);
    let maxYear = getMaxYear("selectYearBiaya");

    const biaya2024 = [250,350,650,500,600,1200,700,500,650,900,1500,300];
    const biaya2023 = [180,350,650,750,1100,1400,850,480,650,1100,1200,280];

    if (selectedYear === maxYear) {
        alert(`Data biaya tahun ${maxYear} belum lengkap.`);
        const previousYear = maxYear - 1;
        this.value = previousYear;

        biayaChart.data.datasets[0].data =
            previousYear === 2024 ? biaya2024 : biaya2023;
        biayaChart.update();
        return;
    }

    biayaChart.data.datasets[0].data =
        selectedYear === 2024 ? biaya2024 : biaya2023;

    biayaChart.update();
});

// ===================================================
// 3) DOUGHNUT — ALAT
// ===================================================
const alatChart = new Chart(document.getElementById("alatChart"), {
    type: "doughnut",
    data: {
        labels: alatLabels,
        datasets: [{
            data: alatValues,
            backgroundColor: ["#ffa726","#fb8c00","#ef6c00"],
            hoverOffset: 6
        }]
    },
    options: {
        maintainAspectRatio: false,
        cutout: "60%",
        plugins: {
            legend: { display: false },
            tooltip: {
                enabled: true,
                backgroundColor: "#ff7b00",
                titleColor: "#fff",
                bodyColor: "#fff",
                displayColors: false,
                callbacks: {
                    label: (ctx) => ctx.raw + "%"
                }
            }
        }
    }
});
