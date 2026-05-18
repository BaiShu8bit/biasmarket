<?php
session_start();
require('../../BBDD/connection.php');

header('Content-Type: application/json; charset=utf-8');

try {

    // =========================
    // VALIDAR INPUT
    // =========================
    if (!isset($_POST['clienteId'])) {
        echo json_encode([
            "status" => "error",
            "message" => "clienteId no enviado"
        ]);
        exit;
    }

    $clienteId = $_POST['clienteId'];

    // =========================
    // CONEXIÓN
    // =========================
    $db = ConnectionDB::getInstance();

    // =========================
    // QUERY CLIENTE
    // =========================
    $stmt = $db->prepare("
        SELECT 
            clienteId,
            clienteGuid,
            nombre,
            apellido1,
            apellido2,
            fecha_nacimiento,
            fecha_alta,
            pais,
            email,
            nombre_usuario,
            activo,
            rol
        FROM clientes
        WHERE clienteId = :clienteId
        LIMIT 1
    ");

    $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
    $stmt->execute();

    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    // =========================
    // VALIDAR RESULTADO
    // =========================
    if (!$cliente) {
        echo json_encode([
            "status" => "error",
            "message" => "Cliente no encontrado"
        ]);
        exit;
    }

    // =========================
    // RESPUESTA
    // =========================
    echo json_encode([
        "status" => "success",
        "data" => $cliente
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Error servidor",
        "debug" => $e->getMessage()
    ]);
}