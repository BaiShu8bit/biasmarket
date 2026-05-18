<?php
require('../../LIBRERIAS/pdf/fpdf.php');
require('../../BBDD/connection.php');

if (isset($_REQUEST['clienteId']) && isset($_REQUEST['nombre_baraja'])) {
    $clienteId = $_REQUEST['clienteId'];
    $nombre_baraja = $_REQUEST['nombre_baraja'];

    $resultado = imprimir_baraja($clienteId, $nombre_baraja);
}

function imprimir_baraja($clienteId, $nombre_baraja)
{
    $instance = ConnectionDB::getInstance();

    // Obtener los datos del cliente
    $sql = 'SELECT nombre, apellido1, apellido2 FROM clientes WHERE clienteId = :clienteId';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    // Formatear el nombre completo
    $playerName = $cliente['nombre'] . ' ' . $cliente['apellido1'] . ' ' . $cliente['apellido2'];

    // Obtener la fecha actual en formato dia/mes/año
    $date = date('d/m/Y');

    // Obtener los datos de la baraja
    $sql = 'SELECT nombre_carta, tipo_carta, COUNT(*) AS cantidad_carta, zona_carta FROM barajas WHERE clienteId = :clienteId AND nombre_baraja = :nombre_baraja GROUP BY nombre_carta, tipo_carta, zona_carta';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
    $stmt->bindValue(':nombre_baraja', $nombre_baraja, PDO::PARAM_STR);
    $stmt->execute();
    $baraja = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($baraja)) {
        echo "No se encontraron cartas para esta baraja.";
        exit;
    }

    // Crear el PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Información del jugador
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Yu-Gi-Oh! Deck List', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Player: $playerName", 0, 1);
    $pdf->Cell(0, 10, "Date: $date", 0, 1);
    $pdf->Ln(10);

    // Clasificar las cartas en categorías
    $monstruos = [];
    $magicas = [];
    $trampas = [];
    $extra = [];
    $side = [];

    foreach ($baraja as $carta) {
        switch ($carta['zona_carta']) {
            case 'main':
                if (strpos($carta['tipo_carta'], 'Monster') !== false) {
                    $monstruos[] = $carta;
                } elseif (strpos($carta['tipo_carta'], 'Spell') !== false) {
                    $magicas[] = $carta;
                } elseif (strpos($carta['tipo_carta'], 'Trap') !== false) {
                    $trampas[] = $carta;
                }
                break;
            case 'extra':
                $extra[] = $carta;
                break;
            case 'side':
                $side[] = $carta;
                break;
        }
    }

    // Crear tres columnas para el Main Deck
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Monstruos', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Magicas', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Trampas', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    $maxRows = max(count($monstruos), count($magicas), count($trampas));
    for ($i = 0; $i < $maxRows; $i++) {
        $pdf->Cell(60, 10, isset($monstruos[$i]) ? "{$monstruos[$i]['nombre_carta']} ({$monstruos[$i]['cantidad_carta']})" : '', 1, 0, 'C');
        $pdf->Cell(60, 10, isset($magicas[$i]) ? "{$magicas[$i]['nombre_carta']} ({$magicas[$i]['cantidad_carta']})" : '', 1, 0, 'C');
        $pdf->Cell(60, 10, isset($trampas[$i]) ? "{$trampas[$i]['nombre_carta']} ({$trampas[$i]['cantidad_carta']})" : '', 1, 1, 'C');
    }

    $pdf->Ln(10);

    // Extra Deck y Side Deck
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 10, 'Extra Deck', 1, 0, 'C');
    $pdf->Cell(90, 10, 'Side Deck', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    $maxRows = max(count($extra), count($side));
    for ($i = 0; $i < $maxRows; $i++) {
        $pdf->Cell(90, 10, isset($extra[$i]) ? "{$extra[$i]['nombre_carta']} ({$extra[$i]['cantidad_carta']})" : '', 1, 0, 'C');
        $pdf->Cell(90, 10, isset($side[$i]) ? "{$side[$i]['nombre_carta']} ({$side[$i]['cantidad_carta']})" : '', 1, 1, 'C');
    }

    try {
        $pdf->Output('F', __DIR__ . '/DeckList.pdf');
    } catch (Exception $e) {
        echo 'Error al guardar el archivo PDF: ' . $e->getMessage();
        exit;
    }

    echo '/funciones/CUENTA/DeckList.pdf';
    exit;
}
