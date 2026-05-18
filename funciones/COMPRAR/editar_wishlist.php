<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) &&
    isset($_REQUEST['originalName']) &&
    isset($_REQUEST['newName'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $originalName = $_REQUEST['originalName'];
    $newName = $_REQUEST['newName'];

    $usuario = editar_wishlist($clienteId, $originalName, $newName);
} else {
}

function editar_wishlist($clienteId, $originalName, $newName)
{
    try {

        $instance = ConnectionDB::getInstance();

        $sql = 'UPDATE wishlist SET nombre_lista = :nombre_lista WHERE clienteId = :clienteId AND nombre_lista = :originalName';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_lista', $newName, PDO::PARAM_STR);
        $stmt->bindValue(':originalName', $originalName, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            // No se actualizaron filas, es decir, no se encontró el registro
            echo "No se encontró ninguna lista con ese nombre original para actualizar.";
        } else {
            // Se actualizó una o más filas
            echo "Lista actualizada correctamente.";
        }

    } catch (PDOException $e) {

        error_log('Error en wishlist: ' . $e->getMessage());
        return null;
    }
}


