<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['idpublicacion']) &&
    isset($_REQUEST['clienteId']) &&
    isset($_REQUEST['nombre_usuario']) &&
    isset($_REQUEST['nombre_carta']) &&
    isset($_REQUEST['estado_carta']) &&
    isset($_REQUEST['idioma_carta']) &&
    isset($_REQUEST['observacion_carta']) &&
    isset($_REQUEST['precio_carta']) &&
    isset($_REQUEST['cantidad_carta'])
) {
    $publicacionId = $_REQUEST['idpublicacion'];
    $clienteId = $_REQUEST['clienteId'];
    $nombre_carta = $_REQUEST['nombre_carta'];
    $nombre_usuario = $_REQUEST['nombre_usuario'];
    $estado_carta = $_REQUEST['estado_carta'];
    $idioma_carta = $_REQUEST['idioma_carta'];
    $observacion_carta = $_REQUEST['observacion_carta'];

    // Limpiar y convertir el precio
    $precio_carta0 = $_REQUEST['precio_carta'];
    $precio_carta = preg_replace('/[^0-9,]/', '', $precio_carta0);
    $precio_carta = str_replace(',', '.', $precio_carta);
    $precio_carta = floatval($precio_carta);

    $cantidad_carta = intval($_REQUEST['cantidad_carta']);

    // Generar carritoGuid único
    $carritoGuid = strtoupper(hash('ripemd128', uniqid("", true) . md5(time())));

    // Llamar a la función para procesar
    $resultado = formulario_publicacion(
        $publicacionId,
        $clienteId,
        $carritoGuid,
        $nombre_usuario,
        $nombre_carta,
        $estado_carta,
        $idioma_carta,
        $observacion_carta,
        $precio_carta,
        $cantidad_carta,
        $clienteId
    );

    echo json_encode($resultado);
    exit;
} else {
    echo json_encode(['error' => 'Faltan parámetros requeridos.']);
    exit;
}

function formulario_publicacion($publicacionId, $clienteId, $carritoGuid, $nombre_usuario, $nombre_carta, $estado_carta, $idioma_carta, $observacion_carta, $precio_carta, $cantidad_carta)
{
    try {
        $instance = ConnectionDB::getInstance();

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

        // Primero, sacamos los usuarios (nombre_usuarioV) asociados con el clienteId
        $sql = 'SELECT nombre_usuario FROM clientes WHERE clienteId = :clienteId ';

        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($resultado)) {

            $nombre_usuarioC = $resultado['nombre_usuario'];

            // Consultar la cantidad actual
            $sql = 'SELECT cantidadCarta FROM publicaciones WHERE publicacionId = :publicacionId';
            $stmt = $instance->prepare($sql);
            $stmt->bindValue(':publicacionId', $publicacionId, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado || !isset($resultado['cantidadCarta'])) {
                return ['error' => 'Publicación no encontrada.'];
            }

            $cantidadRestante = intval($resultado['cantidadCarta']) - $cantidad_carta;

            if ($cantidadRestante < 0) {
                return ['error' => 'Cantidad insuficiente en la publicación.'];
            }

            // Actualizar la cantidad de la publicación
            $sql = 'UPDATE publicaciones SET cantidadCarta = :cantidadCarta WHERE publicacionId = :publicacionId';
            $stmt = $instance->prepare($sql);
            $stmt->bindValue(':cantidadCarta', $cantidadRestante, PDO::PARAM_INT);
            $stmt->bindValue(':publicacionId', $publicacionId, PDO::PARAM_INT);
            $stmt->execute();

            // Insertar en el carrito
            $sql = 'INSERT INTO carrito (carritoGuid, publicacionId, nombre_usuarioC, nombre_usuarioV, nombre_carta, estado_carta, idioma_carta, observacion_carta, precio_carta, cantidad_carta)
                VALUES (:carritoGuid, :publicacionId, :nombre_usuarioC, :nombre_usuarioV, :nombre_carta, :estado_carta, :idioma_carta, :observacion_carta, :precio_carta, :cantidad_carta)';

            $stmt = $instance->prepare($sql);
            $stmt->bindValue(':carritoGuid', $carritoGuid, PDO::PARAM_STR);
            $stmt->bindValue(':publicacionId', $publicacionId, PDO::PARAM_INT);
            $stmt->bindValue(':nombre_usuarioC', $nombre_usuarioC, PDO::PARAM_STR);
            $stmt->bindValue(':nombre_usuarioV', $nombre_usuario, PDO::PARAM_STR);
            $stmt->bindValue(':nombre_carta', $nombre_carta, PDO::PARAM_STR);
            $stmt->bindValue(':estado_carta', $estado_carta, PDO::PARAM_STR);
            $stmt->bindValue(':idioma_carta', $idioma_carta, PDO::PARAM_STR);
            $stmt->bindValue(':observacion_carta', $observacion_carta, PDO::PARAM_STR);
            $stmt->bindValue(':precio_carta', $precio_carta);
            $stmt->bindValue(':cantidad_carta', $cantidad_carta, PDO::PARAM_INT);
            $stmt->execute();

            return ['success' => 'Operación completada correctamente.'];
        }
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}
