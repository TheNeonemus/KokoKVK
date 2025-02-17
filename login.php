<?php
session_start(); // Mulakan sesi

// Include db.php untuk sambungan ke database
include('db.php');

// Semak jika form login dihantar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk semak dalam table pelajar
    $sql_pelajar = "SELECT * FROM pelajar WHERE username = ? AND password = ?";
    $stmt_pelajar = $conn->prepare($sql_pelajar);
    $stmt_pelajar->bind_param("ss", $username, $password);
    $stmt_pelajar->execute();
    $result_pelajar = $stmt_pelajar->get_result();

    // Query untuk semak dalam table guru
    $sql_guru = "SELECT * FROM guru WHERE username = ? AND password = ?";
    $stmt_guru = $conn->prepare($sql_guru);
    $stmt_guru->bind_param("ss", $username, $password);
    $stmt_guru->execute();
    $result_guru = $stmt_guru->get_result();

    // Query untuk semak dalam table admin
    $sql_admin = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("ss", $username, $password);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    // Semak hasil dari ketiga-tiga query
    if ($result_pelajar->num_rows > 0) {
        $row = $result_pelajar->fetch_assoc();
        $_SESSION['username'] = $username; // Simpan username dalam sesi
        $_SESSION['peranan'] = 'pelajar'; // Simpan peranan pengguna
        header("Location: pelajar/index.html"); // Redirect ke halaman pelajar
        exit();
    } elseif ($result_guru->num_rows > 0) {
        $row = $result_guru->fetch_assoc();
        $_SESSION['username'] = $username; // Simpan username dalam sesi
        $_SESSION['peranan'] = 'guru'; // Simpan peranan pengguna
        header("Location: guru_dashboard.php"); // Redirect ke halaman guru
        exit();
    } elseif ($result_admin->num_rows > 0) {
        $row = $result_admin->fetch_assoc();
        $_SESSION['username'] = $username; // Simpan username dalam sesi
        $_SESSION['peranan'] = 'admin'; // Simpan peranan pengguna
        header("Location: admin/index.php"); // Redirect ke halaman admin
        exit();
    } else {
        echo "Username atau password salah!";
    }
}
?>
