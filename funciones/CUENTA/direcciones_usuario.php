<?php
session_start();
require('../../BBDD/connection.php');

header('Content-Type: application/json; charset=utf-8');

try {

    // =========================
    // VALIDAR USUARIO LOGUEADO
    // =========================
    if (!isset($_SESSION["clienteId"])) {
        echo json_encode([
            "status" => "error",
            "message" => "No autenticado"
        ]);
        exit;
    }

    $clienteId = $_SESSION["clienteId"];
    $instance = ConnectionDB::getInstance();

    // =========================
    // CONSULTA DIRECCIONES
    // =========================
    $sql = "
        SELECT 
            id,
            clienteId,
            linea1,
            linea2,
            calle,
            cod_postal,
            localidad,
            pais,
            principal
        FROM direcciones
        WHERE clienteId = :clienteId
        ORDER BY principal DESC, id ASC
    ";

    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':clienteId', $clienteId);
    $stmt->execute();

    $direcciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // =========================
    // RESPUESTA
    // =========================
    echo json_encode([
        "status" => "success",
        "data" => $direcciones
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Error servidor",
        "debug" => $e->getMessage()
    ]);
}