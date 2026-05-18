<?php

session_start();

require_once "../../BBDD/connection.php";

require "../../LIBRERIAS/pdf/fpdf.php";

$db = ConnectionDB::getInstance();

$idPedido = $_GET["id"] ?? null;

if (!$idPedido) {

    die("Pedido no válido");
}

// ======================================
// OBTENER PEDIDO
// ======================================

$sql = "

    SELECT *

    FROM pedidos

    WHERE pedidoId = ?
";

$stmt = $db->prepare($sql);

$stmt->execute([$idPedido]);

$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {

    die("Pedido no encontrado");
}

// ======================================
// PDF
// ======================================

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 20);

$pdf->Cell(0, 15, 'BIASMARKET', 0, 1, 'C');

$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 14);

$pdf->Cell(0, 10, 'Resumen del pedido', 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);

$pdf->Cell(50, 10, 'ID Pedido:', 0, 0);

$pdf->Cell(100, 10, $pedido["pedidoId"], 0, 1);

$pdf->Cell(50, 10, 'Fecha:', 0, 0);

$pdf->Cell(100, 10, $pedido["fechaPedido"], 0, 1);

$pdf->Cell(50, 10, 'Vendedor:', 0, 0);

$pdf->Cell(100, 10, 'BiasMarket', 0, 1);

$pdf->Cell(50, 10, 'Total:', 0, 0);

$pdf->Cell(100, 10, $pedido["totalPedido"] . " EUR", 0, 1);

$pdf->Ln(10);

$pdf->SetFont('Arial', 'I', 10);

$pdf->Cell(
    0,
    10,
    'Gracias por comprar en BiasMarket',
    0,
    1,
    'C'
);

// ======================================
// DESCARGAR
// ======================================

$pdf->Output(
    'D',
    'pedido_' . $pedido["pedidoId"] . '.pdf'
);