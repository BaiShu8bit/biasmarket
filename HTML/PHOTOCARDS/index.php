<?php
session_start();

$id = $_GET['id'] ?? null;
$logueado = isset($_SESSION["clienteId"]);
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
    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=664f0a903a56e900196c14d1&product=inline-share-buttons&source=platform" async="async"></script>
</head>

<body>
    <?php if (!$logueado) {
        include '../../contenido/cabeceras/cabecera_no_sesion.php'; ?>

        <!--Navegador-->
        <nav aria-label="breadcrumb">
            <ol id="breadcrumb" class="breadcrumb"></ol>
        </nav>

        <br>

        <!--IMAGENES DE CARTAS(API)-->
        <div class="card mb-3" id="ficha_carta" style="max-width: 50%;">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="imagen_informacion-tab" data-bs-toggle="pill" data-bs-target="#imagen_informacion" type="button" role="tab" aria-controls="imagen_informacion" aria-selected="true">Información</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="imagen_informacion" role="tabpanel" aria-labelledby="imagen_informacion-tab">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="" class="img-fluid rounded-start" id="imagen_ficha_carta" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 id="nombre_carta"></h5>

                                <p><strong>Grupo:</strong> <a id="link_grupo" href="#"><span id="grupo_carta"></span></a></p>
                                <p><strong>Miembro:</strong> <span id="miembro_carta"></span></p>
                                <p><strong>Álbum:</strong> <a id="link_album" href="#"><span id="album_carta"></span></a></p>
                                <hr>

                                <h5 class="card-title">Tendencia de precio</h5>
                                <p class="card-text" id="tendencia_precio"></p>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--MENSAJE CARRITO-->
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Atención:</strong> Para añadir artículos al carrito, es necesario iniciar sesión previamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    <?php  } else {
        include '../../contenido/cabeceras/cabecera_sesion.php'; ?>

        <!--Navegador-->
        <nav aria-label="breadcrumb">
            <ol id="breadcrumb" class="breadcrumb"></ol>
        </nav>

        <br>

        <!--IMAGENES DE CARTAS(API)-->
        <div class="card mb-3" id="ficha_carta" style="max-width: 50%;">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="imagen_informacion-tab" data-bs-toggle="pill" data-bs-target="#imagen_informacion" type="button" role="tab" aria-controls="imagen_informacion" aria-selected="true">Información</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="imagen_vender-tab" data-bs-toggle="pill" data-bs-target="#imagen_vender" type="button" role="tab" aria-controls="imagen_vender" aria-selected="false">Vender</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="imagen_informacion" role="tabpanel" aria-labelledby="imagen_informacion-tab">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="" class="img-fluid rounded-start" id="imagen_ficha_carta" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 id="nombre_carta"></h5>

                                <p><strong>Grupo:</strong> <a id="link_grupo" href="#"><span id="grupo_carta"></span></a></p>
                                <p><strong>Miembro:</strong> <span id="miembro_carta"></span></p>
                                <p><strong>Álbum:</strong> <a id="link_album" href="#"><span id="album_carta"></span></a></p>
                                <hr>

                                <h5 class="card-title">Tendencia de precio</h5>
                                <p class="card-text" id="tendencia_precio"></p>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="imagen_vender" role="tabpanel" aria-labelledby="imagen_vender-tab">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="" class="img-fluid rounded-start" id="imagen_ficha_carta2" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <form name="form_publicacion" id="form_publicacion" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <input type="hidden" id="photocard_id" name="photocard_id">
                                        <input type="hidden" id="nombre_carta_hidden" name="nombre_carta">
                                        <input type="hidden" id="imagen_carta_hidden" name="imagen_carta">
                                        <label for="form_cantidad" class="form-label"><strong>Cantidad</strong></label>
                                        <input type="number" class="form-control" id="form_cantidad" aria-describedby="cantidad" name="form_cantidad" min="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="form_estado" class="form-label"><strong>Estado</strong></label>
                                        <select class="form-select" id="form_estado" name="form_estado" aria-label="" required>
                                            <option value="mint">Perfecto</option>
                                            <option value="near_mint">Casi perfecto</option>
                                            <option value="excellent">Excelente</option>
                                            <option value="good">Bueno</option>
                                            <option value="poor">Dañada</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="form_imagen" class="form-label"><strong>Imagen de la carta</strong></label>
                                        <input
                                            type="file"
                                            class="form-control"
                                            id="form_imagen"
                                            name="form_imagen"
                                            accept="image/*"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label for="form_observaciones" class="form-label"><strong>Observaciones</strong></label>
                                        <input type="text" class="form-control" id="form_observaciones" name="form_observaciones">
                                    </div>
                                    <div class="mb-3">
                                        <label for="form_precio" class="form-label"><strong>Precio</strong></label>
                                        <input type="number" class="form-control" id="form_precio" name="form_precio" aria-describedby="precio" placeholder="0.00€" required>
                                    </div>
                                    <button type="submit" id="datos_form_publicacion" class="btn btn-primary">Poner en venta</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modal_editar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="editar_publicacion">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">Editar carta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="estado_editar" class="form-label">Estado</label>
                        <select class="form-select" id="estado_editar" name="estado_editar" required>
                            <option value="mint">Perfecto</option>
                            <option value="near_mint">Casi perfecto</option>
                            <option value="excellent">Excelente</option>
                            <option value="good">Bueno</option>
                            <option value="poor">Dañada</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="observacion_editar" class="form-label">Observaciones</label>
                        <input type="text" id="observacion_editar" name="observacion_editar" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="precio_editar" class="form-label">Precio</label>
                        <input type="text" id="precio_editar" name="precio_editar" class="form-control"
                               pattern="^([0-9]+)?([.,][0-9]{1,2})?$">
                    </div>

                    <div class="mb-3">
                        <label for="cantidad_editar" class="form-label">Cantidad</label>
                        <input type="number" id="cantidad_editar" name="cantidad_editar"
                               class="form-control" min="0">
                    </div>

                    <!-- ID OCULTO -->
                    <input type="hidden" id="id_editar" name="id_editar">

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btn_eliminar_publicacion">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Editar</button>
                    
                </div>

            </form>

        </div>
    </div>
</div>
    <?php } ?>

    <!--TABLA PUBLICACIONES-->
    <?php

    require('../../BBDD/connection.php');

    $instance = ConnectionDB::getInstance();

    $photocardId = $_GET['id'] ?? '';

    $sql = "
    SELECT
        publicaciones.*,
        clientes.nombre_usuario
    FROM publicaciones
    INNER JOIN clientes
    ON publicaciones.clienteId = clientes.clienteId
    WHERE photocardId = :photocardId
