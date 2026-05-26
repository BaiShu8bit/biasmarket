<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biasmarket</title>

    <link rel="stylesheet" href="./styles/detalle_venta.css">
    <?php if (!isset($_SESSION["clienteId"])) { ?>
        <link rel="stylesheet" href="../styles/style.css">

    <?php } else { ?>

        <link rel="stylesheet" href="../styles/style_sesion.css">
    <?php } ?>    <link rel="icon" href="../../contenido/iconos/favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="../../LIBRERIAS/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../styles/navbar-top-fixed.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=664f0a903a56e900196c14d1&product=inline-share-buttons&source=platform" async="async"></script>
</head>

<body>
    <?php if ($_SESSION["clienteId"]) {
        include '../../contenido/cabeceras/cabecera_sesion.php'; ?>

        <a href="./index.php" style="text-decoration: none;color:#012269;margin-top: 12vh; margin-left: 15%; font-size: 18px;"><strong>Volver</strong></a>

        <div class="container">
            <h1 id="pedidoId" style="color: #012269;"></h1>

            <!-- Card 1 -->
            <div class="card">
                <div class="summary">
                    <div class="left">
                        <p id="resumen-pedido"></p>
                    </div>
                    <div class="right">
                        <p id="direccion-comprador"></p>
                    </div>
                </div>

                <div id="evaluacion_pedido" style="display: none;">
                <strong style="color: #012269;">Evaluación:</strong><br><br>
                    <p id="evaluacion_general"></p>
                    <p id="evaluacion_comentarios"></p>
                </div>

                <div id="numero_seguimiento" style="display: none;">
                    <label for="numero_seguimiento"><strong>Número de seguimiento:</strong></label>
                    <input type="text" id="input_numero_seguimiento" placeholder="Introduzca el numero de seguimiento..." style="width: 24vw;">
                    <button type="button" class="btn btn-primary" id="button_numero_seguimiento" style="display: none;">Guardar</button>
                </div>

                <div class="cartas">
                    <div id="cartas"></div>
                </div>
                <br>
                <div id="confirmar_envio">
                    <p><strong>Confirmar envio:</strong></p>
                    <button type="button" class="btn btn-primary" id="button_confirmar_envio">CONFIRMAR</button>
                </div>
            </div>
        </div>

    <?php } else { ?>
        <script>
            location.href = "../INDEX/index.php";
        </script> <?php } ?>

    <br><br>

    <!--FOOTER-->
    <?php include '../../contenido/footer/footer.php'; ?>

    <!--SCRIPSTD NECESARIOS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../../LIBRERIAS/assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="../../LIBRERIAS/jquery-validation-1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="../../scripts/scripts.js"></script>
    <script src="../../scripts/VENDER/detalle_venta_scripts.js"></script>

    <script src="../../funciones/funciones.js"></script>
    <script src="../../funciones/VENDER/detalle_venta_funciones.js"></script>
</body>

</html>