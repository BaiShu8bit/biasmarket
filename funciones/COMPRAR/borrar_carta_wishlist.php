<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) &&
    isset($_REQUEST['wishlistId'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $wishlistId = $_REQUEST['wishlistId'];

    $usuario = borrar_carta_wishlist($clienteId, $wishlistId);
} else {
}

function borrar_carta_wishlist($clienteId, $wishlistId)
{
    try {
        $instance = ConnectionDB::getInstance();

        $sql = 'DELETE FROM wishlist WHERE clienteId = :clienteId AND wishlistId = :wishlistId';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':wishlistId', $wishlistId, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {

            $mensaje = "Error";

            echo $mensaje;
        }

    } catch (PDOException $e) {

        error_log('Error en wishlist: ' . $e->getMessage());
        return null;
    }
}
