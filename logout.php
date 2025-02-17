<?php
// Mulakan sesi
session_start();

// Hapuskan semua data dalam sesi
session_unset();

// Hapuskan sesi sepenuhnya
session_destroy();

// Arahkan pengguna balik ke halaman login
header("Location: ../index.html"); // Gantikan dengan lokasi login anda jika perlu
exit();
?>
