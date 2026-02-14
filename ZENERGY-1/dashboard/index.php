<?php
// session_start();
require_once '../config/database.php';
require_once '../functions/helper_functions.php';
require_once '../functions/auth_functions.php';
require_once '../functions/profile_functions.php';

?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . "/data.php";
?>

<?php include "data.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Visualisasi</title>

    <link rel="stylesheet" href="../public/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

</head>

<body class="dashboard-page">
    <div class="dashboard-wrapper"></div>
    <div class="container main">

        <!-- CHART 1 -->
        <div class="card large">
            <div class="card-header">
                <h3>Jumlah kWh</h3>
                <select id="selectYearKwh" class="dropdown-year">
                    <option value="2024" selected>2024</option>
                    <option value="2023">2023</option>
                    <option value="2025">2025</option>
                </select>
            </div>

            <div class="chart-area">
                <canvas id="kwhChart"></canvas>
            </div>
        </div>

        <div class="row">

            <!-- CHART 2 -->
            <div class="card medium">
                <div class="card-header">
                    <h3>Rekap Biaya Listrik</h3>
                    <select id="selectYearBiaya" class="dropdown-year">
                        <option value="2024" selected>2024</option>
                        <option value="2023">2023</option>
                        <option value="2025">2025</option>
                    </select>
                </div>

                <div class="chart-area">
                    <canvas id="biayaChart"></canvas>
                </div>
            </div>

            <!-- CHART 3 -->
            <div class="card small">
                <div class="card-header">
                    <div class="donut-text"></div>
                    <h3>Alat yang Paling Sering Digunakan</h3>
                </div>

                <div class="chart-area">
                    <canvas id="alatChart"></canvas>
                    <div class="donut-desc">
                </div>

            </div>
    </div>

    <script>
    const kwhData = <?= json_encode($kwh); ?>;
    const biayaData = <?= json_encode($biaya); ?>;
    const alatLabels = <?= json_encode(array_keys($alat)); ?>;
    const alatValues = <?= json_encode(array_values($alat)); ?>;
    </script>

    <script src="../public/js/dashboard.js"></script>

</body>
</html>

<?php
include '../views/layouts/footer.php'; 
?>