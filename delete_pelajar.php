<?php
session_start();
include('../db.php');

// Pastikan admin log masuk
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

// Dapatkan ID pelajar yang perlu dipadam
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Padam pelajar dari database
    $sql_delete = "DELETE FROM pelajar WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: tetapan.php");
        exit();
    } else {
        echo "Gagal memadam pelajar.";
    }
}

$conn->close();
?>
