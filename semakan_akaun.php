<?php
session_start(); // Mulakan sesi
include('../../db.php'); // Sambungkan ke database

// Semak sama ada pelajar log masuk
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

// Dapatkan username dari sesi
$username = $_SESSION['username'];

// Dapatkan maklumat pelajar dari database
$sql = "SELECT * FROM pelajar WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Maklumat tidak dijumpai!";
    exit();
}

// Tetapkan maklumat pelajar dengan nilai default jika tiada
$nama = isset($row['nama']) && !empty($row['nama']) ? $row['nama'] : "Tidak tersedia";
$tahun = isset($row['tahun']) && !empty($row['tahun']) ? $row['tahun'] : "Tidak tersedia";
$program = isset($row['program']) && !empty($row['program']) ? $row['program'] : "Tidak tersedia";
$no_kad_matrik = isset($row['no_kad_matrik']) && !empty($row['no_kad_matrik']) ? $row['no_kad_matrik'] : "Tidak tersedia";
$no_ic = isset($row['no_ic']) && !empty($row['no_ic']) ? $row['no_ic'] : "Tidak tersedia";
$no_fon = isset($row['no_fon']) && !empty($row['no_fon']) ? $row['no_fon'] : "Tidak tersedia";
$username = isset($row['username']) && !empty($row['username']) ? $row['username'] : "Tidak tersedia";
$password = isset($row['password']) && !empty($row['password']) ? $row['password'] : "Tidak tersedia";

// Tetapkan maklumat kokurikulum dengan nilai default jika tiada
$unit_uniform = isset($row['unit_uniform']) && !empty($row['unit_uniform']) ? $row['unit_uniform'] : "Belum Terdaftar";
$kelab_persatuan = isset($row['kelab_persatuan']) && !empty($row['kelab_persatuan']) ? $row['kelab_persatuan'] : "Belum Terdaftar";
$sukan_permainan = isset($row['sukan_permainan']) && !empty($row['sukan_permainan']) ? $row['sukan_permainan'] : "Belum Terdaftar";
?>
