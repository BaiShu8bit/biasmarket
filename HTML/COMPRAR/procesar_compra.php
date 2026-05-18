<?php

session_start();

require('../../BBDD/connection.php');

header('Content-Type: application/json');

// ==========================
// CONEXIÓN PDO
// ==========================

$db = ConnectionDB::getInstance();

// ==========================
// LOGIN
// ==========================

if (!isset($_SESSION['clienteId'])) {

    echo json_encode([
        "success" => false,
        "message" => "Usuario no autenticado"
    ]);

    exit;
}

// ==========================
// RECIBIR JSON
// ==========================

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$carrito = $data['carrito'] ?? [];

// ==========================
// CARRITO VACÍO
// ==========================

if (empty($carrito)) {

    echo json_encode([
        "success" => false,
        "message" => "Carrito vacío"
    ]);

    exit;
}

// ==========================
// TOTAL
// ==========================

$total = 0;

foreach ($carrito as $item) {

    $total +=
        floatval($item['precio']) *
        intval($item['cantidad']);
}

// ==========================
// INSERT PEDIDO
// ==========================

$sql = "

    INSERT INTO pedidos
    (
        clienteId,
        totalPedido
    )

    VALUES
    (
        :clienteId,
        :totalPedido
    )
";

$stmt = $db->prepare($sql);

$stmt->execute([

    ':clienteId' =>
        $_SESSION['clienteId'],

    ':totalPedido' =>
        $total
]);

// ID pedido
$pedidoId = $db->lastInsertId();

// ==========================
// INSERT DETALLES
// ==========================

foreach ($carrito as $item) {

    $sqlDetalle = "

        INSERT INTO pedidos_detalle
        (
            pedidoId,
            publicacionId,
            cantidad,
            precio
        )

        VALUES
        (
            :pedidoId,
            :publicacionId,
            :cantidad,
            :precio
        )
    ";

    $stmtDetalle =
        $db->prepare($sqlDetalle);

    $stmtDetalle->execute([

        ':pedidoId' =>
            $pedidoId,

        ':publicacionId' =>
            $item['publicacionId'],

        ':cantidad' =>
            $item['cantidad'],

        ':precio' =>
            $item['precio']
    ]);
}

// ==========================
// SUCCESS
// ==========================

echo json_encode([
    "success" => true
]);