<?php
session_start();

$logueado = isset($_SESSION["clienteId"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biasmarket</title>

    <link rel="stylesheet" href="./styles/style.css">

    <?php if (!$logueado) { ?>
        <link rel="stylesheet" href="../styles/style.css">
    <?php } else { ?>
        <link rel="stylesheet" href="../styles/style_sesion.css">
    <?php } ?>

    <link rel="icon" href="../../contenido/iconos/favico.webp">

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

    <script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=664f0a903a56e900196c14d1&product=inline-share-buttons&source=platform"
        async="async"></script>
</head>

<body>

    <?php if (!$logueado) { ?>
        <?php include '../../contenido/cabeceras/cabecera_no_sesion.php'; ?>
    <?php } else { ?>
        <?php include '../../contenido/cabeceras/cabecera_sesion.php'; ?>
    <?php } ?>

    <!--NAVEGADOR-->
    <nav aria-label="breadcrumb">
        <ol id="breadcrumb" class="breadcrumb"></ol>
    </nav>

    <div class="container">

        <h1 id="nombre_grupo" class="text-left mt-4"></h1>

        <hr class="divider">

        <div id="albums_container" class="row justify-content-center"></div>

        <div class="text-center mt-4">

            <!-- PAGINACIÓN -->
            <div class="pagination">
                <button id="prevBtn">Anterior</button>
                <span id="pageInfo"></span>
                <button id="nextBtn">Siguiente</button>
            </div>

        </div>

    </div>

    <br><br>

    <!--FOOTER-->
    <?php include '../../contenido/footer/footer.php'; ?>

    <!--SCRIPTS NECESARIOS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../../LIBRERIAS/assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="../../LIBRERIAS/jquery-validation-1.19.5/dist/jquery.validate.min.js"></script>

    <script src="/biasmarket/scripts/ALBUM/scripts.js"></script>
    <script src="../../funciones/funciones.js"></script>

</body>

</html>