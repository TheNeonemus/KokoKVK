<?php
session_start();
include('../db.php'); // Sambungan ke database

// Pastikan admin log masuk
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

$username = $_SESSION['username'];

// Dapatkan nama admin
$sql = "SELECT nama FROM admin WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_name = $row['nama'];
} else {
    echo "Nama admin tidak ditemui.";
    exit();
}

// Fungsi carian pelajar
$pelajar = [];
if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    // Mengemaskini query untuk mengambil lebih banyak medan
    $sql_search = "SELECT * FROM pelajar WHERE nama LIKE ? OR no_kad_matrik LIKE ? OR program LIKE ?";
    $stmt = $conn->prepare($sql_search);
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $result_search = $stmt->get_result();
    while ($row = $result_search->fetch_assoc()) {
        $pelajar[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetapan - Cari Pelajar</title>
    <link rel="stylesheet" href="tetapan.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="pengguna/users.php">Pengguna</a></li>
            <li><a href="unit_uniform/uniform.php">Unit Uniform</a></li>
            <li><a href="kelab_persatuan/kelab_persatuan.php">Kelab & Persatuan</a></li>
            <li><a href="sukan_permainan/sukan_permainan.php">Sukan & Permainan</a></li>
            <li><a href="reports.php">Laporan</a></li>
            <li><a href="tetapan.php">Tetapan</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Tetapan</h1>
            <p>Cari pelajar berdasarkan nama, nombor kad matrik atau program.</p>
        </div>
        
        <div class="search-container">
            <form method="GET" action="tetapan.php">
                <input type="text" name="search" placeholder="Cari pelajar..." required>
                <button type="submit">Cari</button>
            </form>
        </div>

        <?php if (!empty($pelajar)) : ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tahun</th>
                    <th>Program</th>
                    <th>No Kad Matrik</th>
                    <th>No IC</th>
                    <th>No Fon</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Unit Uniform</th>
                    <th>Kelab & Persatuan</th>
                    <th>Sukan & Permainan</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($pelajar as $p) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($p['nama']); ?></td>
                        <td><?php echo htmlspecialchars($p['tahun']); ?></td>
                        <td><?php echo htmlspecialchars($p['program']); ?></td>
                        <td><?php echo htmlspecialchars($p['no_kad_matrik']); ?></td>
                        <td><?php echo htmlspecialchars($p['no_ic']); ?></td>
                        <td><?php echo htmlspecialchars($p['no_fon']); ?></td>
                        <td><?php echo htmlspecialchars($p['username']); ?></td>
                        <td><?php echo htmlspecialchars($p['password']); ?></td>
                        <td><?php echo htmlspecialchars($p['unit_uniform']); ?></td>
                        <td><?php echo htmlspecialchars($p['kelab_persatuan']); ?></td>
                        <td><?php echo htmlspecialchars($p['sukan_permainan']); ?></td>
                        <td>
                            <!-- Butang Update -->
                            <a href="update_pelajar.php?id=<?php echo $p['id']; ?>">Update</a>
                            <!-- Butang Padam -->
                            <a href="delete_pelajar.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Adakah anda pasti mahu memadam pelajar ini?')">Padam</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php elseif (isset($_GET['search'])) : ?>
            <p>Tiada pelajar ditemui.</p>
        <?php endif; ?>
    </div>
</body>
</html>

