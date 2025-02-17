<?php
session_start();
include('../../db.php'); // Sambungan database

// Pastikan pelajar telah log masuk
if (!isset($_SESSION['username'])) {
    echo "Tiada sesi username. Sila log masuk semula.";
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];

// Debug nilai session
// echo "Sesi username: $username<br>";

// Query untuk ambil maklumat pelajar
$sql = "SELECT * FROM pelajar WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Ambil maklumat kokurikulum (jika ada)
    $unit_beruniform = !empty($row['unit_uniform']) ? $row['unit_uniform'] : 'Belum Terdaftar';
    $kelab_dan_persatuan = !empty($row['kelab_persatuan']) ? $row['kelab_persatuan'] : 'Belum Terdaftar';
    $sukan_permainan = !empty($row['sukan_permainan']) ? $row['sukan_permainan'] : 'Belum Terdaftar';
} else {
    echo "Maklumat tidak dijumpai dalam database untuk username: $username.<br>";
    echo "Debug SQL Query: SELECT * FROM pelajar WHERE username = '$username'";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semakan Akaun Pelajar</title>
    <style>
        /* Tetapan asas */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Container utama */
        .container {
            width: 80%;
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Tajuk */
        h2 {
            text-align: center;
            color: #5c6bc0;
            font-size: 24px;
            margin-bottom: 30px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #5c6bc0;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        td {
            background-color: #fafafa;
        }

        td:hover {
            background-color: #f0f0f0;
        }

        /* Styling untuk link */
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #5c6bc0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #3f51b5;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Maklumat Akaun Pelajar</h2>
    <table>
        <tr>
            <th>Nama</th>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
        </tr>
        <tr>
            <th>Tahun</th>
            <td><?php echo htmlspecialchars($row['tahun']); ?></td>
        </tr>
        <tr>
            <th>Program</th>
            <td><?php echo htmlspecialchars($row['program']); ?></td>
        </tr>
        <tr>
            <th>No Kad Matrik</th>
            <td><?php echo htmlspecialchars($row['no_kad_matrik']); ?></td>
        </tr>
        <tr>
            <th>No IC</th>
            <td><?php echo htmlspecialchars($row['no_ic']); ?></td>
        </tr>
        <tr>
            <th>No Telefon</th>
            <td><?php echo htmlspecialchars($row['no_fon']); ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
        </tr>
        <tr>
            <th>Password</th>
            <td><?php echo htmlspecialchars($row['password']); ?></td>
        </tr>
        <tr>
            <th>Unit Beruniform</th>
            <td><?php echo htmlspecialchars($unit_beruniform); ?></td>
        </tr>
        <tr>
            <th>Kelab dan Persatuan</th>
            <td><?php echo htmlspecialchars($kelab_dan_persatuan); ?></td>
        </tr>
        <tr>
            <th>Sukan dan Permainan</th>
            <td><?php echo htmlspecialchars($sukan_permainan); ?></td>
        </tr>
    </table>
    <a href="index.html">Kembali ke Halaman Pelajar</a>
</div>
</body>
</html>
