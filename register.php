<?php
include '../db.php'; // Pastikan path ke fail database betul

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Masukkan data guru ke dalam table guru
    $sql = "INSERT INTO guru (name, phone, email, username, password) VALUES ('$name', '$phone', '$email', '$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Pendaftaran berjaya! <a href='../login/index.html'>Log Masuk</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
