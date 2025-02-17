<?php
session_start();
include('../db.php'); // Sambungan ke database

// Pastikan admin log masuk
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Dapatkan ID pelajar dari parameter URL atau POST
$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : '');
$message = ''; // Untuk mesej status

if ($id) {
    // Ambil data pelajar berdasarkan ID
    $query = "SELECT * FROM pelajar WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pelajar = $result->fetch_assoc();

    if (!$pelajar) {
        echo "Pelajar tidak ditemui.";
        exit();
    }
} else {
    echo "ID pelajar tidak disediakan.";
    exit();
}

// Proses kemaskini pelajar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $no_kad_matrik = $_POST['no_kad_matrik'];
    $no_ic = $_POST['no_ic'];
    $no_fon = $_POST['no_fon'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Update rekod dalam database
    $query = "UPDATE pelajar SET nama = ?, no_kad_matrik = ?, no_ic = ?, no_fon = ?, username = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssi', $nama, $no_kad_matrik, $no_ic, $no_fon, $username, $password, $id);

    if ($stmt->execute()) {
        header("Location: update_pelajar.php?id=$id&status=success");
        exit();
    } else {
        $message = "Ralat: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Pelajar</title>
    <link rel="stylesheet" href="update_pelajar.css">
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Admin</h2>
        <ul>
            <li><a href="../index.php">Dashboard</a></li>
            <li><a href="users.php">Pengguna</a></li>
            <li><a href="unit_uniform.php">Unit Uniform</a></li>
            <li><a href="kelab_persatuan.php">Kelab & Persatuan</a></li>
            <li><a href="sukan_permainan.php">Sukan & Permainan</a></li>
            <li><a href="reports.php">Laporan</a></li>
            <li><a href="../tetapan.php">Tetapan</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Kemaskini Pelajar</h1>

        <!-- Tambahkan makluman kemaskini berjaya di sini -->
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="success">Maklumat pelajar berjaya dikemaskini!</div>
        <?php endif; ?>

        <!-- Borang untuk kemaskini pelajar -->
        <form method="POST" action="update_pelajar.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo $pelajar['nama']; ?>" required>

            <label for="no_kad_matrik">No Kad Matrik:</label>
            <input type="text" id="no_kad_matrik" name="no_kad_matrik" value="<?php echo $pelajar['no_kad_matrik']; ?>" required>

            <label for="no_ic">No IC:</label>
            <input type="text" id="no_ic" name="no_ic" value="<?php echo $pelajar['no_ic']; ?>" required>

            <label for="no_fon">No Telefon:</label>
            <input type="text" id="no_fon" name="no_fon" value="<?php echo $pelajar['no_fon']; ?>" required>

            <button type="submit">Kemaskini</button>
        </form>

        <!-- Butang kembali ke halaman senarai pelajar -->
        <div class="button-container">
            <a href="uniform.php?tahun=" class="back-button">← Kembali</a>
        </div>
    </div>
</body>
</html>
