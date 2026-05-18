<?php

require('../../BBDD/connection.php');

header('Content-Type: application/json');

try {

    $instance = ConnectionDB::getInstance();

    $id = $_POST['id_editar'];
    $estado = $_POST['estado_editar'];
    $observacion = $_POST['observacion_editar'];
    $precio = $_POST['precio_editar'];
    $cantidad = $_POST['cantidad_editar'];

    $sql = "
        UPDATE publicaciones
        SET 
            estadoCarta = :estado,
            observacionesCarta = :observacion,
            precioCarta = :precio,
            cantidadCarta = :cantidad
        WHERE publicacionId = :id
    ";

    $stmt = $instance->prepare($sql);

    $stmt->bindValue(':estado', $estado);
    $stmt->bindValue(':observacion', $observacion);
    $stmt->bindValue(':precio', $precio);
    $stmt->bindValue(':cantidad', $cantidad);
    $stmt->bindValue(':id', $id);

    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "Actualizado correctamente"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}