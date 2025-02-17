<?php
require_once('../db.php'); // Sambungan ke database

// Ambil sukan yang dipilih
$selected_sport = isset($_GET['sukan_permainan']) ? $_GET['sukan_permainan'] : '';

if (!empty($selected_sport)) {
    // Dapatkan data pelajar dari database
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="senarai_pelajar_' . $selected_sport . '.xls"');

    $query = "SELECT nama, tahun, program, no_kad_matrik, no_ic, no_fon FROM pelajar WHERE sukan_permainan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $selected_sport);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "No\tNama\tTahun\tProgram\tKad Matrik\tNo IC\n";
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        echo $i++ . "\t" . $row['nama'] . "\t" . $row['tahun'] . "\t" . $row['program'] . "\t" . $row['no_kad_matrik'] . "\t" . $row['no_ic'] . "\n";
    }
}
?>
