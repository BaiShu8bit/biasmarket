<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) &&
    isset($_REQUEST['lista'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $lista = $_REQUEST['lista'];

    $usuario = cartas_lista($clienteId, $lista);
} else {
}

function cartas_lista($clienteId, $lista)
{
    try {

        $instance = ConnectionDB::getInstance();

        $sql = 'SELECT * FROM wishlist WHERE clienteId = :clienteId AND nombre_lista = :nombre_lista AND nombre_carta != ""';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_lista', $lista, PDO::PARAM_STR);

        $stmt->execute();

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($resultado);

    } catch (PDOException $e) {

        error_log('Error en wishlist: ' . $e->getMessage());
        return null;
    }
}
