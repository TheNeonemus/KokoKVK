<?php
session_start();
include('../db.php'); // Sambungan ke database

// Pastikan admin log masuk
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Dapatkan ID pelajar yang hendak dipadam
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk memadam pelajar dari database
    $query = "DELETE FROM pelajar WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Pastikan parameter tahun dan program wujud untuk redirect
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
        $program = isset($_GET['program']) ? $_GET['program'] : '';

        // Redirect dengan mesej status
        header("Location: uniform.php?tahun=$tahun&program=$program&status=deleted");
        exit();
    } else {
        // Paparkan ralat jika gagal memadam
        echo "Ralat: " . $stmt->error;
    }
} else {
    echo "ID pelajar tidak disediakan.";
    exit();

    
}
?>
