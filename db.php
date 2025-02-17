<?php
// Gantikan dengan maklumat sambungan yang sesuai
$servername = "localhost";
$username = "root"; // Nama pengguna MySQL
$password = ""; // Kata laluan MySQL
$dbname = "user_auth"; // Nama pangkalan data

// Buat sambungan
$conn = new mysqli($servername, $username, $password, $dbname);

// Semak sambungan
if ($conn->connect_error) {
    die("Sambungan gagal: " . $conn->connect_error);
}
?>
