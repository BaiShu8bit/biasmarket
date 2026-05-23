<?php

error_reporting(E_ALL);

require('../../../BBDD/connection.php');

session_start();



/*
========================================
TABLA
========================================
*/

$table = 'publicaciones';

$primaryKey = 'publicacionId';



/*
========================================
COLUMNAS
========================================
*/

$columns = array(

    // ID USUARIO
    array(
        'db' => 'clienteId',
        'dt' => 0
    ),

    // ESTADO
    array(
        'db' => 'estadoCarta',
        'dt' => 1,
        'formatter' => function ($d, $row) {

            $estados = array(

                'mint' => 'Perfecto',

                'near_mint' => 'Casi perfecto',

                'excellent' => 'Excelente',

                'good' => 'Bueno',

                'light_played' => 'Ligeramente jugada',

                'played' => 'Muy jugada',

                'poor' => 'Dañada'
            );

            return $estados[$d] ?? $d;
        }
    ),

    // SUBIDA DE IMAGEN
    array(
    'db' => 'imagenCarta',
    'dt' => 8,
    'formatter' => function ($d, $row) {

        if (!empty($d)) {

            return '
                <a href="../../uploads/' . $d . '" target="_blank">
                    📷
                </a>
            ';
        }

        return '<span style="opacity:0.3;">📷</span>';
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

            return number_format($d, 2, ',', '.') . '€';
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

            if (
                isset($_SESSION['clienteId']) &&
                $_SESSION['clienteId'] == $row['clienteId']
            ) {

                return '';
            }

            $options = '';

            for ($i = 1; $i <= $d; $i++) {

                $options .=
                    '<option value="' . $i . '">' . $i . '</option>';
            }

            return '
                <select style="width:60px;">
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
                    >
                        <img
                            src="/contenido/iconos/carrito-de-compras(3).png"
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

            if (
                isset($_SESSION['clienteId']) &&
                $_SESSION['clienteId'] == $d
            ) {

                return '
                    <button
                        type="button"
                        class="btn btn-light"
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



/*
========================================
CONEXIÓN
========================================
*/

$sql_details = array(

    'user' => 'root',

    'pass' => '452000eL',

    'db' => 'tfg',

    'host' => 'localhost'
);



/*
========================================
WHERE
========================================
*/

$where = '';

$photocardId = $_POST['photocard_id'] ?? '';

if (!empty($photocardId)) {

    $where = "photocardId = '" . addslashes($photocardId) . "'";
}



/*
========================================
FILTRO ESTADO
========================================
*/

$filter = $_POST['filter_option'] ?? '';

if (!empty($filter)) {

    if (!empty($where)) {

        $where .= " AND ";
    }

    $where .= "estadoCarta = '" . addslashes($filter) . "'";
}