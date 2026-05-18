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

        <h1 id="carrito_titulo" style="margin-left: 20px; margin-top: 100px;">Carrito de la compra</h1>

        <div id="no_cartas_carrito" style="display: none;">

            <h4 style="color: gray;">Tu carrito está vacío</h4>

            <br> <br>

            <a href="../INDEX/index.php">
                <button id="volver_mercado">Volver al mercado</button>
            </a>
        </div>

        <div id="si_cartas_carrito" style="display: block;">
            <div style="display: flex; justify-content: space-between; border: 1px solid #ddd; border-radius: 5px; padding: 15px; background-color: #f8f9fa;">
                <div id="cartas_carrito" style="width: 65%;"></div>
                <div id="cartas_info" style="width: 30%; padding-left: 20px; border-left: 1px solid #ddd;">
                    <div style="margin-bottom: 20px;">
                        <h4>Dirección de entrega</h4>
                        <p id="nombre"></p>
                        <p id="linea_extra"></p>
                        <p id="calle"></p>
                        <p id="codpostal"></p>
                        <p id="pais"></p>
                        <button id="anyadir_direccion" class="btn btn-primary" type="button" style="display: none;"><a style="text-decoration: none; color: white;" href="../CUENTA/index.php">AÑADIR DIRECCIÓN</a></button>
                        <p id="mensaje_direccion" style="color:red;"></p>
                    </div>
                    <div>
                        <h4>Vista general del carrito</h4>

                        <div id="vendedores" style="margin-top: 20px;">

                        </div>
                        <div id="detalle_pedidos" style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px;">
                            <p><strong>Número de pedidos</strong> <span id="numero_pedidos"></span></p>
                            <p><strong>Cantidad de artículos</strong> <span id="numero_articulos"></span></p>
                        </div>
                        <div id="valor_pedidos" style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px;">
                            <p><strong>Valor del pedido</strong> <span id="valor_pedido"></span></p>
                            <p><strong>Gastos de envío</strong> <span id="gastos_envio"></span></p>
                        </div>
                        <div id="total_carrito" style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px;">
                            <p><strong>Total</strong> <span id="total">€</span></p>
                        </div>

                        <input id="confirmar_compra" type="text" style="display: none;" placeholder="Introduzca su contraseña" required>
                        <p id="mensaje_confirmar_compra" style="color: red;"></p>
                        <button id="pagar" style="display: none; margin-top: 10px; padding: 10px; background-color: #28a745; color: #fff; border: none; border-radius: 5px; cursor: pointer;">PAGAR</button>
                        <button id="proceder_pago" style="margin-top: 10px; padding: 10px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer;">PROCEDER AL PAGO</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="overlay">
            <div style="display: none;" id="tarjeta" class="card-container">
                <div class="credit-card">
                    <div class="card-front">
                        <div class="chip"></div>
                        <div class="card-number" id="card-number">#### #### #### ####</div>
                        <div class="card-holder">
                            <label>Titular de la Tarjeta</label>
                            <div id="card-holder">NOMBRE COMPLETO</div>
                        </div>
                        <div class="card-expiry">
                            <label>Expira</label>
                            <div id="card-expiry">MM/AA</div>
                        </div>
                    </div>
                </div>
                <form class="payment-form" name="payment-form" onsubmit="return false;">
                    <label for="input-card-number">Número de Tarjeta</label>
                    <input type="text" id="input_card_number" name="input_card_number" maxlength="19" placeholder="1234 5678 9123 4567"  required>
                    <p id="mensaje_input_card_number" style="color:red;"></p>

                    <label for="input-card-holder">Nombre del Titular</label>
                    <input type="text" id="input_card_holder" name="input_card_holder" placeholder="Nombre Completo"  required>
                    <p id="mensaje_input_card_holder" style="color:red;"></p>

                    <label for="input-card-expiry">Fecha de Expiración</label>
                    <input type="text" id="input_card_expiry" name="input_card_expiry" maxlength="5" placeholder="MM/AA"  required>
                    <p id="mensaje_input_card_expiry" style="color:red;"></p>

                    <label for="input-cvv">CVV</label>
                    <input type="password" id="input_cvv" name="input_cvv" maxlength="3" placeholder="123" required>
                    <p id="mensaje_input_cvv" style="color:red;"></p>

                    <button id="pagar_ahora" type="submit">Pagar Ahora</button>
                </form>
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

    <script src="../../scripts/scripts.js" defer></script>
    <script src="../../scripts/CARRITO/scripts.js" defer></script>

</body>

</html>