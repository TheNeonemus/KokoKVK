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
$selected_unit = isset($_GET['unit_uniform']) ? $_GET['unit_uniform'] : '';

// Senarai unit uniform
$unit_uniform_list = ['Kadet bomba dan penyelamat', 'Kadet pertahanan awam', 'Kadet remaja sekolah'];

// Senarai pelajar untuk unit uniform yang dipilih
$students = [];
if (!empty($selected_unit)) {
    $query = "SELECT id, nama, tahun, program, no_kad_matrik, no_ic, no_fon, username, password, unit_uniform 
              FROM pelajar 
              WHERE unit_uniform = ? 
              AND (nama LIKE ? OR no_ic LIKE ? OR no_kad_matrik LIKE ?)";
    $stmt = $conn->prepare($query);

    // Wildcard untuk carian
    $search_param = "%" . $search_query . "%";

    $stmt->bind_param('ssss', $selected_unit, $search_param, $search_param, $search_param);
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
    <title>Senarai Pelajar Unit Uniform</title>
    <link rel="stylesheet" href="unit_uniform.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <h1>Senarai Pelajar Unit Uniform</h1>
            <p>Pilih unit uniform untuk melihat senarai pelajar.</p>
        </div>

        <!-- Pilihan Unit Uniform -->
        <div class="unit-selection">
            <h2>Pilih Unit Uniform</h2>
            <div class="unit-buttons">
                <?php foreach ($unit_uniform_list as $unit): ?>
                    <button onclick="window.location.href='uniform.php?unit_uniform=<?php echo urlencode($unit); ?>'">
                        <?php echo htmlspecialchars($unit); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Form Carian -->
        <?php if (!empty($selected_unit)): ?>
        <div class="search-form">
            <form method="GET" action="uniform.php">
                <input type="hidden" name="unit_uniform" value="<?php echo htmlspecialchars($selected_unit); ?>">
                <input type="text" name="search" placeholder="Cari pelajar..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Butang Tambah Pelajar -->
        <div class="add-student">
            <a href="tambah_pelajar.php?unit_uniform=<?php echo urlencode($selected_unit); ?>" class="btn">Tambah Pelajar</a>
        </div>

 <!-- Tambah butang eksport di atas jadual pelajar -->
<div class="export-buttons">
    <a href="export_pdf.php?unit_uniform=<?php echo urlencode($selected_unit); ?>" class="btn-export">Eksport ke PDF</a>
    <a href="export_excel.php?uniform=<?php echo urlencode($selected_unit); ?>" class="btn-export">Eksport ke Excel</a>
</div>


        <!-- Senarai Pelajar -->
        <div class="students-list">
            <?php if (count($students) > 0): ?>
                <h2>Senarai Pelajar untuk Unit Uniform "<?php echo htmlspecialchars($selected_unit); ?>"</h2>
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
                <p>Tiada pelajar ditemui untuk unit uniform ini.</p>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <p>Sila pilih unit uniform terlebih dahulu.</p>
        <?php endif; ?>
    </div>
</body>
</html>
