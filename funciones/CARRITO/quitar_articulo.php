<?php

require('../../BBDD/connection.php');

$cartas = json_decode($_REQUEST['cartas'], true);

if (is_array($cartas) && count($cartas) > 0) {
    foreach ($cartas as $carta) {
        if (
            isset($carta['id_usuario']) &&
            isset($carta['vendedor']) &&
            isset($carta['nombre_carta']) &&
            isset($carta['cantidad_carta']) &&
            isset($carta['estado_carta']) &&
            isset($carta['idioma_carta']) &&
            isset($carta['observacion_carta']) &&
            isset($carta['precio_carta'])
        ) {
            $id_usuario = $carta['id_usuario'];
            $vendedor = $carta['vendedor'];
            $nombre_carta = $carta['nombre_carta'];
            $cantidad_carta = $carta['cantidad_carta'];
            $estado_carta = $carta['estado_carta'];
            $idioma_carta = $carta['idioma_carta'];
            $observacion_carta = $carta['observacion_carta'];
            $precio_carta = $carta['precio_carta'];

            quitar_articulos($vendedor, $id_usuario, $nombre_carta, $cantidad_carta, $estado_carta, $idioma_carta, $observacion_carta, $precio_carta);
        } else {
            echo json_encode(['error' => 'Faltan campos en la carta.']);
            exit;
        }
    }
    echo json_encode(['success' => 'Cartas procesadas correctamente.']);
} else {
    echo json_encode(['error' => 'Formato de datos incorrecto o no hay cartas.']);
}



function quitar_articulos($vendedor, $comprador, $nombre_carta, $cantidad_carta, $estado_carta, $idioma_carta, $observacion_carta, $precio_carta)
{
    $instance = ConnectionDB::getInstance();

    switch ($idioma_carta) {
        case "Español":
            $idioma_carta = "espanyol";
            break;
        case "Inglés":
            $idioma_carta = "ingles";
            break;
        case "Alemán":
            $idioma_carta = "aleman";
            break;
        case "Francés":
            $idioma_carta = "frances";
            break;
        case "Italiano":
            $idioma_carta = "italiano";
            break;
    }

    switch ($estado_carta) {
        case "Perfecto":
            $estado_carta = "mint";
            break;
        case "Casi perfecto":
            $estado_carta = "near_mint";
            break;
        case "Excelente":
            $estado_carta = "excellent";
            break;
        case "Bueno":
            $estado_carta = "good";
            break;
        case "Ligeramente jugada":
            $estado_carta = "light_played";
            break;
        case "Muy jugada":
            $estado_carta = "played";
            break;
        case "Dañada":
            $estado_carta = "poor";
            break;
    }

    if ($observacion_carta == "No hay observación") {

        $observacion_carta = "";
    }

    //var_dump($vendedor, $nombre_carta, $estado_carta, $idioma_carta, $observacion_carta, $precio_carta);
    //exit;

    // Consultar la cantidad actual
    $sql = 'SELECT publicacionId, cantidadCarta FROM publicaciones 
             WHERE nombre_usuario = :vendedor 
             AND nombreCarta = :nombre_carta 
             AND estadoCarta = :estado_carta 
             AND idiomaCarta = :idioma_carta 
             AND observacionesCarta = :observacion_carta
             AND precioCarta = :precio_carta';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':vendedor', $vendedor, PDO::PARAM_STR);
    $stmt->bindValue(':nombre_carta', $nombre_carta, PDO::PARAM_STR);
    $stmt->bindValue(':estado_carta', $estado_carta, PDO::PARAM_STR);
    $stmt->bindValue(':idioma_carta', $idioma_carta, PDO::PARAM_STR);
    $stmt->bindValue(':observacion_carta', $observacion_carta, PDO::PARAM_STR);
    $stmt->bindValue(':precio_carta', $precio_carta, PDO::PARAM_STR);

    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    //var_dump($resultado);
    //exit;

    if (!$resultado || !isset($resultado['cantidadCarta'])) {

        return ['error' => 'Publicación no encontrada.'];
    }

    $publicacionId = $resultado['publicacionId'];

    $cantidadRestante = intval($resultado['cantidadCarta']) + $cantidad_carta;

    if ($cantidadRestante < 0) {
        return ['error' => 'Cantidad insuficiente en la publicación.'];
    }

    // Actualizar la cantidad de la publicación
    $sql = 'UPDATE publicaciones SET cantidadCarta = :cantidadCarta WHERE publicacionId = :publicacionId';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':cantidadCarta', $cantidadRestante, PDO::PARAM_INT);
    $stmt->bindValue(':publicacionId', $publicacionId, PDO::PARAM_INT);
    $stmt->execute();


    $sql = 'SELECT cantidad_carta FROM carrito WHERE publicacionId = :publicacionId';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':publicacionId', $publicacionId, PDO::PARAM_INT);

    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado || !isset($resultado['cantidad_carta'])) {

        return ['error' => 'Cantidad no encontrada.'];
    }

    $cantidadRestante = intval($resultado['cantidad_carta']) - $cantidad_carta;

    if ($cantidadRestante > 0) {

        // Actualizar la cantidad de la publicación
        $sql = 'UPDATE carrito SET cantidad_carta = :cantidad_carta WHERE publicacionId = :publicacionId';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':cantidad_carta', $cantidadRestante, PDO::PARAM_INT);
        $stmt->bindValue(':publicacionId', $publicacionId, PDO::PARAM_INT);
        $stmt->execute();

    } else if ($cantidadRestante <= 0) {

        $sql = 'DELETE FROM carrito WHERE publicacionId = :publicacionId';

        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':publicacionId', $publicacionId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
