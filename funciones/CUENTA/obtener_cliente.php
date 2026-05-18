<?php
session_start();
require('../../BBDD/connection.php');

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["clienteId"])) {
    echo json_encode([
        "status" => "error",
        "message" => "No autenticado"
    ]);
    exit;
}

$clienteId = $_SESSION["clienteId"];
$instance = ConnectionDB::getInstance();

$stmt = $instance->prepare("
    SELECT nombre, apellido1, apellido2, email, fecha_alta, pais, nombre_usuario
    FROM clientes
    WHERE clienteId = :id
");

$stmt->bindValue(':id', $clienteId);
$stmt->execute();

$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "data" => $cliente
]);