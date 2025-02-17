<?php
require_once('../db.php'); // Sambungan ke database
require_once('../lib/fpdf/fpdf.php'); // Pastikan anda muat turun dan masukkan FPDF

// Ambil unit uniform yang dipilih
$selected_unit = isset($_GET['unit_uniform']) ? $_GET['unit_uniform'] : '';

if (!empty($selected_unit)) {
    // Dapatkan data pelajar dari database
    $query = "SELECT nama, tahun, program, no_kad_matrik, no_ic, no_fon FROM pelajar WHERE unit_uniform = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $selected_unit);
    $stmt->execute();
    $result = $stmt->get_result();

    // Mulakan PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(190, 10, 'Senarai Pelajar - ' . $selected_unit, 0, 1, 'C');
    $pdf->Ln(10);

    // Header Jadual
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 10, 'No', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Nama', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Tahun', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Program', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Kad Matrik', 1, 0, 'C');
    $pdf->Cell(40, 10, 'No IC', 1, 0, 'C');
    $pdf->Ln();

    // Data Jadual
    $pdf->SetFont('Arial', '', 12);
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        // Tentukan baris tertinggi
        $cellData = [
            $i++,
            $row['nama'],
            $row['tahun'],
            $row['program'],
            $row['no_kad_matrik'],
            $row['no_ic']
        ];
        $cellWidths = [10, 50, 30, 30, 30, 40];
        $lineHeight = 10;

        // Hitung bilangan baris maks bagi satu row
        $maxLines = 0;
        foreach ($cellData as $key => $text) {
            $maxLines = max($maxLines, $pdf->GetStringWidth($text) / $cellWidths[$key]);
        }
        $rowHeight = ceil($maxLines) * $lineHeight;

        // Cetak baris
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        foreach ($cellData as $key => $text) {
            $pdf->MultiCell($cellWidths[$key], $lineHeight, $text, 1, 'C');
            $x += $cellWidths[$key];
            $pdf->SetXY($x, $y);
        }
        $pdf->Ln($rowHeight);
    }

    // Papar PDF
    $pdf->Output();
}
?>
