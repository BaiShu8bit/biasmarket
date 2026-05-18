<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) &&
    isset($_REQUEST['wishlistName'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $wishlistName = $_REQUEST['wishlistName'];

    $usuario = anyadir_wishlist($clienteId, $wishlistName);
} else {
}

function anyadir_wishlist($clienteId, $wishlistName)
{
    try {
        $instance = ConnectionDB::getInstance();

        $sql = 'SELECT nombre_lista FROM wishlist WHERE clienteId = :clienteId AND nombre_lista = :nombre_lista';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_lista', $wishlistName, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {


            $mensaje = "Error";

            echo $mensaje;
            
        } else {

            $wishlistGuid = '';
            $uid = uniqid("", true);
            $data = '';
            $data .= $_SERVER['REQUEST_TIME'];
            $data .= $_SERVER['HTTP_USER_AGENT'];
            $data .= $_SERVER['REMOTE_ADDR'];
            $data .= $_SERVER['REMOTE_PORT'];
            $hash = strtoupper(hash('ripemd128', $uid . $wishlistGuid . md5($data)));
            $wishlistGuid = '' .
                substr($hash, 0, 8) .
                '-' .
                substr($hash, 8, 4) .
                '-' .
                substr($hash, 12, 4) .
                '-' .
                substr($hash, 16, 4) .
                '-' .
                substr($hash, 20, 12) .
                '';

            $sql = 'INSERT INTO wishlist (wishlistGuid, clienteId, nombre_lista) VALUES (:wishlistGuid, :clienteId, :nombre_lista)';
            $stmt = $instance->prepare($sql);
            $stmt->bindValue(':wishlistGuid', $wishlistGuid);
            $stmt->bindValue(':clienteId', $clienteId);
            $stmt->bindValue(':nombre_lista', $wishlistName);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {

        error_log('Error en wishlist: ' . $e->getMessage());
        return null;
    }
}
