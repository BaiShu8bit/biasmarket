<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) && isset($_REQUEST['pedidoId']) && isset($_REQUEST['gastos_envio']) && isset($_REQUEST['total_pedido'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $pedidoId = $_REQUEST['pedidoId'];
    $gastos_envio = $_REQUEST['gastos_envio'];
    $total_pedido = $_REQUEST['total_pedido'];

    $resultado = enviar($clienteId, $pedidoId, $gastos_envio, $total_pedido);
}

function enviar($clienteId, $pedidoId, $gastos_envio, $total_pedido)
{
    $instance = ConnectionDB::getInstance();

    if($gastos_envio == "1.09€"){

        $sql = 'SELECT monedero FROM clientes WHERE clienteId = :clienteId';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $total_pedido_numeric = floatval(str_replace("€", "", $total_pedido));
        $monedero = floatval($resultado["monedero"]);
        
        $sumaTotal = $total_pedido_numeric + $monedero;

        $sql = 'UPDATE clientes SET monedero = :monedero WHERE clienteId = :clienteId';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':monedero', $sumaTotal, PDO::PARAM_STR);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->execute();
    }

    $sql = 'UPDATE compras SET estado_pedido = :estado_pedido WHERE pedidoId = :pedidoId';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':estado_pedido', "enviado", PDO::PARAM_STR);
    $stmt->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $stmt->execute();


    $sql = 'UPDATE ventas SET estado_pedido = :estado_pedido WHERE pedidoId = :pedidoId';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':estado_pedido', "enviado", PDO::PARAM_STR);
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
