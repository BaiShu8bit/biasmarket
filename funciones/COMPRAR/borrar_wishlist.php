<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) &&
    isset($_REQUEST['wishlistName'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $wishlistName = $_REQUEST['wishlistName'];

    $usuario = borrar_wishlist($clienteId, $wishlistName);
} else {
}

function borrar_wishlist($clienteId, $wishlistName)
{
    try {
        $instance = ConnectionDB::getInstance();

        $sql = 'DELETE FROM wishlist WHERE clienteId = :clienteId AND nombre_lista = :nombre_lista';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_lista', $wishlistName, PDO::PARAM_STR);

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
