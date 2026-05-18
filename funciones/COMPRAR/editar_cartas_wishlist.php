<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['idcarta']) &&
    isset($_REQUEST['cardPrecio']) &&
    isset($_REQUEST['cardCantidad']) &&
    isset($_REQUEST['cardIdioma'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $idcarta = $_REQUEST['idcarta'];
    $cardPrecio = $_REQUEST['cardPrecio'];
    $cardCantidad = $_REQUEST['cardCantidad'];
    $cardIdioma = $_REQUEST['cardIdioma'];


    $usuario = editar_wishlist($clienteId, $idcarta, $cardPrecio, $cardCantidad, $cardIdioma);
} else {
}

function editar_wishlist($clienteId, $idcarta, $cardPrecio, $cardCantidad, $cardIdioma)
{
    try {

        $instance = ConnectionDB::getInstance();

        $sql = 'UPDATE wishlist SET cantidad_carta = :cantidad_carta, idioma_carta = :idioma_carta, precio_carta = :precio_carta WHERE clienteId = :clienteId AND wishlistId = :wishlistId';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':cantidad_carta', $cardCantidad, PDO::PARAM_INT);
        $stmt->bindValue(':idioma_carta', $cardIdioma, PDO::PARAM_STR);
        $stmt->bindValue(':precio_carta', $cardPrecio, PDO::PARAM_STR);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':wishlistId', $idcarta, PDO::PARAM_INT);

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


