<?php
require('../../BBDD/connection.php');
session_start();

header('Content-Type: application/json; charset=utf-8');

try {

    if (
        !isset(
            $_POST['nombre'],
            $_POST['apellido1'],
            $_POST['fecha_nacimiento'],
            $_POST['pais'],
            $_POST['email'],
            $_POST['usuario'],
            $_POST['password']
        )
    ) {
        echo json_encode([
            "status" => "error",
            "message" => "Datos incompletos"
        ]);
        exit;
    }

    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $pais = $_POST['pais'];
    $email = $_POST['email'];
    $nombre_usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // fecha
    $fecha_obj = date_create_from_format('d/m/Y', $_POST['fecha_nacimiento']);
    if (!$fecha_obj) {
        echo json_encode([
            "status" => "error",
            "message" => "Formato de fecha inválido"
        ]);
        exit;
    }

    $fecha_nacimiento = $fecha_obj->format('Y-m-d');
    /* $fecha_alta = date('Y-m-d H:i:s'); */

    $instance = ConnectionDB::getInstance();

    // EMAIL EXISTS
    $stmt = $instance->prepare("SELECT 1 FROM clientes WHERE email = :email");
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    if ($stmt->fetch()) {
        echo json_encode([
            "status" => "error",
            "message" => "El correo electrónico ya existe"
        ]);
        exit;
    }

    // USER EXISTS
    $stmt = $instance->prepare("SELECT 1 FROM clientes WHERE nombre_usuario = :nombre_usuario");
    $stmt->bindValue(':nombre_usuario', $nombre_usuario);
    $stmt->execute();

    if ($stmt->fetch()) {
        echo json_encode([
            "status" => "error",
            "message" => "El usuario ya existe"
        ]);
        exit;
    }

    // INSERT
    $password_encript = password_hash($password, PASSWORD_BCRYPT);
    $clienteGuid = bin2hex(random_bytes(16));

    $apellido2 = !empty($_POST['apellido2']) ? $_POST['apellido2'] : null;

    $stmt = $instance->prepare("
    INSERT INTO clientes 
    (clienteGuid, nombre, apellido1, apellido2, fecha_nacimiento, pais, email, nombre_usuario, password)
    VALUES
    (:clienteGuid, :nombre, :apellido1, :apellido2, :fecha_nacimiento, :pais, :email, :nombre_usuario, :password)
");

    $stmt->bindValue(':clienteGuid', $clienteGuid);
    $stmt->bindValue(':nombre', $nombre);
    $stmt->bindValue(':apellido1', $apellido1);
    $stmt->bindValue(':apellido2', $apellido2);
    $stmt->bindValue(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindValue(':pais', $pais);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':nombre_usuario', $nombre_usuario);
    $stmt->bindValue(':password', $password_encript);
    $stmt->bindValue(':clienteGuid', $clienteGuid);

    if ($stmt->execute()) {

        $clienteId = $instance->lastInsertId();

        $_SESSION["clienteId"] = $clienteId;

        echo json_encode([
            "status" => "success",
            "clienteId" => $clienteId
        ]);
        exit;
    }

    echo json_encode([
        "status" => "error",
        "message" => "Error al crear usuario"
    ]);
} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Error servidor",
        "debug" => $e->getMessage()
    ]);
}
