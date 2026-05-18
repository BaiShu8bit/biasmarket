<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId']) && isset($_REQUEST['nombre_baraja']) && isset($_REQUEST['dato2'])
) {

    $clienteId = $_REQUEST['clienteId'];
    $nombre_baraja = $_REQUEST['nombre_baraja'];


    $resultado = datos_barajas($clienteId, $nombre_baraja);
}

if (
    isset($_REQUEST['clienteId']) && isset($_REQUEST['dato1'])
) {

    $clienteId = $_REQUEST['clienteId'];

    $resultado = datos_barajas_select($clienteId);
}

function datos_barajas($clienteId, $nombre_baraja)
{
    try {

        $instance = ConnectionDB::getInstance();

        // Primera consulta: Recuperar todas las columnas
        $sql = 'SELECT * FROM barajas WHERE clienteId = :clienteId AND nombre_baraja = :nombre_baraja';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_baraja', $nombre_baraja, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultado)) {
            $mensaje = "no hay datos";
            echo json_encode($mensaje);
            exit;
        }

        // Segunda consulta: Recuperar solo el nombre de la baraja
        $sqlNombreBaraja = 'SELECT nombre_baraja, principal FROM barajas WHERE clienteId = :clienteId AND nombre_baraja = :nombre_baraja GROUP BY nombre_baraja';
        $stmtNombreBaraja = $instance->prepare($sqlNombreBaraja);
        $stmtNombreBaraja->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmtNombreBaraja->bindValue(':nombre_baraja', $nombre_baraja, PDO::PARAM_INT);
        $stmtNombreBaraja->execute();
        $nombres = $stmtNombreBaraja->fetchAll(PDO::FETCH_COLUMN);

        if (empty($nombres)) {
            $mensaje = "no hay nombres de baraja";
            echo json_encode($mensaje);
            exit;
        }

        $data = [
            'resultado' => $resultado,
            'nombres_baraja' => $nombres
        ];

        echo json_encode($data);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function datos_barajas_select($clienteId)
{
    try {

        $instance = ConnectionDB::getInstance();

        // Primera consulta: Recuperar todas las columnas
        $sql = 'SELECT * FROM barajas WHERE clienteId = :clienteId';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultado)) {
            $mensaje = "no hay datos";
            echo json_encode($mensaje);
            exit;
        }

        // Segunda consulta: Recuperar solo el nombre de la baraja
        $sqlNombreBaraja = 'SELECT nombre_baraja, principal FROM barajas WHERE clienteId = :clienteId GROUP BY nombre_baraja';
        $stmtNombreBaraja = $instance->prepare($sqlNombreBaraja);
        $stmtNombreBaraja->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmtNombreBaraja->execute();
        $nombres = $stmtNombreBaraja->fetchAll(PDO::FETCH_ASSOC);

        if (empty($nombres)) {
            $mensaje = "no hay nombres de baraja";
            echo json_encode($mensaje);
            exit;
        }

        echo json_encode($nombres);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}