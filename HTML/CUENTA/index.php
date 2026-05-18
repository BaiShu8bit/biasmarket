<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biasmarket</title>

    <link rel="stylesheet" href="./styles/style.css">
    <?php if (!isset($_SESSION["clienteId"])) { ?>
        <link rel="stylesheet" href="../styles/style.css">

    <?php } else { ?>

        <link rel="stylesheet" href="../styles/style_sesion.css">
    <?php } ?>
    <link rel="icon" href="../../contenido/iconos/favicon.webp">

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

        <h1 id="titulo-opciones">Cuenta</h1>

        <div class="perfil-container">
            <!-- Barra lateral -->
            <div class="perfil-sidebar">
                <button class="sidebar-btn active" data-target="cuenta">Cuenta</button>
                <button class="sidebar-btn" data-target="direcciones">Direcciones</button>
            </div>

            <!-- Área de detalles -->
            <div class="perfil-detalles">
                <!-- Sección Cuenta -->
                <div class="seccion-cuenta seccion-activa">
                    <h2 id="nombre_usuario1"></h2>
                    <div class="info-cuenta">
                        <p id="nombre"></p>
                        <p id="email"></p>
                        <p id="fechaRe"></p>
                        <p id="estadoCuenta" style="display: none;"></p>
                        <!--<button id="boton_cuenta" class="btn-editar"><i class="fas fa-pencil-alt"></i></button>-->
                    </div>
                    <div class="info-cuenta-direccion">
                        <p><strong>Dirección principal:</strong></p>
                        <p id="linea1"></p>
                        <p id="linea2"></p>
                        <p id="calle"></p>
                        <p id="codpostal"></p>
                        <p id="pais"></p>
                    </div>
                    <div class="info-cuenta-saldo">
                        <p id="saldo"></p>
                    </div>
                </div>


                <!-- Sección Direcciones -->
                <div class="seccion-direcciones">

                    <h2>Mis direcciones</h2>

                    <!-- BOTÓN AÑADIR DIRECCIÓN -->
                    <button id="btn_nueva_direccion" class="btn-agregar-main">
                        <i class="fas fa-plus"></i> Nueva dirección
                    </button>

                    <!-- GRID DIRECCIONES -->
                    <div class="direcciones-grid" id="lista_direcciones">

                        <!-- EJEMPLO TEMPLATE (JS lo clonará o lo rellenará) -->
                        <!-- Si no hay direcciones, JS puede mostrar un mensaje -->

                        <div class="direccion plantilla-direccion" style="display:none;">
                            <p class="dir-principal"><strong class="txt-principal"></strong></p>

                            <p class="nombre"></p>
                            <p class="linea2"></p>
                            <p class="calle"></p>
                            <p class="codpostal-localidad"></p>
                            <p class="pais"></p>

                            <div class="btn-group">
                                <button class="btn-editar btn-edit-dir">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>

                                <button class="btn-camion btn-principal-dir">
                                    <i class="fas fa-truck"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ===================== -->
                <!-- MODAL DIRECCIONES -->
                <!-- ===================== -->
                <div id="direccionModal" class="modal" style="display:none;">

                    <div class="modal-content">

                        <span id="closeModal" class="close">&times;</span>

                        <h2 id="modal_title">Nueva dirección</h2>

                        <form id="direccionForm">

                            <input type="hidden" id="direccionId">

                            <label>Nombre</label>
                            <input type="text" id="modal_nombre" required>

                            <label>Apellidos</label>
                            <input type="text" id="modal_linea2">

                            <label>Calle</label>
                            <input type="text" id="modal_calle" required>

                            <label>Código Postal</label>
                            <input type="text" id="modal_codpostal" required>

                            <label>Localidad</label>
                            <input type="text" id="modal_localidad" required>

                            <label>País</label>
                            <input type="text" id="modal_pais" required>

                            <button type="submit">Guardar</button>
                        </form>

                    </div>

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

            <script src="../../scripts/CUENTA/scripts.js"></script>

            <script src="../../funciones/funciones.js"></script>
            <script src="../../funciones/CUENTA/funciones.js"></script>
     
</body>

</html>