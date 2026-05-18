<?php
require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId'])
) {

    $clienteId = $_REQUEST['clienteId'];

    $resultado = datos_tablas($clienteId);
}

function datos_tablas($clienteId)
{
    $instance = ConnectionDB::getInstance();

    //EN ESTE SACAMOS LOS DATOS
    $sql = 'SELECT nombre_usuario FROM clientes WHERE clienteId = :clienteId';
    
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':clienteId', $clienteId);

    $stmt->execute();
    $resultado2 = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($resultado2)) {

        $sql = 'SELECT * FROM ventas WHERE nombre_usuarioV = :nombre_usuarioV';
    
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':nombre_usuarioV', $resultado2["nombre_usuario"]);
    
        $stmt->execute();
        $resultado2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($resultado2);
        exit;
    } else {

        $mensaje = "Error";

        echo $mensaje;
    }
}
