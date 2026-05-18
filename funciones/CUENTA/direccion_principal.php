<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . "/../../BBDD/connection.php";

$pdo = ConnectionDB::getInstance();

if (!isset($_SESSION["clienteId"])) {
    echo json_encode(null);
    exit;
}

$clienteId = $_SESSION["clienteId"];

$sql = "SELECT * FROM direcciones 
        WHERE clienteId = :clienteId 
        ORDER BY es_principal DESC, fecha_creacion DESC
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([":clienteId" => $clienteId]);

$direccion = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($direccion);