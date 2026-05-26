<?php

require('../../BBDD/connection.php');

header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['clienteId'])) {

    echo json_encode(["error" => "No autorizado"]);
    exit;
}

$clienteId = $_SESSION['clienteId'];

$instance = ConnectionDB::getInstance();

/*
====================================
OBTENER PEDIDOS DEL CLIENTE
====================================
*/

$sql = "SELECT * 
        FROM pedidos 
        WHERE clienteId = :clienteId
        ORDER BY fechaPedido DESC";

$stmt = $instance->prepare($sql);
$stmt->bindValue(':clienteId', $clienteId);
$stmt->execute();

$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($pedidos);
exit;