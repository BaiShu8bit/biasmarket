<?php
error_reporting(E_ALL);

require('../../../BBDD/connection.php');

session_start();

// TABLA
$table = '(
    SELECT
        publicaciones.*,
        clientes.nombre_usuario
    FROM publicaciones
    INNER JOIN clientes
    ON publicaciones.clienteId = clientes.clienteId
) temp';

// PRIMARY KEY
$primaryKey = 'publicacionId';

// COLUMNAS
$columns = array(

    // VENDEDOR
    array(
        'db' => 'nombre_usuario',
        'dt' => 0
    ),

    // ESTADO
    array(
        'db' => 'estadoCarta',
        'dt' => 1,
        'formatter' => function ($d, $row) {

            if ($d == 'mint') {
                return 'Perfecto';
            }

            if ($d == 'near_mint') {
                return 'Casi perfecto';
            }

            if ($d == 'excellent') {
                return 'Excelente';
            }

            if ($d == 'good') {
                return 'Bueno';
            }

            if ($d == 'light_played') {
                return 'Ligeramente jugada';
            }

            if ($d == 'played') {
                return 'Muy jugada';
            }

            if ($d == 'poor') {
                return 'Dañada';
            }

            return $d;
        }
    ),

    // OBSERVACIONES
    array(
        'db' => 'observacionesCarta',
        'dt' => 2
    ),

    // PRECIO
    array(
        'db' => 'precioCarta',
        'dt' => 3,
        'formatter' => function ($d, $row) {

            $precio_carta = str_replace('.', ',', $d);

            return $precio_carta . '€';
        }
    ),

    // CANTIDAD
    array(
        'db' => 'cantidadCarta',
        'dt' => 4
    ),

    // SELECT CANTIDAD
    array(
        'db' => 'cantidadCarta',
        'dt' => 5,
        'formatter' => function ($d, $row) {

            if (isset($_SESSION['clienteId'])) {

                if ($_SESSION['clienteId'] == $row['clienteId']) {
                    return '';
                }
            }

            $options = '';

            for ($i = 1; $i <= $d; $i++) {

                $options .=
                    '<option value="' . $i . '">' . $i . '</option>';
            }

            return '
                <select style="width: 60px;">
                    ' . $options . '
                </select>
            ';
        }
    ),

    // CARRITO
    array(
        'db' => 'publicacionId',
        'dt' => 6,
        'formatter' => function ($d, $row) {

            if (!isset($_SESSION['clienteId'])) {

                return '
                    <img
                        src="/contenido/iconos/carrito-de-compras(2).png"
                        style="width:25px;"
                    >
                ';
            }

            if ($_SESSION['clienteId'] != $row['clienteId']) {

                return '
                    <button
                        type="button"
                        class="btn btn-light"
                        id="' . $d . '"
                    >
                        <img
                            src="/contenido/iconos/carrito-de-compras(3).png"
                            class="boton_carrito"
                            style="width:25px;"
                        >
                    </button>
                ';
            }

            return '';
        }
    ),

    // EDITAR
    array(
        'db' => 'clienteId',
        'dt' => 7,
        'formatter' => function ($d, $row) {

            if (!isset($_SESSION['clienteId'])) {
                return '';
            }

            if ($d == $_SESSION['clienteId']) {

                return '
                    <button
                        type="button"
                        class="btn btn-light boton_editar_concentrador"
                        data-bs-toggle="modal"
                        data-bs-target="#modal_editar"
                        data-publicacionid="' . $row['publicacionId'] . '"
                    >
                        <img
                            src="/contenido/iconos/editar.png"
                            style="width:25px;"
                        >
                    </button>
                ';
            }

            return '';
        }
    )
);

// CONEXIÓN
$sql_details = array(
    'user' => 'root',
    'pass' => '452000eL',
    'db'   => 'tfg',
    'host' => 'localhost'
);

// FILTRO ESTADO
$searchFilter = array();

if (
    !empty($_POST['filter_option']) &&
    in_array(
        $_POST['filter_option'],
        [
            'mint',
            'near_mint',
            'excellent',
            'good',
            'light_played',
            'played',
            'poor'
        ]
    )
) {

    $searchFilter['filter'] = array(
        'estadoCarta' => $_POST['filter_option']
    );
}

// PHOTOCARD ID
$photocardId = $_POST['photocard_id'] ?? '';

$where = '';

if (!empty($photocardId)) {

    $photocardId = addslashes($photocardId);

    $where = "photocardId = '$photocardId'";
}

// SSP
require 'ssp.class.php';

// OUTPUT JSON
echo json_encode(
    SSP::simple(
        $_POST,
        $sql_details,
        $table,
        $primaryKey,
        $columns,
        $where,
        $searchFilter
    )
);
