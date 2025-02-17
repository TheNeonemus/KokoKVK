<?php
session_start();
include('../../db.php'); // Sambungan ke database

// Pastikan pengguna telah log masuk
if (!isset($_SESSION['username'])) {
    echo "Sila log masuk untuk mendaftar unit uniform.";
    exit();
}

// Tetapkan had maksimum untuk setiap unit uniform
$uniform_had = [
    "Kadet bomba dan penyelamat" => 213,
    "Kadet pertahanan awam" => 213,
    "Kadet Remaja Sekolah" => 213,
];

// Ambil bilangan pelajar yang sudah mendaftar bagi setiap unit uniform
$uniform_status = [];
foreach ($uniform_had as $uniform => $had) {
    $sql = "SELECT COUNT(*) as jumlah FROM pelajar WHERE unit_uniform = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uniform);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $uniform_status[$uniform] = $data['jumlah'] ?? 0;
}

// Ambil username pengguna dari sesi
$username = $_SESSION['username'];

// Semak jika form dihantar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uniform = $_POST['uniform'] ?? '';

    // Semak jika unit uniform mempunyai had yang mencukupi
    if (isset($uniform_had[$uniform]) && $uniform_status[$uniform] < $uniform_had[$uniform]) {
        $sql_update = "UPDATE pelajar SET unit_uniform = ? WHERE username = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $uniform, $username);

        if ($stmt_update->execute()) {
            header("Location: berjaya.html");
            exit();
        } else {
            echo "Pendaftaran gagal: " . $stmt_update->error;
        }
    } else {
        echo "Pendaftaran gagal: Unit uniform '$uniform' sudah mencapai had maksimum.";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Unit Uniform</title>
    <link rel="stylesheet" href="uniform.css">
</head>
<body>
    <div class="form-container">
        <h2>Pendaftaran Unit Uniform</h2>
        <form action="" method="POST">
            <label for="uniform">Pilih Unit Uniform:</label>
            <select name="uniform" id="uniform" required>
                <option value="" disabled selected>-- Pilih unit uniform --</option>
                <?php foreach ($uniform_had as $uniform => $had): ?>
                    <option value="<?= htmlspecialchars($uniform) ?>" 
                        <?= $uniform_status[$uniform] >= $had ? 'disabled' : '' ?>>
                        <?= htmlspecialchars($uniform) ?> 
                        (<?= $uniform_status[$uniform] ?>/<?= $had ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Daftar</button>
        </form>
        <p><a href="index.html">Kembali ke Dashboard</a></p>
    </div>
</body>
</html>
