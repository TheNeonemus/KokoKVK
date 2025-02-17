<?php
session_start();
include('../../db.php'); // Sambungan ke database

// Pastikan pengguna telah log masuk
if (!isset($_SESSION['username'])) {
    echo "Sila log masuk untuk mendaftar kelab dan persatuan.";
    exit();
}

// Tetapkan had maksimum untuk setiap kelab
$kelab_had = [
    "Rukun Negara" => 59,
    "Pencegahan Jenayah" => 59,
    "Keusahawanan" => 59,
    "Koperasi" => 59,
    "Bahasa" => 59,
    "Sains dan Matematik" => 59,
    "Inovasi dan Rekacipta" => 59,
    "Peers" => 59,
    "Pusat sumber" => 59,
    "SPBT" => 59,
    "Media" => 59,
];

// Ambil bilangan pelajar yang sudah mendaftar bagi setiap kelab
$kelab_status = [];
foreach ($kelab_had as $kelab => $had) {
    $sql = "SELECT COUNT(*) as jumlah FROM pelajar WHERE kelab_persatuan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kelab);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $kelab_status[$kelab] = $data['jumlah'] ?? 0;
}

// Ambil username pengguna dari sesi
$username = $_SESSION['username'];

// Semak jika form dihantar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kelab = $_POST['kelab'] ?? '';

    // Semak jika kelab mempunyai had yang mencukupi
    if (isset($kelab_had[$kelab]) && $kelab_status[$kelab] < $kelab_had[$kelab]) {
        $sql_update = "UPDATE pelajar SET kelab_persatuan = ? WHERE username = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $kelab, $username);

        if ($stmt_update->execute()) {
            header("Location: berjaya.html");
            exit();
        } else {
            echo "Pendaftaran gagal: " . $stmt_update->error;
            echo '<br><br>';
            echo '<a href="index.html"><button>Kembali</button></a>'; // Butang kembali ke halaman tertentu
        }
    } else {
        echo "Pendaftaran gagal: Kelab '$kelab' sudah mencapai had maksimum.";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kelab dan Persatuan</title>
    <link rel="stylesheet" href="kelab.css">
</head>
<body>
    <div class="form-container">
        <h2>Pendaftaran Kelab dan Persatuan</h2>
        <form action="" method="POST">
            <label for="kelab">Pilih Kelab:</label>
            <select name="kelab" id="kelab" required>
                <option value="" disabled selected>-- Pilih kelab --</option>
                <?php foreach ($kelab_had as $kelab => $had): ?>
                    <option value="<?= htmlspecialchars($kelab) ?>" 
                        <?= $kelab_status[$kelab] >= $had ? 'disabled' : '' ?>>
                        <?= htmlspecialchars($kelab) ?> 
                        (<?= $kelab_status[$kelab] ?>/<?= $had ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Daftar</button>
        </form>
        <p><a href="index.html">Kembali ke Dashboard</a></p>
    </div>
</body>
</html>
