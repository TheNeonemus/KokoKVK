<?php
session_start();
include('../../db.php'); // Sambungan ke database

// Pastikan pengguna telah log masuk
if (!isset($_SESSION['username'])) {
    echo "Sila log masuk untuk mendaftar sukan dan permainan.";
    exit();
}

// Tetapkan had maksimum untuk setiap sukan
$sukan_had = [
    "Bola Sepak" => 71,
    "Ping Pong" => 71,
    "E-Sukan" => 71,
    "Sepak Takraw" => 71,
    "Ragbi" => 71,
    "Futsal" => 71,
    "Bola Tampar" => 71,
    "Bola Jaring" => 71,
    "Petanque" => 71,
];

// Ambil bilangan pelajar yang sudah mendaftar bagi setiap sukan
$sukan_status = [];
foreach ($sukan_had as $sukan => $had) {
    $sql = "SELECT COUNT(*) as jumlah FROM pelajar WHERE sukan_permainan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $sukan);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $sukan_status[$sukan] = $data['jumlah'] ?? 0;
}

// Ambil username pengguna dari sesi
$username = $_SESSION['username'];

// Semak jika form dihantar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sukan = $_POST['sukan'] ?? '';

    // Semak jika sukan mempunyai had yang mencukupi
    if (isset($sukan_had[$sukan]) && $sukan_status[$sukan] < $sukan_had[$sukan]) {
        $sql_update = "UPDATE pelajar SET sukan_permainan = ? WHERE username = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $sukan, $username);

        if ($stmt_update->execute()) {
            header("Location: berjaya.html");
            exit();
        } else {
            echo "Pendaftaran gagal: " . $stmt_update->error;
        }
    } else {
        echo "Pendaftaran gagal: Sukan '$sukan' sudah mencapai had maksimum.";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Sukan dan Permainan</title>
    <link rel="stylesheet" href="sukan.css">
</head>
<body>
    <div class="form-container">
        <h2>Pendaftaran Sukan dan Permainan</h2>
        <form action="" method="POST">
            <label for="sukan">Pilih Sukan:</label>
            <select name="sukan" id="sukan" required>
                <option value="" disabled selected>-- Pilih sukan --</option>
                <?php foreach ($sukan_had as $sukan => $had): ?>
                    <option value="<?= htmlspecialchars($sukan) ?>" 
                        <?= $sukan_status[$sukan] >= $had ? 'disabled' : '' ?>>
                        <?= htmlspecialchars($sukan) ?> 
                        (<?= $sukan_status[$sukan] ?>/<?= $had ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Daftar</button>
        </form>
        <p><a href="index.html">Kembali ke Dashboard</a></p>
    </div>
</body>
</html>
