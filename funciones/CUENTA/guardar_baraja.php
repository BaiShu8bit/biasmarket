<?php

require('../../BBDD/connection.php');

if (isset($_REQUEST['clienteId'], $_REQUEST['nombre_baraja'], $_REQUEST['cartas_baraja'])) {
    $clienteId = $_REQUEST['clienteId'];
    $nombre_baraja = $_REQUEST['nombre_baraja'];
    $cartas_baraja = $_REQUEST['cartas_baraja'];

    try {
        guardar_baraja($clienteId, $nombre_baraja, $cartas_baraja);
        echo json_encode(["success" => true, "message" => "Baraja guardada con éxito."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function guardar_baraja($clienteId, $nombre_baraja, $cartas_baraja)
{
    $instance = ConnectionDB::getInstance();
    $instance->beginTransaction(); // Iniciar transacción

    try {
        // Decodificar JSON
        $cartas = json_decode($cartas_baraja, true);
        if (!is_array($cartas) || empty($cartas)) {
            throw new Exception("El formato de cartas_baraja no es válido.");
        }

        // Verificar si la baraja ya existe
        $sql = 'SELECT nombre_baraja FROM barajas WHERE clienteId = :clienteId AND nombre_baraja = :nombre_baraja';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_baraja', $nombre_baraja, PDO::PARAM_STR);
        $stmt->execute();
        $barajaExiste = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si existe, eliminarla
        if ($barajaExiste) {
            $sql = 'DELETE FROM barajas WHERE clienteId = :clienteId AND nombre_baraja = :nombre_baraja';
            $stmt = $instance->prepare($sql);
            $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
            $stmt->bindValue(':nombre_baraja', $nombre_baraja, PDO::PARAM_STR);
            $stmt->execute();
        }

        // Verificar si hay otra baraja principal
        $sql = 'SELECT 1 FROM barajas WHERE clienteId = :clienteId AND principal = "SI"';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
        $stmt->execute();
        $principal = $stmt->fetch() ? "NO" : "SI";

        // Preparar consulta para insertar cartas
        $sql = 'INSERT INTO barajas (barajaGuid, clienteId, principal, nombre_baraja, nombre_carta, tipo_carta, zona_carta, url_carta) 
                VALUES (:barajaGuid, :clienteId, :principal, :nombre_baraja, :nombre_carta, :tipo_carta, :zona_carta, :url_carta)';
        $stmt = $instance->prepare($sql);

        foreach ($cartas as $carta) {
            // Validar datos de carta
            $nombre_carta = $carta['name'] ?? null;
            $tipo_carta = $carta['type'] ?? null;
            $zona_carta = $carta['zone'] ?? null;
            $url_carta = $carta['url'] ?? null;

            if (!$nombre_carta || !$tipo_carta || !$zona_carta || !$url_carta) {
                throw new Exception("Faltan datos en una de las cartas.");
            }

            // Generar GUID para cada carta
            $barajaGuid = strtoupper(hash('ripemd128', uniqid("", true)));

            // Asignar valores y ejecutar
            $stmt->bindValue(':barajaGuid', $barajaGuid);
            $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
            $stmt->bindValue(':principal', $principal, PDO::PARAM_STR);
            $stmt->bindValue(':nombre_baraja', $nombre_baraja, PDO::PARAM_STR);
            $stmt->bindValue(':nombre_carta', $nombre_carta, PDO::PARAM_STR);
            $stmt->bindValue(':tipo_carta', $tipo_carta, PDO::PARAM_STR);
            $stmt->bindValue(':zona_carta', $zona_carta, PDO::PARAM_STR);
            $stmt->bindValue(':url_carta', $url_carta, PDO::PARAM_STR);
            $stmt->execute();

            // Asegurar que "principal" solo sea asignado a una baraja
            $principal = "NO";
        }

        $instance->commit(); // Confirmar transacción
    } catch (Exception $e) {
        $instance->rollBack(); // Revertir cambios si hay error
        throw $e;
    }
}