";

    $stmt = $instance->prepare($sql);

    $stmt->bindValue(':photocardId', $photocardId);

    $stmt->execute();

    $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <!--TRADUCIR ESTADOS-->
    <?php
    function traducirEstadoBadge(string $estado): string
    {

        switch ($estado) {

            case "mint":
                return '<span class="badge-estado mint">M</span>';

            case "near_mint":
                return '<span class="badge-estado near-mint">NM</span>';

            case "excellent":
                return '<span class="badge-estado excellent">EX</span>';

            case "good":
                return '<span class="badge-estado good">GD</span>';

            case "poor":
                return '<span class="badge-estado poor">PO</span>';

            default:
                return '<span class="badge-estado">' . $estado . '</span>';
        }
    }
    ?>

    <div class="table-responsive">

        <table id="tabla_publicaciones" class="table table-striped table-bordered">

            <thead>

                <tr>

                    <th>Vendedor</th>
                    <th>Estado</th>
                    <th>Imagen</th>
                    <th>Observaciones</th>
                    <th data-sort="precioCarta">Precio ▲▼</th>
                    <th>Cantidad</th>
                    <th>Carrito</th>

                </tr>

            </thead>

            <tbody>

                <?php if (!empty($publicaciones)) { ?>

                    <?php foreach ($publicaciones as $publicacion) { ?>

                        <tr>

                            <td>
                                <?php echo $publicacion['nombre_usuario']; ?>
                            </td>

                            <td>
                                <?php echo traducirEstadoBadge($publicacion['estadoCarta']); ?>
                            </td>

                            <td style="text-align:center;">

                                <?php if (!empty($publicacion['imagenCarta'])) { ?>

                                    <a href="/biasmarket/contenido/uploads/<?php echo $publicacion['imagenCarta']; ?>"
                                    target="_blank"
                                    title="Ver imagen">
                                        📷
                                    </a>

                                <?php } else { ?>

                                    <span style="opacity: 0.3;">📷</span>

                                <?php } ?>

                            </td>

                            <td>
                                <?php echo $publicacion['observacionesCarta']; ?>
                            </td>

                            <td>
                                <?php echo $publicacion['precioCarta']; ?>€
                            </td>

                            <td>
                                <?php echo $publicacion['cantidadCarta']; ?>
                            </td>

                            <td>

                                <select class="cantidad-select">
                                    <?php for ($i = 1; $i <= $publicacion['cantidadCarta']; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>

                                <button
                                    class="cart-btn boton_carrito"
                                    data-publicacion-id="<?php echo $publicacion['publicacionId']; ?>"
                                    data-photocard-id="<?php echo $publicacion['photocardId']; ?>"
                                    data-vendedor="<?php echo htmlspecialchars($publicacion['nombre_usuario']); ?>"
                                    data-estado="<?php echo htmlspecialchars($publicacion['estadoCarta']); ?>"
                                    data-observaciones="<?php echo htmlspecialchars($publicacion['observacionesCarta']); ?>"
                                    data-precio="<?php echo $publicacion['precioCarta']; ?>">
                                    <span class="carrito">🛒</span>
                                </button>

                                <button
                                    class="btn btn-warning btn-editar"
                                    data-id="<?php echo $publicacion['publicacionId']; ?>"
                                    data-estado="<?php echo $publicacion['estadoCarta']; ?>"
                                    data-observacion="<?php echo htmlspecialchars($publicacion['observacionesCarta']); ?>"
                                    data-precio="<?php echo $publicacion['precioCarta']; ?>"
                                    data-cantidad="<?php echo $publicacion['cantidadCarta']; ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal_editar"
                                >
                                    ✏️
                                </button>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td class="unavailable" colspan="6">
                            Ninguna carta disponible en este momento
                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

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

    <script src="../../scripts/scripts.js"></script>
    <script src="../../scripts/PHOTOCARDS/scripts.js"></script>
    <script src="../../funciones/funciones.js"></script>

</body>

</html>