<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biasmarket</title>

    <link rel="stylesheet" href="./styles/gestion.css">
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

<?php
require('../../BBDD/connection.php');

$instance = ConnectionDB::getInstance();

/* =========================
   ELIMINAR USUARIO
========================= */
if (isset($_GET["delete"])) {

    $idEliminar = intval($_GET["delete"]);

    $sqlDelete = "DELETE FROM clientes WHERE clienteId = :id";
    $stmtDelete = $instance->prepare($sqlDelete);
    $stmtDelete->bindValue(":id", $idEliminar);
    $stmtDelete->execute();

    header("Location: gestion.php");
    exit;
}

/* =========================
   OBTENER USUARIOS
========================= */
$sql = "SELECT clienteId, nombre_usuario, email, rol, fecha_alta FROM clientes";
$stmt = $instance->prepare($sql);
$stmt->execute();

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="contenedor">

     <h2 class="titulo">Gestión de usuarios</h2>

    <table class="table table-striped table-bordered">
      
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Fecha alta</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($usuarios as $user) { ?>

                <tr>
                    <td><?= $user["clienteId"] ?></td>
                    <td><?= htmlspecialchars($user["nombre_usuario"]) ?></td>
                    <td><?= htmlspecialchars($user["email"]) ?></td>
                    <td><?= $user["rol"] ?></td>
                    <td><?= $user["fecha_alta"] ?></td>

                    <td>

                        <?php if ($user["rol"] !== "admin") { ?>

                            <a
                                href="gestion.php?delete=<?= $user["clienteId"] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Seguro que quieres eliminar este usuario?')"
                            >
                                Eliminar
                            </a>

                        <?php } else { ?>

                            <span class="text-muted">No permitido</span>

                        <?php } ?>

                    </td>
                </tr>

            <?php } ?>

        </tbody>

    </table>

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