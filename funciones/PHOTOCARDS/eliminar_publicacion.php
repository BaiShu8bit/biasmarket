<?php

require('../../BBDD/connection.php');

header('Content-Type: application/json');

try {

    $instance = ConnectionDB::getInstance();

    if (empty($_POST['id'])) {

        echo json_encode([
            "success" => false,
            "message" => "ID no recibido"
        ]);
        exit;
    }

    $id = $_POST['id'];

    // 1. Obtener cantidad actual
    $sqlSelect = "
        SELECT cantidadCarta
        FROM publicaciones
        WHERE publicacionId = :id
    ";

    $stmtSelect = $instance->prepare($sqlSelect);
    $stmtSelect->bindValue(':id', $id);
    $stmtSelect->execute();

    $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    if (!$row) {

        echo json_encode([
            "success" => false,
            "message" => "Publicación no encontrada"
        ]);
        exit;
    }

    $cantidad = (int)$row['cantidadCarta'];

    // 2. Si solo queda 1 → borrar fila
    if ($cantidad <= 1) {

        $sqlDelete = "
            DELETE FROM publicaciones
            WHERE publicacionId = :id
        ";

        $stmtDelete = $instance->prepare($sqlDelete);
        $stmtDelete->bindValue(':id', $id);
        $stmtDelete->execute();

    } else {

        // 3. Si hay más de 1 → restar 1
        $sqlUpdate = "
            UPDATE publicaciones
            SET cantidadCarta = cantidadCarta - 1
            WHERE publicacionId = :id
        ";

        $stmtUpdate = $instance->prepare($sqlUpdate);
        $stmtUpdate->bindValue(':id', $id);
        $stmtUpdate->execute();
    }

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