<?php
require('../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId'])
) {

    $clienteId = $_REQUEST['clienteId'];

    $resultado = datos_usuario($clienteId);
}

function datos_usuario($clienteId)
{
    $instance = ConnectionDB::getInstance();

    //EN ESTE SACAMOS LOS DATOS DEL USUARIO
    $sql = 'SELECT cl.nombre_usuario, cl.monedero, COALESCE(SUM(ca.cantidad_carta), 0) AS total_cartas
    FROM clientes cl
    LEFT JOIN carrito ca ON cl.nombre_usuario = ca.nombre_usuarioC
    WHERE cl.clienteId = :clienteId
    GROUP BY cl.nombre_usuario, cl.monedero';
    
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':clienteId', $clienteId);

    $stmt->execute();
    $resultado2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($resultado2)) {

        echo json_encode($resultado2);
        exit;
    } else {

        $mensaje = "Error";

        echo $mensaje;
    }
}
