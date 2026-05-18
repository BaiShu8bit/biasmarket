<?php

require('../../BBDD/connection.php');

// Decodificar los datos JSON recibidos
$resultados = json_decode($_POST['resultados'], true);

if (is_array($resultados) && count($resultados) > 0) {
    // Suponiendo que todos los pedidos comparten el mismo clienteId y password
    $clienteId = $resultados[0]['clienteId'] ?? null;
    $password = $resultados[0]['password'] ?? null;

    if ($clienteId && $password) {
        $instance = ConnectionDB::getInstance();

        // Verificar cliente una sola vez
        $sql = 'SELECT nombre_usuario FROM clientes WHERE clienteId = :clienteId AND password = :password';
        $stmt = $instance->prepare($sql);
        $stmt->bindValue(':clienteId', $clienteId);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
        $nombre_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($nombre_usuario)) {

            // Actualizar monedero una sola vez
            $total_compra = $resultados[0]['total_compra'];
            $sql = 'UPDATE clientes SET monedero = :monedero WHERE clienteId = :clienteId';
            $stmt = $instance->prepare($sql);
            $stmt->bindValue(':monedero', $total_compra, PDO::PARAM_STR);
            $stmt->bindValue(':clienteId', $clienteId, PDO::PARAM_INT);
            $stmt->execute();
        } else {

            echo json_encode(['error' => 'Cliente no encontrado.']);
            exit;
        }
    } else {
        echo json_encode(['error' => 'Faltan clienteId o password.']);
        exit;
    }

    // Procesar cada pedido
    try {
        foreach ($resultados as $resultado) {
            if (
                isset($resultado['direccion']) &&
                isset($resultado['vendedor']) &&
                isset($resultado['totalArticulos']) &&
                isset($resultado['precioTotal']) &&
                isset($resultado['gastosEnvio']) &&
                isset($resultado['totalPedido']) &&
                isset($resultado['cartas']) &&
                is_array($resultado['cartas'])
            ) {
                $direccionC = $resultado['direccion'];
                $vendedor = $resultado['vendedor'];
                $totalArticulos = $resultado['totalArticulos'];
                $precioTotal = $resultado['precioTotal'];
                $gastosEnvio = $resultado['gastosEnvio'];
                $totalPedido = $resultado['totalPedido'];
                $cartas = $resultado['cartas'];

                //echo "Procesando pedido {$index}...\n";

                $sql = 'DELETE FROM carrito WHERE nombre_usuarioV = :nombre_usuarioV AND nombre_usuarioC = :nombre_usuarioC';
                $stmt = $instance->prepare($sql);
                $stmt->bindValue(':nombre_usuarioV', $vendedor, PDO::PARAM_STR);
                $stmt->bindValue(':nombre_usuarioC', $nombre_usuario["nombre_usuario"], PDO::PARAM_STR);
                $stmt->execute();

                $filas_afectadas = $stmt->rowCount();

                if ($filas_afectadas > 0) {
                    /*if (1) {*/

                    $sql = 'SELECT direccion_principal FROM clientes WHERE nombre_usuario = :nombre_usuario';
                    $stmt = $instance->prepare($sql);
                    $stmt->bindValue(':nombre_usuario', $vendedor);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result && isset($result['direccion_principal'])) {

                        $direccion_principal = $result['direccion_principal'];

                        $columns = [
                            'direccion_nombre' . $direccion_principal,
                            'direccion_linea' . $direccion_principal,
                            'direccion_calle' . $direccion_principal,
                            'direccion_localidad' . $direccion_principal,
                            'direccion_codpost' . $direccion_principal,
                            'direccion_pais' . $direccion_principal
                        ];

                        // Crear la consulta para obtener los datos de la dirección
                        $sql = 'SELECT ' . implode(', ', $columns) . ' FROM clientes WHERE nombre_usuario = :nombre_usuario';
                        $stmt = $instance->prepare($sql);
                        $stmt->bindValue(':nombre_usuario', $vendedor);
                        $stmt->execute();

                        $direccionV = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($direccionV) {

                            preg_match('/^(\d+)\s+(.*)$/', $direccionC['codpostal'], $matches);
                            $codigoPostal = $matches[1];
                            $localidad = $matches[2];

                            // Procesar pedido y mostrar datos
                            /*
                                echo "Procesando pedido:\n";
                                echo "Cliente ID: $clienteId\n";
                                echo "Password: $password\n";
                                echo "Vendedor: $vendedor\n";
                                echo "Total Artículos: $totalArticulos\n";
                                echo "Precio Total: $precioTotal\n";
                                echo "Gastos de Envío: $gastosEnvio\n";
                                echo "Total Pedido: $totalPedido\n";
                                echo "Dirección COMPRADOR:\n";
                                echo "  Nombre: {$direccionC['nombre']}\n";
                                echo "  Línea Extra: {$direccionC['lineaExtra']}\n";
                                echo "  Calle: {$direccionC['calle']}\n";
                                echo "  Código Postal: {$codigoPostal}\n";
                                echo "  Localidad: {$localidad}\n";
                                echo "  País: {$direccionC['pais']}\n";
                                echo "Dirección VENDEDOR:\n";
                                echo "  Nombre: {$direccionV['direccion_nombre' .$direccion_principal]}\n";
                                echo "  Línea Extra: {$direccionV['direccion_linea' .$direccion_principal]}\n";
                                echo "  Calle: {$direccionV['direccion_calle' .$direccion_principal]}\n";
                                echo "  Localidad: {$direccionV['direccion_localidad' .$direccion_principal]}\n";
                                echo "  Código Postal: {$direccionV['direccion_codpost' .$direccion_principal]}\n";
                                echo "  País: {$direccionV['direccion_pais' .$direccion_principal]}\n";
                                echo "Cartas:\n";
                                echo "--------------------------\n";
                            */

                            $sql = 'SELECT COALESCE(MAX(pedidoId), 0) AS ultimo_pedido FROM ventas WHERE nombre_usuarioC = :vendedor';
                            $stmt = $instance->prepare($sql);
                            $stmt->bindValue(':vendedor', $nombre_usuario["nombre_usuario"], PDO::PARAM_STR);
                            $stmt->execute();
                            $ultimo_pedido = $stmt->fetch(PDO::FETCH_ASSOC)['ultimo_pedido'];
            
                            // Incrementar pedidoId
                            $nuevo_pedidoId = $ultimo_pedido + 1;

                            foreach ($cartas as $carta) {
                                
                                echo "  - {$carta['nombreCarta']} ({$carta['cantidad']}) - Estado: {$carta['estado']}, Idioma: {$carta['idioma']}, Observación: {$carta['observacion']}, Precio: {$carta['precio']}\n";

                                $clienteGuid = '';
                                $uid = uniqid("", true);
                                $data = '';
                                $data .= $_SERVER['REQUEST_TIME'];
                                $data .= $_SERVER['HTTP_USER_AGENT'];
                                $data .= $_SERVER['REMOTE_ADDR'];
                                $data .= $_SERVER['REMOTE_PORT'];
                                $hash = strtoupper(hash('ripemd128', $uid . $clienteGuid . md5($data)));
                                $clienteGuid = '' .
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

                                $fecha_cv = date('Y-m-d H:i:s');

                                $sql = 'INSERT INTO compras (
                                compraGuid, 
                                pedidoId, 
                                fecha_compra, 
                                nombre_usuarioC, 
                                direccion_nombreC, 
                                direccion_lineaC, 
                                direccion_calleC, 
                                direccion_localidadC, 
                                direccion_codpostC, 
                                direccion_paisC, 
                                nombre_usuarioV, 
                                direccion_nombreV, 
                                direccion_lineaV, 
                                direccion_calleV, 
                                direccion_localidadV, 
                                direccion_codpostV, 
                                direccion_paisV, 
                                articulos,
                                valor_pedido,
                                gastos_envio,
                                total_pedido,
                                nombre_carta,
                                cantidad_carta,
                                idioma_carta,
                                observacion_carta,
                                estado_carta,
                                precio_carta,
                                estado_pedido
                                ) VALUES (
                                :compraGuid,
                                :pedidoId,
                                :fecha_compra,
                                :nombre_usuarioC, 
                                :direccion_nombreC, 
                                :direccion_lineaC, 
                                :direccion_calleC, 
                                :direccion_localidadC, 
                                :direccion_codpostC, 
                                :direccion_paisC, 
                                :nombre_usuarioV, 
                                :direccion_nombreV, 
                                :direccion_lineaV, 
                                :direccion_calleV, 
                                :direccion_localidadV, 
                                :direccion_codpostV, 
                                :direccion_paisV, 
                                :articulos,
                                :valor_pedido,
                                :gastos_envio,
                                :total_pedido,
                                :nombre_carta,
                                :cantidad_carta,
                                :idioma_carta,
                                :observacion_carta,
                                :estado_carta,
                                :precio_carta,
                                "pagado"
                                )';
                                $stmt = $instance->prepare($sql);
                                $stmt->bindValue(':compraGuid', $clienteGuid);
                                $stmt->bindValue(':pedidoId', $nuevo_pedidoId, PDO::PARAM_INT);
                                $stmt->bindValue(':fecha_compra', $fecha_cv);
                                $stmt->bindValue(':nombre_usuarioC', $nombre_usuario["nombre_usuario"]);
                                $stmt->bindValue(':direccion_nombreC', $direccionC['nombre']);
                                $stmt->bindValue(':direccion_lineaC', $direccionC['lineaExtra']);
                                $stmt->bindValue(':direccion_calleC', $direccionC['calle']);
                                $stmt->bindValue(':direccion_localidadC', $localidad);
                                $stmt->bindValue(':direccion_codpostC', $codigoPostal);
                                $stmt->bindValue(':direccion_paisC', $direccionC['pais']);
                                $stmt->bindValue(':nombre_usuarioV', $vendedor);
                                $stmt->bindValue(':direccion_nombreV', $direccionV['direccion_nombre' . $direccion_principal]);
                                $stmt->bindValue(':direccion_lineaV', $direccionV['direccion_linea' . $direccion_principal]);
                                $stmt->bindValue(':direccion_calleV', $direccionV['direccion_calle' . $direccion_principal]);
                                $stmt->bindValue(':direccion_localidadV', $direccionV['direccion_localidad' . $direccion_principal]);
                                $stmt->bindValue(':direccion_codpostV', $direccionV['direccion_codpost' . $direccion_principal]);
                                $stmt->bindValue(':direccion_paisV', $direccionV['direccion_pais' . $direccion_principal]);
                                $stmt->bindValue(':articulos', $totalArticulos);
                                $stmt->bindValue(':valor_pedido', $precioTotal);
                                $stmt->bindValue(':gastos_envio', $gastosEnvio);
                                $stmt->bindValue(':total_pedido', $totalPedido);
                                $stmt->bindValue(':nombre_carta', $carta['nombreCarta']);
                                $stmt->bindValue(':cantidad_carta', $carta['cantidad']);
                                $stmt->bindValue(':idioma_carta', $carta['idioma']);
                                $stmt->bindValue(':observacion_carta', $carta['observacion']);
                                $stmt->bindValue(':estado_carta', $carta['estado']);
                                $stmt->bindValue(':precio_carta', $carta['precio']);

                                $stmt->execute();
                                $comprar = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                /*var_dump($comprar);
                                exit;*/

                                $sql = 'INSERT INTO ventas (
                                ventaGuid,
                                pedidoId,
                                fecha_venta, 
                                nombre_usuarioC, 
                                direccion_nombreC, 
                                direccion_lineaC, 
                                direccion_calleC, 
                                direccion_localidadC, 
                                direccion_codpostC, 
                                direccion_paisC, 
                                nombre_usuarioV, 
                                direccion_nombreV, 
                                direccion_lineaV, 
                                direccion_calleV, 
                                direccion_localidadV, 
                                direccion_codpostV, 
                                direccion_paisV, 
                                articulos,
                                valor_pedido,
                                gastos_envio,
                                total_pedido,
                                nombre_carta,
                                cantidad_carta,
                                idioma_carta,
                                observacion_carta,
                                estado_carta,
                                precio_carta,
                                estado_pedido
                                ) VALUES (
                                :ventaGuid,
                                :pedidoId,
                                :fecha_venta,
                                :nombre_usuarioC, 
                                :direccion_nombreC, 
                                :direccion_lineaC, 
                                :direccion_calleC, 
                                :direccion_localidadC, 
                                :direccion_codpostC, 
                                :direccion_paisC, 
                                :nombre_usuarioV, 
                                :direccion_nombreV, 
                                :direccion_lineaV, 
                                :direccion_calleV, 
                                :direccion_localidadV, 
                                :direccion_codpostV, 
                                :direccion_paisV, 
                                :articulos,
                                :valor_pedido,
                                :gastos_envio,
                                :total_pedido,
                                :nombre_carta,
                                :cantidad_carta,
                                :idioma_carta,
                                :observacion_carta,
                                :estado_carta,
                                :precio_carta,
                                "pagado"
                                )';
                                $stmt = $instance->prepare($sql);
                                $stmt->bindValue(':ventaGuid', $clienteGuid);
                                $stmt->bindValue(':pedidoId', $nuevo_pedidoId, PDO::PARAM_INT);
                                $stmt->bindValue(':fecha_venta', $fecha_cv);
                                $stmt->bindValue(':nombre_usuarioC', $nombre_usuario["nombre_usuario"]);
                                $stmt->bindValue(':direccion_nombreC', $direccionC['nombre']);
                                $stmt->bindValue(':direccion_lineaC', $direccionC['lineaExtra']);
                                $stmt->bindValue(':direccion_calleC', $direccionC['calle']);
                                $stmt->bindValue(':direccion_localidadC', $localidad);
                                $stmt->bindValue(':direccion_codpostC', $codigoPostal);
                                $stmt->bindValue(':direccion_paisC', $direccionC['pais']);
                                $stmt->bindValue(':nombre_usuarioV', $vendedor);
                                $stmt->bindValue(':direccion_nombreV', $direccionV['direccion_nombre' . $direccion_principal]);
                                $stmt->bindValue(':direccion_lineaV', $direccionV['direccion_linea' . $direccion_principal]);
                                $stmt->bindValue(':direccion_calleV', $direccionV['direccion_calle' . $direccion_principal]);
                                $stmt->bindValue(':direccion_localidadV', $direccionV['direccion_localidad' . $direccion_principal]);
                                $stmt->bindValue(':direccion_codpostV', $direccionV['direccion_codpost' . $direccion_principal]);
                                $stmt->bindValue(':direccion_paisV', $direccionV['direccion_pais' . $direccion_principal]);
                                $stmt->bindValue(':articulos', $totalArticulos);
                                $stmt->bindValue(':valor_pedido', $precioTotal);
                                $stmt->bindValue(':gastos_envio', $gastosEnvio);
                                $stmt->bindValue(':total_pedido', $totalPedido);
                                $stmt->bindValue(':nombre_carta', $carta['nombreCarta']);
                                $stmt->bindValue(':cantidad_carta', $carta['cantidad']);
                                $stmt->bindValue(':idioma_carta', $carta['idioma']);
                                $stmt->bindValue(':observacion_carta', $carta['observacion']);
                                $stmt->bindValue(':estado_carta', $carta['estado']);
                                $stmt->bindValue(':precio_carta', $carta['precio']);

                                $stmt->execute();
                            }
                        } else {
                            echo "Error: No se pudo obtener la dirección para el vendedor {$vendedor} en el pedido {$index}.\n";
                        }
                    } else {
                        echo "Error: No se encontró una dirección principal para el vendedor {$vendedor} en el pedido {$index}.\n";
                    }
                } else {

                    echo "Error al borrar.";
                }
            } else {
                echo json_encode(['error' => 'Faltan campos en un pedido.']);
            }
        }
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage() . "\n";
    } catch (Exception $e) {
        echo "Error general: " . $e->getMessage() . "\n";
    }
} else {
    echo json_encode(['error' => 'Formato de datos incorrecto o no hay resultados.']);
}
