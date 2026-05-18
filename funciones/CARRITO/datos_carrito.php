<?php

require('../../BBDD/connection.php');

if (
    isset($_REQUEST['clienteId'])
) {

    $clienteId = $_REQUEST['clienteId'];

    $resultado = datos_carrito($clienteId);
}

function datos_carrito($clienteId)
{
    $instance = ConnectionDB::getInstance();

    // Primero, sacamos los usuarios (nombre_usuarioV) asociados con el clienteId
    $sql = 'SELECT DISTINCT ca.nombre_usuarioV, ca.nombre_usuarioC
            FROM clientes cl
            LEFT JOIN carrito ca ON cl.nombre_usuario = ca.nombre_usuarioC
            WHERE cl.clienteId = :clienteId';

    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':clienteId', $clienteId);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultadoFinal = [];

    // Si hay usuarios, obtenemos sus cartas
    if (!empty($usuarios)) {
        foreach ($usuarios as $usuario) {

            if ($usuario['nombre_usuarioV'] && $usuario['nombre_usuarioC']) {
                // Consulta para obtener los detalles de las cartas
                $sqlDetalles = 'SELECT ca.nombre_carta,  
                                       COALESCE(SUM(ca.cantidad_carta), 0) AS cantidad_carta, 
                                       ca.observacion_carta, 
                                       ca.estado_carta, 
                                       ca.idioma_carta, 
                                       ca.precio_carta
                                FROM carrito ca
                                WHERE ca.nombre_usuarioV = :nombre_usuarioV AND ca.nombre_usuarioC = :nombre_usuarioC
                                GROUP BY ca.nombre_carta, ca.observacion_carta, ca.estado_carta, ca.idioma_carta, ca.precio_carta';

                $stmtDetalles = $instance->prepare($sqlDetalles);
                $stmtDetalles->bindValue(':nombre_usuarioV', $usuario['nombre_usuarioV'], PDO::PARAM_STR);
                $stmtDetalles->bindValue(':nombre_usuarioC', $usuario['nombre_usuarioC'], PDO::PARAM_STR);
                $stmtDetalles->execute();
                $cartas = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

                // Agregar las cartas al resultado del usuario
                $usuario['cartas'] = $cartas;
                $resultadoFinal[] = $usuario;
            }
        }

        $sql = 'SELECT direccion_principal FROM clientes WHERE clienteId = :clienteId';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId);
        $stmt->execute();
        $direccion_principal = $stmt->fetch(PDO::FETCH_ASSOC)['direccion_principal'];

        if ($direccion_principal == 1) {
            $sql = 'SELECT 
                direccion_nombre1,
                direccion_linea1,
                direccion_calle1,
                direccion_localidad1,
                direccion_codpost1,
                direccion_pais1
            FROM clientes WHERE clienteId = :clienteId';
            $stmt = $instance->prepare($sql);
            $stmt->bindValue(':clienteId', $clienteId);
            $stmt->execute();
            $direccion = $stmt->fetch(PDO::FETCH_ASSOC); // solo un resultado
            $resultadoFinal[] = $direccion; // agregamos el resultado
        }

        if ($direccion_principal == 2) {
            $sql = 'SELECT 
                direccion_nombre2,
                direccion_linea2,
                direccion_calle2,
                direccion_localidad2,
                direccion_codpost2,
                direccion_pais2
            FROM clientes WHERE clienteId = :clienteId';
            $stmt = $instance->prepare($sql);
            $stmt->bindValue(':clienteId', $clienteId);
            $stmt->execute();
            $direccion = $stmt->fetch(PDO::FETCH_ASSOC); // solo un resultado
            $resultadoFinal[] = $direccion; // agregamos el resultado
        }

        // Devolvemos el resultado en formato JSON
        echo json_encode($resultadoFinal);
        exit;
    } else {
        // Si no hay resultados, mostrar mensaje de error
        $mensaje = "Error";
        echo json_encode($mensaje);
        exit;
    }
}
