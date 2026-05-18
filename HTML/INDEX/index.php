<?php
session_start();
error_log("Session started in index.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiasMarket</title>

    <link rel="stylesheet" href="./styles/style.css">

    <?php if (!isset($_SESSION["clienteId"])) { ?>
        <link rel="stylesheet" href="../styles/style.css">

    <?php } else { ?>

        <link rel="stylesheet" href="../styles/style_sesion.css">
    <?php } ?>


    <link rel="icon" href="../../contenido/iconos/favico.webp">

    <!-- Bootstrap core CSS -->
    <link href="../../LIBRERIAS/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../styles/navbar-top-fixed.css" rel="stylesheet">

    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.29.2.min.js"></script>
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
    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=664f0a903a56e900196c14d1&product=inline-share-buttons&source=platform" async="async"></script>
</head>

<body>
    <?php
    // LÓGICA LIMPIA DE SESIÓN
    error_log("About to check session for header selection");
    if (isset($_SESSION["clienteId"])) {
        error_log("Including cabecera_sesion.php");
        include '../../contenido/cabeceras/cabecera_sesion.php';
    } else {
        error_log("Including cabecera_no_sesion.php");
        include '../../contenido/cabeceras/cabecera_no_sesion.php';
    }
    ?>

    <div id="alerta"></div>
    <section>
        <!-- CAROUSEL PIMPED -->
        <div id="Carousel-slider">
            <button id="prev-slide">❮</button>

            <button id="next-slide">❯</button>
            <div id="carousel-background"></div>

            <!-- DOTS -->
            <div class="carousel-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>

            <!-- SLIDER -->
            <div class="Carousel-slider">
                <div class="slider-item album1 active"
                    data-bg="../../../contenido/iconos/bg1.jpg"
                    data-album-id="cmmuy6r6n006zjo045zh901if"
                ></div>
                

                <div class="slider-item album2"
                    data-bg="../../../contenido/iconos/bg2.jpg"
                    data-album-id="cmklcbh90000ql404630xce8z"
                ></div>

                <div class="slider-item album3"
                    data-bg="../../../contenido/iconos/bg3.jpg"
                    data-album-id="cmkgkapaj002zl904mkjrwh4o"
                ></div>

                <div class="slider-item album4"
                    data-bg="../../../contenido/iconos/bg5.jpg"
                    data-album-id="cmo9imnrl000dl604oods5gfn"
                ></div>

            </div>

        </div>
    </section>

    <!--IMAGENES DE CARTAS(API)-->
    <div class="container">
        <h1 class="text_tittle mt-4"><span>📈</span>Las más vendidas</h1>
        <hr class="divider">

        <div id="imagenes" class="row justify-content-center promo--end">
        </div>

        <br>

        <a href="../BEST_SELLERS/index.php" id="best_sellers"><button class="btn btn-dark">Ver más</button></a>

        <br><br>
        <div class="secciones-flex">

            <!-- ÁLBUMES -->
            <div class="bloque">
                <h1 class="text_tittle mt-4"><span>📚</span>Álbumes</h1>
                <hr class="divider2">

                <div id="album_destacado" class="text-center mb-4"></div>
                <ol id="album_list" class="album-list" start="2"></ol>
            </div>

            <!-- GRUPOS -->
            <div class="bloque">
                <h1 class="text_tittle mt-4"><span>👥</span>Grupos</h1>
                <hr class="divider2">

                <div id="grupo_destacado" class="text-center mb-4"></div>
                <ol id="grupo_list" class="group-list" start="2"></ol>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <script src="../../scripts/INDEX/scripts.js"></script>
    <script src="../../funciones/funciones.js"></script>

</body>

</html>