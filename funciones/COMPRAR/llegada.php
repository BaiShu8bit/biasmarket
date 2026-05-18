<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) && 
    isset($_REQUEST['pedidoId']) && 
    isset($_REQUEST['gastos_envio']) && 
    isset($_REQUEST['total_pedido'])  && 
    isset($_REQUEST['evaluacion'])  && 
    isset($_REQUEST['comentarios']) && 
    isset($_REQUEST['direccion_nombreV'])
   ) 
{

    $clienteId = $_REQUEST['clienteId'];
    $pedidoId = $_REQUEST['pedidoId'];
    $gastos_envio = $_REQUEST['gastos_envio'];
    $total_pedido = $_REQUEST['total_pedido'];
    $evaluacion = $_REQUEST['evaluacion'];
    $comentarios = $_REQUEST['comentarios'];
    $direccion_nombreV = $_REQUEST['direccion_nombreV'];


    $resultado = enviar($clienteId, $pedidoId, $gastos_envio, $total_pedido, $evaluacion, $comentarios, $direccion_nombreV);
}

function enviar($clienteId, $pedidoId, $gastos_envio, $total_pedido, $evaluacion, $comentarios, $direccion_nombreV)
{
    $instance = ConnectionDB::getInstance();

    if($gastos_envio == "5€"){

        $sql = 'SELECT monedero FROM clientes WHERE direccion_nombre1 = :direccion_nombreV';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':direccion_nombreV', $direccion_nombreV, PDO::PARAM_STR);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $total_pedido_numeric = floatval(str_replace("€", "", $total_pedido));
        $monedero = floatval($resultado["monedero"]);
        
        $sumaTotal = $total_pedido_numeric + $monedero;

        $sql = 'UPDATE clientes SET monedero = :monedero WHERE direccion_nombre1 = :direccion_nombreV';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':monedero', $sumaTotal, PDO::PARAM_STR);
        $stmt->bindValue(':direccion_nombreV', $direccion_nombreV, PDO::PARAM_STR);
        $stmt->execute();
    }

    $sql = 'UPDATE compras SET estado_pedido = :estado_pedido, evaluacion = :evaluacion, comentarios = :comentarios WHERE pedidoId = :pedidoId AND direccion_nombreV = :direccion_nombreV';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':estado_pedido', "recibido", PDO::PARAM_STR);
    $stmt->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $stmt->bindValue(':evaluacion', $evaluacion, PDO::PARAM_STR);
    $stmt->bindValue(':comentarios', $comentarios, PDO::PARAM_STR);
    $stmt->bindValue(':direccion_nombreV', $direccion_nombreV, PDO::PARAM_STR);

    $stmt->execute();


    $sql = 'UPDATE ventas SET estado_pedido = :estado_pedido, evaluacion = :evaluacion, comentarios = :comentarios WHERE pedidoId = :pedidoId AND direccion_nombreV = :direccion_nombreV';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':estado_pedido', "recibido", PDO::PARAM_STR);
    $stmt->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $stmt->bindValue(':evaluacion', $evaluacion, PDO::PARAM_STR);
    $stmt->bindValue(':comentarios', $comentarios, PDO::PARAM_STR);
    $stmt->bindValue(':direccion_nombreV', $direccion_nombreV, PDO::PARAM_STR);
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
