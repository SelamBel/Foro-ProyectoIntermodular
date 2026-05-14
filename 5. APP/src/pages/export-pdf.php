<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once __DIR__ . '/../assets/lib/fpdf.php';
require_once __DIR__ . '/../models/Publication.php';

$pubModel = new Publication();

$order  = in_array($_GET['order'] ?? '', ['votes', 'newest', 'oldest']) ? $_GET['order'] : 'newest';
$search = trim($_GET['search'] ?? '');
$items  = $pubModel->getAllFiltered($order, $search);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'AntHive - Listado de publicaciones', 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 6, 'Generado el ' . date('d/m/Y H:i') . ' por ' . $_SESSION['name'], 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10,  8, '#',        1, 0, 'C', true);
$pdf->Cell(70,  8, 'Titulo',   1, 0, 'L', true);
$pdf->Cell(40,  8, 'Autor',    1, 0, 'L', true);
$pdf->Cell(20,  8, 'Votos',    1, 0, 'C', true);
$pdf->Cell(25,  8, 'Coments.', 1, 0, 'C', true);
$pdf->Cell(25,  8, 'Fecha',    1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
$fill = false;
foreach ($items as $i => $post) {
    $pdf->SetFillColor(245, 245, 245);
    $votes = (int)$post['upvotes'] - (int)$post['downvotes'];
    $date  = date('d/m/Y', strtotime($post['date_creation']));
    $pdf->Cell(10,  7, $i + 1, 1, 0, 'C', $fill);
    $pdf->Cell(70,  7, iconv('UTF-8', 'windows-1252//TRANSLIT', mb_strimwidth($post['title'], 0, 50, '...')), 1, 0, 'L', $fill);
    $pdf->Cell(40,  7, iconv('UTF-8', 'windows-1252//TRANSLIT', $post['name'] . ' ' . $post['surname']), 1, 0, 'L', $fill);
    $pdf->Cell(20,  7, $votes, 1, 0, 'C', $fill);
    $pdf->Cell(25,  7, $post['comment_count'], 1, 0, 'C', $fill);
    $pdf->Cell(25,  7, $date, 1, 1, 'C', $fill);
    $fill = !$fill;
}

$pdf->Ln(4);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 6, 'Total: ' . count($items) . ' publicaciones', 0, 1, 'R');

$pdf->Output('D', 'anthive_publicaciones_' . date('Ymd') . '.pdf');
