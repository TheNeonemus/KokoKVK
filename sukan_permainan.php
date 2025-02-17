<?php
session_start();
include('../db.php'); // Sambungan ke database

// Pastikan admin log masuk
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Nilai default untuk parameter carian
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$selected_sport = isset($_GET['sukan_permainan']) ? $_GET['sukan_permainan'] : '';

// Senarai sukan & permainan
$sport_list = ['Bola Sepak', 'Ping Pong', 'E-Sukan', 'Sepak Takraw', 'Ragbi', 'Futsal', 'Bola Tampar','Bola Jaring', 'Pentaque'];

// Senarai pelajar untuk sukan & permainan yang dipilih
$students = [];
if (!empty($selected_sport)) {
    $query = "SELECT id, nama, tahun, program, no_kad_matrik, no_ic, no_fon, username, password, sukan_permainan 
              FROM pelajar 
              WHERE sukan_permainan = ? 
              AND (nama LIKE ? OR no_ic LIKE ? OR no_kad_matrik LIKE ?)";
    $stmt = $conn->prepare($query);

    // Wildcard untuk carian
    $search_param = "%" . $search_query . "%";

    $stmt->bind_param('ssss', $selected_sport, $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Pelajar Sukan & Permainan</title>
    <link rel="stylesheet" href="sukan_permainan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Admin</h2>
        <ul>
            <li><a href="../index.php">Dashboard</a></li>
            <li><a href="../pengguna/users.php">Pengguna</a></li>
            <li><a href="../unit_uniform/uniform.php">Unit Uniform</a></li>
            <li><a href="../kelab_persatuan/kelab_persatuan.php">Kelab & Persatuan</a></li>
            <li class="active"><a href="sukan_permainan.php">Sukan & Permainan</a></li>
            <li><a href="reports.php">Laporan</a></li>
            <li><a href="../tetapan.php">Tetapan</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Senarai Pelajar Sukan & Permainan</h1>
            <p>Pilih sukan & permainan untuk melihat senarai pelajar.</p>
        </div>

        <!-- Pilihan Sukan & Permainan -->
        <div class="sukan-selection">
            <h2>Pilih Sukan & Permainan</h2>
            <div class="sukan-buttons">
                <?php foreach ($sport_list as $sport): ?>
                    <button onclick="window.location.href='sukan_permainan.php?sukan_permainan=<?php echo urlencode($sport); ?>'">
                        <?php echo htmlspecialchars($sport); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Form Carian -->
        <?php if (!empty($selected_sport)): ?>
        <div class="search-form">
            <form method="GET" action="sukan_permainan.php">
                <input type="hidden" name="sukan_permainan" value="<?php echo htmlspecialchars($selected_sport); ?>">
                <input type="text" name="search" placeholder="Cari pelajar..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Butang Tambah Pelajar -->
        <div class="add-student">
            <a href="tambah_pelajar.php?sukan_permainan=<?php echo urlencode($selected_sport); ?>" class="btn">Tambah Pelajar</a>
        </div>

        <!-- Tambah butang eksport di atas jadual pelajar -->
<div class="export-buttons">
    <a href="export_pdf.php?sukan_permainan=<?php echo urlencode($selected_sport); ?>" class="btn-export">Eksport ke PDF</a>
    <a href="export_excel.php?sukan_permainan=<?php echo urlencode($selected_sport); ?>" class="btn-export">Eksport ke Excel</a>
</div>


        <!-- Senarai Pelajar -->
        <div class="students-list">
            <?php if (count($students) > 0): ?>
                <h2>Senarai Pelajar untuk Sukan & Permainan "<?php echo htmlspecialchars($selected_sport); ?>"</h2>
                <table class="student-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tahun</th>
                            <th>Program</th>
                            <th>No Kad Matrik</th>
                            <th>No IC</th>
                            <th>No Telefon</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $index => $student): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($student['nama']); ?></td>
                                <td><?php echo htmlspecialchars($student['tahun']); ?></td>
                                <td><?php echo htmlspecialchars($student['program']); ?></td>
                                <td><?php echo htmlspecialchars($student['no_kad_matrik']); ?></td>
                                <td><?php echo htmlspecialchars($student['no_ic']); ?></td>
                                <td><?php echo htmlspecialchars($student['no_fon']); ?></td>
                                <td><?php echo htmlspecialchars($student['username']); ?></td>
                                <td><?php echo htmlspecialchars($student['password']); ?></td>
                                <td>
                                    <a href="update_pelajar.php?id=<?php echo $student['id']; ?>" class="btn-update">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="padam_pelajar.php?id=<?php echo $student['id']; ?>" class="btn-delete" onclick="return confirm('Adakah anda pasti ingin memadam pelajar ini?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tiada pelajar ditemui untuk sukan & permainan ini.</p>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <p>Sila pilih sukan & permainan terlebih dahulu.</p>
        <?php endif; ?>
    </div>
</body>
</html>
