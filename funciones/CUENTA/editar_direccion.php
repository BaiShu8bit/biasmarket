<?php
session_start();
require('../../BBDD/connection.php');

header('Content-Type: application/json; charset=utf-8');

try {

    // =========================
    // VALIDAR SESIÓN
    // =========================
    if (!isset($_SESSION["clienteId"])) {
        echo json_encode([
            "status" => "error",
            "message" => "No autenticado"
        ]);
        exit;
    }

    $clienteId = $_SESSION["clienteId"];

    // =========================
    // VALIDAR INPUTS
    // =========================
    $required = ['linea1', 'calle', 'codpostal', 'pais'];

    foreach ($required as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === "") {
            echo json_encode([
                "status" => "error",
                "message" => "Faltan datos obligatorios"
            ]);
            exit;
        }
    }

    // =========================
    // LIMPIEZA DE DATOS
    // =========================
    $linea1 = trim($_POST['linea1']);
    $linea2 = isset($_POST['linea2']) ? trim($_POST['linea2']) : null;
    $calle = trim($_POST['calle']);
    $codpostal = trim($_POST['codpostal']);
    $pais = trim($_POST['pais']);

    // separar codpostal y localidad si vienen juntos
    $parts = explode(" ", $codpostal, 2);
    $cp = $parts[0] ?? "";
    $localidad = $parts[1] ?? "";

    // =========================
    // CONEXIÓN
    // =========================
    $instance = ConnectionDB::getInstance();

    // =========================
    // COMPROBAR SI YA EXISTE DIRECCIÓN
    // =========================
    $stmt = $instance->prepare("
        SELECT id 
        FROM direcciones 
        WHERE clienteId = :clienteId 
        LIMIT 1
    ");
    $stmt->bindValue(':clienteId', $clienteId);
    $stmt->execute();

    $existe = $stmt->fetch(PDO::FETCH_ASSOC);

    // =========================
    // INSERT O UPDATE
    // =========================
    if ($existe) {

        // UPDATE
        $stmt = $instance->prepare("
            UPDATE direcciones
            SET 
                linea1 = :linea1,
                linea2 = :linea2,
                calle = :calle,
                cod_postal = :cod_postal,
                localidad = :localidad,
                pais = :pais
            WHERE id = :id AND clienteId = :clienteId
        ");

        $stmt->bindValue(':id', $existe['id']);
        $stmt->bindValue(':clienteId', $clienteId);

    } else {

        // INSERT
        $stmt = $instance->prepare("
            INSERT INTO direcciones 
            (clienteId, linea1, linea2, calle, cod_postal, localidad, pais, principal)
            VALUES
            (:clienteId, :linea1, :linea2, :calle, :cod_postal, :localidad, :pais, 1)
        ");

        $stmt->bindValue(':clienteId', $clienteId);
    }

    // =========================
    // BIND COMÚN
    // =========================
    $stmt->bindValue(':linea1', $linea1);
    $stmt->bindValue(':linea2', $linea2);
    $stmt->bindValue(':calle', $calle);
    $stmt->bindValue(':cod_postal', $cp);
    $stmt->bindValue(':localidad', $localidad);
    $stmt->bindValue(':pais', $pais);

    // =========================
    // EJECUTAR
    // =========================
    if ($stmt->execute()) {

        echo json_encode([
            "status" => "success",
            "message" => "Dirección guardada correctamente"
        ]);
        exit;

    } else {

        echo json_encode([
            "status" => "error",
            "message" => "No se pudo guardar la dirección"
        ]);
        exit;
    }

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Error servidor",
        "debug" => $e->getMessage()
    ]);
}