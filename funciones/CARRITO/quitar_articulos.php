<?php

require('../../BBDD/connection.php');

if (isset($_REQUEST['cartas'])) {
    $cartas = json_decode($_REQUEST['cartas'], true); // Decodificar el array de cartas

    if (is_array($cartas)) {
        foreach ($cartas as $carta) {
            // Validar que cada carta tenga todos los campos necesarios
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
                // Extraer los datos de la carta
                $id_usuario = $carta['id_usuario'];
                $vendedor = $carta['vendedor'];
                $nombre_carta = $carta['nombre_carta'];
                $cantidad_carta = $carta['cantidad_carta'];
                $estado_carta = $carta['estado_carta'];
                $idioma_carta = $carta['idioma_carta'];
                $observacion_carta = $carta['observacion_carta'];
                $precio_carta = $carta['precio_carta'];

                // Procesar los datos, por ejemplo, llamar a una función para quitarlos
                quitar_articulos($vendedor, $id_usuario, $nombre_carta, $cantidad_carta, $estado_carta, $idioma_carta, $observacion_carta, $precio_carta);
            }
        }
    } else {

        echo json_encode(['error' => 'Formato de datos incorrecto.']);
        exit;
    }

    echo json_encode(['success' => 'Cartas procesadas correctamente.']);
} else {

    echo json_encode(['error' => 'No se recibieron datos de cartas.']);
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
    $stmt->bindValue(':precio_carta', $precio_carta, PDO::PARAM_INT);

    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado || !isset($resultado['cantidadCarta'])) {

        return ['error' => 'Publicación no encontrada.'];
    }

    $cantidadRestante = intval($resultado['cantidadCarta']) + $cantidad_carta;

    if ($cantidadRestante < 0) {
        return ['error' => 'Cantidad insuficiente en la publicación.'];
    }

    // Actualizar la cantidad de la publicación
    $sql = 'UPDATE publicaciones SET cantidadCarta = :cantidadCarta WHERE publicacionId = :publicacionId';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':cantidadCarta', $cantidadRestante, PDO::PARAM_INT);
    $stmt->bindValue(':publicacionId', $resultado['publicacionId'], PDO::PARAM_INT);
    $stmt->execute();


    // Primero, sacamos los usuarios (nombre_usuarioV) asociados con el clienteId
    $sql = 'SELECT nombre_usuario FROM clientes WHERE clienteId = :clienteId ';

    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':clienteId', $comprador, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($resultado)) {

        $nombre_usuarioC = $resultado['nombre_usuario'];

        $sql = 'DELETE FROM carrito WHERE nombre_usuarioV = :nombre_usuarioV AND nombre_usuarioC = :nombre_usuarioC';

        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':nombre_usuarioV', $vendedor, PDO::PARAM_STR);
        $stmt->bindValue(':nombre_usuarioC', $nombre_usuarioC, PDO::PARAM_STR);
        $stmt->execute();

        // Devolvemos el resultado en formato JSON
        $filas_afectadas = $stmt->rowCount();
        if ($filas_afectadas > 0) {

            return ['success' => true, 'message' => 'Artículos eliminados correctamente.'];
        } else {

            return ['success' => false, 'message' => 'No se encontraron artículos para eliminar.'];
        }
    } else {

        return ['success' => false, 'message' => 'El comprador no existe.'];
    }
}
