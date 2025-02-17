<?php
session_start();
include('../db.php'); // Sambungan ke database

// Pastikan admin log masuk
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Ambil unit uniform yang dipilih
$selected_unit = isset($_GET['unit_uniform']) ? $_GET['unit_uniform'] : '';

// Semak jika borang ditambah
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $tahun = $_POST['tahun'];
    $program = $_POST['program'];
    $no_kad_matrik = $_POST['no_kad_matrik'];
    $no_ic = $_POST['no_ic'];
    $no_fon = $_POST['no_fon'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk masukkan data pelajar ke dalam database
    $query = "INSERT INTO pelajar (nama, tahun, program, no_kad_matrik, no_ic, no_fon, username, password, unit_uniform) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssssss', $nama, $tahun, $program, $no_kad_matrik, $no_ic, $no_fon, $username, $password, $selected_unit);

    if ($stmt->execute()) {
        echo "<script>alert('Pelajar berjaya ditambah!'); window.location.href='uniform.php?unit_uniform=" . urlencode($selected_unit) . "';</script>";
    } else {
        echo "<script>alert('Ralat: " . $stmt->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelajar ke Unit Uniform</title>
    <link rel="stylesheet" href="tambah_pelajar.css">
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Admin</h2>
        <ul>
            <li><a href="../index.php">Dashboard</a></li>
            <li><a href="../pengguna/users.php">Pengguna</a></li>
            <li class="active"><a href="uniform.php">Unit Uniform</a></li>
            <li><a href="../kelab_persatuan/kelab_persatuan.php">Kelab & Persatuan</a></li>
            <li><a href="../sukan_permainan/sukan_permainan.php">Sukan & Permainan</a></li>
            <li><a href="reports.php">Laporan</a></li>
            <li><a href="../tetapan.php">Tetapan</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Tambah Pelajar ke Unit Uniform</h1>
            <p>Masukkan maklumat pelajar untuk unit uniform "<?php echo htmlspecialchars($selected_unit); ?>"</p>
        </div>

        <!-- Borang Tambah Pelajar -->
        <div class="add-student-form">
            <form method="POST" action="tambah_pelajar.php?unit_uniform=<?php echo urlencode($selected_unit); ?>">
                <label for="nama">Nama:</label>
                <input type="text" name="nama" required>

                <label for="no_kad_matrik">No Kad Matrik:</label>
                <input type="text" name="no_kad_matrik" required>

                <label for="no_ic">No IC:</label>
                <input type="text" name="no_ic" required>

                <label for="no_fon">No Telefon:</label>
                <input type="text" name="no_fon" required>

                <button type="submit">Tambah Pelajar</button>
            </form>
        </div>
    </div>
</body>
</html>
