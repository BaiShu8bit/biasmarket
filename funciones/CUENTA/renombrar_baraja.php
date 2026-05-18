<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) &&
    isset($_REQUEST['nombre_baraja']) &&
    isset($_REQUEST['nombre_original'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $nombre_baraja = $_REQUEST['nombre_baraja'];
    $nombre_original = $_REQUEST['nombre_original'];

    $resultado = renombrar_baraja($clienteId, $nombre_baraja, $nombre_original);
}

function renombrar_baraja($clienteId, $nombre_baraja, $nombre_original)
{
    $instance = ConnectionDB::getInstance();

    $sql = 'SELECT nombre_baraja FROM barajas WHERE clienteId = :clienteId AND nombre_baraja = :nombre_baraja';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
    $stmt->bindValue(':nombre_baraja', $nombre_baraja, PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {

        $sql = 'UPDATE barajas SET nombre_baraja = :nombre_baraja WHERE clienteId = :clienteId AND nombre_baraja = :nombre_original';
    
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_baraja', $nombre_baraja, PDO::PARAM_STR);
        $stmt->bindValue(':nombre_original', $nombre_original, PDO::PARAM_STR);
        $stmt->execute();

        $mensaje = "Actualización exitosa.";
        echo json_encode($mensaje);
        exit;

    }else{

        $mensaje = "Ese nombre ya esta siendo utilizado, elija otro";
        echo json_encode($mensaje);
        exit;

    }
}
