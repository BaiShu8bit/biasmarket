<?php

session_start();

require('../../BBDD/connection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['clienteId'])) {

    echo json_encode([]);

    exit;
}

$db = ConnectionDB::getInstance();

// ==========================
// PEDIDOS
// ==========================

$sql = "

    SELECT *

    FROM pedidos

    WHERE clienteId = :clienteId

    ORDER BY fechaPedido DESC
";

$stmt = $db->prepare($sql);

$stmt->execute([

    ':clienteId' =>
        $_SESSION['clienteId']
]);

$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($pedidos);