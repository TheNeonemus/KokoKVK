<?php
// Pastikan pengguna log masuk sebagai pelajar
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pelajar') {
    header("Location: index.html"); // Arahkan balik ke login jika tiada session
    exit();
}

// Simpan nama pengguna dalam session untuk digunakan dalam HTML
$_SESSION['username'] = $_SESSION['username'];
?>
