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
    <?php } ?>    <link rel="icon" href="../../contenido/iconos/favicon.webp">

    <!-- Bootstrap core CSS -->
    <link href="../../LIBRERIAS/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../styles/navbar-top-fixed.css" rel="stylesheet">

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

    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
        <div class="container-fluid" id="nav">
            <div id="logo">
                <a href="../INDEX/index.php"><img src="../../contenido/iconos/logo.png" alt="Online_card" id="Online_card"></a>
                <p id="tittle">BiasMarket</p>
            </div>
        </div>
    </nav>

    <br>

    <form id="formulario_registro" method="POST" name="formulario_registro_vali">

        <div class="row">
            <div class="col mb-3">
                <label for="nombre" class="form-label">Nombre*</label>
                <input type="text" class="form-control" id="nombre" name="nombre" aria-describedby="nombre" placeholder="Nombre" required>
                <p id="mensaje_nombre"></p>
            </div>

            <div class="col mb-3">
                <label for="apellido1" class="form-label">Primer apellido*</label>
                <input type="text" class="form-control" id="apellido1" name="apellido1" aria-describedby="apellido1" placeholder="Apellidos" required>
                <p id="mensaje_apellido1"></p>
            </div>

            <div class="col mb-3">
                <label for="apellido2" class="form-label">Segundo apellido*</label>
                <input type="text" class="form-control" id="apellido2" name="apellido2" aria-describedby="apellido2" placeholder="Apellidos">
                <p id="mensaje_apellido2"></p>
            </div>
        </div>

        <div class="col mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento*</label>
            <br>
            <input type="text" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="01/01/2000" placeholder="00/00/0000" required>
        </div>

        <!--
        <div class="col mb-3">
            <label for="direccion" class="form-label">Dirección*</label>
            <input type="text" class="form-control" id="direccion" name="direccion" aria-describedby="direccion" placeholder="Dirección" required>
        </div>

        <div class="row">
            <div class="col mb-3">
                <label for="calle" class="form-label">Calle*</label>
                <input type="text" class="form-control" id="calle" name="calle" aria-describedby="calle" placeholder="Calle" required>
            </div>
            <div class="col mb-3">
                <label for="num_casa" class="form-label">Número de casa / piso*</label>
                <input type="text" class="form-control" id="num_casa" name="num_casa" aria-describedby="num_casa" placeholder="Número de casa / piso" required>
            </div>
        </div>

        <div class="row">
            <div class="col mb-3">
                <label for="codigo_postal" class="form-label">Código postal*</label>
                <input type="number" class="form-control" id="codigo_postal" name="codigo_postal" aria-describedby="codigo_postal" placeholder="Código postal" required>
            </div>
            <div class="col mb-3">
                <label for="ciudad" class="form-label">Ciudad*</label>
                <input type="text" class="form-control" id="ciudad" name="ciudad" aria-describedby="ciudad" placeholder="Localidad" required>
            </div>
        </div>
    -->

        <div class="col mb-3">
            <label for="pais" class="form-label">País*</label>
            <select class="form-select" id="pais" name="pais" aria-label="" required>
                <option selected>País</option>
                <option value="espanya">España</option>
            </select>
            <p id="mensaje_pais"></p>
        </div>

        <div class="col mb-3">
            <label for="email" class="form-label">Dirección Email*</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="email" placeholder="Correo electrónico" required>
            <p id="mensaje_email"></p>
        </div>

        <div class="col mb-3">
            <label for="ciudad" class="form-label">Elija un nombre de usuario y una contraseña*</label>
            <input type="usuario" class="form-control" id="usuario" name="usuario" aria-describedby="usuario" placeholder="Nombre de usuario" required><br>
            <p id="mensaje_usuario"></p>
            <input type="password" class="form-control" id="password" name="password" aria-describedby="password" placeholder="Contraseña (8-12 caracteres, debe contener letras y números)" required><br>
            <p id="mensaje_password"></p>
            <input type="password" class="form-control" id="password_repeat" name="password_repeat" aria-describedby="password_repeat" placeholder="Repita su contraseña" required>
            <p id="mensaje_password_repeat"></p>
        </div>

        <div class="mb-3 form-check">
            <h4>Políticas legales</h4>
            <div>
                <input type="checkbox" class="form-check-input" id="condiciones_legales" name="condiciones_legales">
                <label class="form-check-label" for="condiciones_legales">Por la presente declaro estar de acuerdo con las <a href="../../contenido/condiciones_legales.html">Condiciones legales</a>. Declaro haber leído las Condiciones de Revocación.</label>
            </div>
            <p id="mensaje_condiciones_legales"></p>
            <div>
                <input type="checkbox" class="form-check-input" id="politica_privacidad" name="politica_privacidad">
                <label class="form-check-label" for="politica_privacidad">Declaro haber leído la <a href="../../contenido/politica_de_privacidad.html">Política de privacidad</a> y estoy de acuerdo con el almacenamiento de mis datos. Sé que puedo revocar mi consentimiento en cualquier momento.</label>
            </div>
            <p id="mensaje_politica_privacidad"></p>
            <div>
                <input type="checkbox" class="form-check-input" id="declaracion" name="declaracion">
                <label class="form-check-label" for="declaracion">Declaro estar de acuerdo y solicito expresamente que comience la provisión de los servicios solicitados por mí antes de que expire el período de revocación. Soy consciente de que perderé mi derecho de revocación en caso de un cumplimiento completo del contrato por ustedes.</label>
            </div>
            <p id="mensaje_declaracion"></p>
        </div>

        <button type="submit" id="enviar_datos" class="btn btn-primary">Registrarme</button>
    </form>

    <br><br>

    <!--FOOTER-->
    <?php include '../../contenido/footer/footer.php'; ?>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="../../LIBRERIAS/jquery-validation-1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(function() {

            var fechaActual = moment();
            var maxYear = fechaActual.year() - 15;

            $('input[name="fecha_nacimiento"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1900,
                maxYear: maxYear,
                locale: {
                    format: 'DD/MM/Y'
                }
            });
        });
    </script>

    <script src="../../funciones/REGISTRO/funciones.js"></script>
    <script src="../../funciones/funciones.js"></script>

</body>

</html>