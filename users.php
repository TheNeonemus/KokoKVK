<?php
session_start();
include('../db.php'); // Sambungan ke database

// Pastikan admin log masuk
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Tetapkan pilihan tahun dan program
$selected_year = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$selected_program = isset($_GET['program']) ? $_GET['program'] : '';

// Mendapatkan nilai carian dari form
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Senarai program berdasarkan tahun
$program_list = [];
if ($selected_year === "Tahun Satu") {
    $program_list = ['1SVM KMK', '1SVM MTA', '1SVM MTK', '1SVM KSK', '1SVM BAK', '1SVM BPP', '1SVM PPU', '1SVM ETE', '1SVM ETN', '1SVM WTP', '1SVM MPI'];
} elseif ($selected_year === "Tahun Dua") {
    $program_list = ['2SVM KMK', '2SVM MTA', '2SVM MTK', '2SVM KSK', '2SVM BAK', '2SVM BPP', '2SVM PPU', '2SVM ETE', '2SVM ETN', '2SVM WTP', '2SVM MPI'];
}


// Jika program dipilih, dapatkan senarai pelajar berdasarkan tahun dan program
$students = [];
if ($selected_program) {
    // Ubah suai query untuk menyokong carian
    $query = "SELECT id, nama, tahun, program, no_kad_matrik, no_ic, no_fon, username, password 
              FROM pelajar 
              WHERE program = ? 
              AND (nama LIKE ? OR no_ic LIKE ? OR no_kad_matrik LIKE ?)";
    $stmt = $conn->prepare($query);
    
    // Menggunakan wildcard untuk carian berdasarkan nama, IC, atau kad matrik
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param('ssss', $selected_program, $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();

    // Ambil data pelajar
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
    <title>Senarai Pengguna</title>
    <link rel="stylesheet" href="user.css">
    <!-- Sertakan Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="users.css"> <!-- Sesuaikan dengan fail CSS anda -->
</head>

</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Admin</h2>
        <ul>
            <li><a href="../index.php">Dashboard</a></li>
            <li class="active"><a href="users.php">Pengguna</a></li>
            <li><a href="../unit_uniform/uniform.php">Unit Uniform</a></li>
            <li><a href="../kelab_persatuan/kelab_persatuan.php">Kelab & Persatuan</a></li>
            <li><a href="../sukan_permainan/sukan_permainan.php">Sukan & Permainan</a></li>
            <li><a href="reports.php">Laporan</a></li>
            <li><a href="../tetapan.php">Tetapan</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Senarai Pengguna</h1>
            <p>Pilih tahun dan program untuk melihat senarai pelajar.</p>
        </div>

        <div class="year-selection">
            <h2>Pilih Tahun</h2>
            <div class="button-container">
                <a href="users.php?tahun=Tahun Satu" class="btn <?php echo ($selected_year == 'Tahun Satu') ? 'selected' : ''; ?>">Tahun Satu</a>
                <a href="users.php?tahun=Tahun Dua" class="btn <?php echo ($selected_year == 'Tahun Dua') ? 'selected' : ''; ?>">Tahun Dua</a>
            </div>
        </div>

        <?php if ($selected_year && !$selected_program): ?>
        <div class="program-selection">
            <h2>Senarai Program untuk <?php echo htmlspecialchars($selected_year); ?></h2>
            <div class="program-buttons">
                <?php foreach ($program_list as $program): ?>
                    <button onclick="window.location.href='users.php?tahun=<?php echo urlencode($selected_year); ?>&program=<?php echo urlencode($program); ?>'">
                        <?php echo htmlspecialchars($program); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

       <!-- Form Carian -->
<?php if ($selected_program): ?>
    <div class="search-form">
        <form method="GET" action="users.php">
            <input type="hidden" name="tahun" value="<?php echo $selected_year; ?>">
            <input type="hidden" name="program" value="<?php echo $selected_program; ?>">
            <input type="text" name="search" placeholder="Cari pelajar..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <!-- Butang Tambah Pelajar -->
    <div class="add-student">
        <a href="tambah_pelajar.php?tahun=<?php echo urlencode($selected_year); ?>&program=<?php echo urlencode($selected_program); ?>" class="btn">Tambah Pelajar</a>
    </div>
<?php endif; ?>



       <?php
// Paparkan senarai pelajar
if ($selected_program && count($students) > 0): ?>
    <div class="students-list">
        <h2>Senarai Pelajar untuk <?php echo htmlspecialchars($selected_program); ?></h2>
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
                    <th>Aksi</th> <!-- Kolum untuk aksi -->
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
    <!-- Butang Kemaskini menggunakan Font Awesome -->
    <a href="update_pelajar.php?id=<?php echo $student['id']; ?>" class="btn-update">
        <i class="fas fa-edit"></i> <!-- Ikon kemaskini -->
    </a>

    <!-- Butang Padam menggunakan Font Awesome -->
    <a href="padam_pelajar.php?id=<?php echo $student['id']; ?>" class="btn-delete" onclick="return confirm('Adakah anda pasti ingin memadam pelajar ini?');">
        <i class="fas fa-trash"></i> <!-- Ikon padam -->
    </a>
</td>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php elseif ($selected_program): ?>
    <p>Tiada pelajar ditemui untuk program ini.</p>
<?php endif; ?>

</body>
</html>
