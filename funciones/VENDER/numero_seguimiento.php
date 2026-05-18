<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['numero_seguimiento']) && isset($_REQUEST['pedidoId'])
) {

    $numero_seguimiento = $_REQUEST['numero_seguimiento'];
    $pedidoId = $_REQUEST['pedidoId'];

    $resultado = enviar($numero_seguimiento, $pedidoId);
}

function enviar($numero_seguimiento, $pedidoId)
{
    $instance = ConnectionDB::getInstance();

    $sql = 'UPDATE compras SET numero_seguimiento = :numero_seguimiento WHERE pedidoId = :pedidoId';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':numero_seguimiento', $numero_seguimiento, PDO::PARAM_STR);
    $stmt->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $stmt->execute();

    $sql = 'UPDATE ventas SET numero_seguimiento = :numero_seguimiento WHERE pedidoId = :pedidoId';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':numero_seguimiento', $numero_seguimiento, PDO::PARAM_STR);
    $stmt->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $stmt->execute();

    $resultado = $stmt->rowCount();

    if ($resultado === 0) {

        $mensaje = "Error";
        echo json_encode($mensaje);
        exit;
        
    } else {

        $mensaje = "Actualización exitosa.";
        echo json_encode($mensaje);
        exit;
    }
}
