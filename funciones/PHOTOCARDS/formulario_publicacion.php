<?php

require('../../BBDD/connection.php');

session_start();

header('Content-Type: application/json');

try {

    if (!isset($_SESSION['clienteId'])) {

        echo json_encode([
            "success" => false,
            "message" => "Sesión no iniciada"
        ]);
        exit;
    }

    $clienteId = $_SESSION['clienteId'];

    if (
        empty($_POST['photocard_id']) ||
        empty($_POST['form_estado']) ||
        empty($_POST['form_precio']) ||
        empty($_POST['form_cantidad'])
    ) {

        echo json_encode([
            "success" => false,
            "message" => "Faltan datos"
        ]);
        exit;
    }

    /*
    ============================================
    DATOS
    ============================================
    */

    $photocardId = $_POST['photocard_id'];
    $nombreCarta = $_POST['nombre_carta'] ?? 'Sin nombre';
    $nombreImagen = null;

    if (isset($_FILES['form_imagen']) && $_FILES['form_imagen']['error'] === 0) {

        $ext = pathinfo($_FILES['form_imagen']['name'], PATHINFO_EXTENSION);
        $nombreImagen = uniqid('img_') . '.' . $ext;

        $rutaDestino = $_SERVER['DOCUMENT_ROOT'] . "/biasmarket/contenido/uploads/" . $nombreImagen;

        move_uploaded_file(
            $_FILES['form_imagen']['tmp_name'],
            $rutaDestino
        );
    }

    $imagenCarta = $nombreImagen;

    $estadoCarta = $_POST['form_estado'];
    $observacionesCarta = $_POST['form_observaciones'] ?? '';
    $precioCarta = $_POST['form_precio'];
    $cantidadCarta = $_POST['form_cantidad'];

    $instance = ConnectionDB::getInstance();

    /*
    ============================================
    VALIDAR IMAGEN
    ============================================
    */
    $nombreImagen = null;

    if (isset($_FILES['form_imagen']) && $_FILES['form_imagen']['error'] === 0) {

        $ext = pathinfo($_FILES['form_imagen']['name'], PATHINFO_EXTENSION);
        $nombreImagen = uniqid('img_') . '.' . $ext;

        $rutaDestino = __DIR__ . "/../../contenido/uploads/" . $nombreImagen;

        if (!file_exists(__DIR__ . "/../../contenido/uploads/")) {
            mkdir(__DIR__ . "/../../contenido/uploads/", 0777, true);
        }

        move_uploaded_file(
            $_FILES['form_imagen']['tmp_name'],
            $rutaDestino
        );
    }

    /*
    ============================================
    COMPROBAR SI EXISTE PHOTOCARD
    ============================================
    */

    $sqlCheck = "
        SELECT photocardId
        FROM photocards
        WHERE photocardId = :photocardId
    ";

    $stmtCheck = $instance->prepare($sqlCheck);
    $stmtCheck->bindValue(':photocardId', $photocardId);
    $stmtCheck->execute();

    $photocardExists = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$photocardExists) {

        $sqlPhotocard = "
            INSERT INTO photocards
            (
                photocardId,
                nombreCarta,
                imagenCarta
            )
            VALUES
            (
                :photocardId,
                :nombreCarta,
                :imagenCarta
            )
        ";

        $stmtPhotocard = $instance->prepare($sqlPhotocard);
        $stmtPhotocard->bindValue(':photocardId', $photocardId);
        $stmtPhotocard->bindValue(':nombreCarta', $nombreCarta);
        $stmtPhotocard->bindValue(':imagenCarta', $imagenCarta);
        $stmtPhotocard->execute();
    }

    /*
    ============================================
    COMPROBAR SI YA EXISTE PUBLICACIÓN
    ============================================
    */

    $sqlExiste = "
        SELECT publicacionId, cantidadCarta
        FROM publicaciones
        WHERE clienteId = :clienteId
        AND photocardId = :photocardId
        AND estadoCarta = :estadoCarta
        AND observacionesCarta = :observacionesCarta
        AND precioCarta = :precioCarta
    ";

    $stmtExiste = $instance->prepare($sqlExiste);
    $stmtExiste->bindValue(':clienteId', $clienteId);
    $stmtExiste->bindValue(':photocardId', $photocardId);
    $stmtExiste->bindValue(':estadoCarta', $estadoCarta);
    $stmtExiste->bindValue(':observacionesCarta', $observacionesCarta);
    $stmtExiste->bindValue(':precioCarta', $precioCarta);
    $stmtExiste->execute();

    $publicacionExistente = $stmtExiste->fetch(PDO::FETCH_ASSOC);

    /*
    ============================================
    INSERT / UPDATE
    ============================================
    */

    if ($publicacionExistente) {

        $sqlUpdate = "
            UPDATE publicaciones
            SET cantidadCarta = cantidadCarta + :cantidad
            WHERE publicacionId = :id
        ";

        $stmtUpdate = $instance->prepare($sqlUpdate);
        $stmtUpdate->bindValue(':cantidad', $cantidadCarta);
        $stmtUpdate->bindValue(':id', $publicacionExistente['publicacionId']);
        $stmtUpdate->execute();

    } else {

        $publicacionGuid = uniqid('pub_', true);

        $sqlInsert = "
            INSERT INTO publicaciones
            (
                publicacionGuid,
                clienteId,
                photocardId,
                estadoCarta,
                imagenCarta,
                observacionesCarta,
                precioCarta,
                cantidadCarta
            )
            VALUES
            (
                :publicacionGuid,
                :clienteId,
                :photocardId,
                :estadoCarta,
                :imagenCarta,
                :observacionesCarta,
                :precioCarta,
                :cantidadCarta
            )
        ";

        $stmtInsert = $instance->prepare($sqlInsert);
        $stmtInsert->bindValue(':publicacionGuid', $publicacionGuid);
        $stmtInsert->bindValue(':clienteId', $clienteId);
        $stmtInsert->bindValue(':photocardId', $photocardId);
        $stmtInsert->bindValue(':estadoCarta', $estadoCarta);
        $stmtInsert->bindValue(':imagenCarta', $imagenCarta);
        $stmtInsert->bindValue(':observacionesCarta', $observacionesCarta);
        $stmtInsert->bindValue(':precioCarta', $precioCarta);
        $stmtInsert->bindValue(':cantidadCarta', $cantidadCarta);
        $stmtInsert->execute();
    }

    echo json_encode([
        "success" => true,
        "message" => "Publicación creada o actualizada correctamente"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}