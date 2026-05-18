<?php

require('../../BBDD/connection.php');

if (isset($_REQUEST['clienteId'])) {

    $clienteId = $_REQUEST['clienteId'];

    $usuario = wishlist($clienteId); 

    if ($usuario) {

        echo json_encode(['resultado' => $usuario]);

    } else {

        echo json_encode(['mensaje' => 'No hay listas.']);
    }
} else {
    echo json_encode(['error' => 'clienteId no especificado.']);
}

function wishlist($clienteId)
{
    try {
        $instance = ConnectionDB::getInstance();

        $sql = 'SELECT nombre_lista FROM wishlist WHERE clienteId = :clienteId GROUP BY nombre_lista';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {

        error_log('Error en wishlist: ' . $e->getMessage());
        return null;
    }
}
