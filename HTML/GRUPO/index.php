<?php
session_start();
$logeado = isset($_SESSION["clienteId"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biasmarket</title>

    <link rel="stylesheet" href="./styles/style.css">

    <?php if ($logeado) { ?>
        <link rel="stylesheet" href="../styles/style_sesion.css">
    <?php } else { ?>
        <link rel="stylesheet" href="../styles/style.css">
    <?php } ?>

    <link rel="icon" href="../../contenido/iconos/favicon.webp">

    <link href="../../LIBRERIAS/assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../styles/navbar-top-fixed.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

<?php
if ($logeado) {
    include '../../contenido/cabeceras/cabecera_sesion.php';
} else {
    include '../../contenido/cabeceras/cabecera_no_sesion.php';
}
?>

<!--Navegador-->
<nav aria-label="breadcrumb">
    <ol id="breadcrumb" class="breadcrumb"></ol>
</nav>

<div class="container">

    <h1 id="nombre_grupo" class="text-center mt-4"></h1>

    <div id="imagenes" class="row justify-content-center promo--end"></div>


    <div class="bloque">

        <h1 class="text_tittle mt-4">Grupos</h1>
        <hr class="divider2">

        <ol id="grupo_list" class="group-list"></ol>

        <div class="pagination">
            <button id="prevBtn">Anterior</button>
            <span id="pageInfo"></span>
            <button id="nextBtn">Siguiente</button>
        </div>

    </div>

</div>

</div>

<br><br>

<?php include '../../contenido/footer/footer.php'; ?>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../../LIBRERIAS/assets/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../LIBRERIAS/jquery-validation-1.19.5/dist/jquery.validate.min.js"></script>

<script src="/biasmarket/scripts/GRUPO/scripts.js"></script>
<script src="../../funciones/funciones.js"></script>

</body>
</html>