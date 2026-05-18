<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) && isset($_REQUEST['nombre_baraja'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $nombre_baraja = $_REQUEST['nombre_baraja'];

    $resultado = guardar_baraja($clienteId, $nombre_baraja);
}

function guardar_baraja($clienteId, $nombre_baraja)
{
    $instance = ConnectionDB::getInstance();

    $sql = 'DELETE FROM barajas WHERE clienteId = :clienteId AND nombre_baraja = :nombre_baraja';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
    $stmt->bindValue(':nombre_baraja', $nombre_baraja, PDO::PARAM_STR);

    $stmt->execute();
}
