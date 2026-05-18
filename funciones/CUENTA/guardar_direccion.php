<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["clienteId"])) {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

require_once __DIR__ . "/../../BBDD/connection.php";

$pdo = ConnectionDB::getInstance();

try {

    $clienteId = $_SESSION["clienteId"];

    $nombre = $_POST["nombre"] ?? "";
    $linea2 = $_POST["linea2"] ?? "";
    $calle = $_POST["calle"] ?? "";
    $codpostal = $_POST["codpostal"] ?? "";
    $localidad = $_POST["localidad"] ?? "";
    $pais = $_POST["pais"] ?? "";

    if (!$nombre || !$calle || !$codpostal || !$localidad || !$pais) {
        echo json_encode(["status" => "error", "message" => "Campos obligatorios vacíos"]);
        exit;
    }

    $sql = "INSERT INTO direcciones 
        (clienteId, nombre, linea2, calle, codpostal, localidad, pais, es_principal) 
        VALUES (:clienteId, :nombre, :linea2, :calle, :codpostal, :localidad, :pais, 0)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":clienteId" => $clienteId,
        ":nombre" => $nombre,
        ":linea2" => $linea2,
        ":calle" => $calle,
        ":codpostal" => $codpostal,
        ":localidad" => $localidad,
        ":pais" => $pais
    ]);

    echo json_encode(["status" => "success"]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error servidor",
        "debug" => $e->getMessage()
    ]);
}