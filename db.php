<?php
$servername = "localhost";
$username = "root"; // Tukar jika anda gunakan username lain
$password = ""; // Tukar jika ada password untuk MySQL
$database = "user_auth";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Sambungan gagal: " . $conn->connect_error);
}
?>
