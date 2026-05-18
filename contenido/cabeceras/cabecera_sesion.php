<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_log(
    "cabecera_sesion.php loaded - Session clienteId: " .
    ($_SESSION["clienteId"] ?? "NOT SET")
);
?>

<link href="https://fonts.googleapis.com/css2?family=Bungee&family=Righteous&family=Syncopate:wght@700&family=Unbounded:wght@900&family=Permanent+Marker&display=swap" rel="stylesheet">

<nav class="navbar navbar-expand-xl navbar-light fixed-top bg-light">

    <div class="container-fluid" id="nav">

        <!-- ========================================
             LOGO
        ========================================= -->
        <div id="logo" class="d-flex align-items-center">

            <a href="../INDEX/index.php">
                <img
                    src="../../contenido/iconos/logo.png"
                    alt="Online_card"
                    id="Online_card"
                    width="76"
                >
            </a>

            <span id="tittle">biasmarket</span>

        </div>

        <!-- ========================================
             HAMBURGUESA
        ========================================= -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- ========================================
             NAVBAR
        ========================================= -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- ==============================
                 BOTONES CENTRALES
            =============================== -->
            <div
                class="
                    d-flex
                    flex-xl-row
                    flex-column
                    align-items-center
                    justify-content-center
                    gap-3
                    flex-grow-1
                    mt-3
                    mt-xl-0
                "
            >

                <!-- ==========================
                     PERFIL
                =========================== -->
                <div class="text-center dropdown">

                    <button
                        class="
                            btn
                            btn-light
                            dropdown-toggle
                            d-flex
                            flex-column
                            justify-content-center
                            align-items-center
                        "
                        type="button"
                        id="dropdownPerfil"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <div>

                            <img
                                src="../../contenido/iconos/usuario.png"
                                alt="logo usuario"
                                style="width: 25px;"
                            >

                            <strong id="nombre_usuario">

                                <?php
                                echo htmlspecialchars(
                                    $_SESSION["nombre_usuario"] ?? "Usuario"
                                );
                                ?>

                            </strong>

                        </div>

                        <div id="monedero"></div>

                    </button>

                    <ul
                        class="dropdown-menu"
                        aria-labelledby="dropdownPerfil"
                    >

                        <li>
                            <a
                                class="dropdown-item"
                                href="../../HTML/CUENTA/index.php"
                            >
                                CUENTA
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- ==========================
                     VENDER
                =========================== -->
                <div class="text-center dropdown">

                    <button
                        class="
                            btn
                            btn-light
                            dropdown-toggle
                            d-flex
                            flex-column
                            justify-content-center
                            align-items-center
                        "
                        type="button"
                        id="dropdownVender"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <div>

                            <img
                                src="../../contenido/iconos/vender.png"
                                alt="logo vender"
                                style="width: 25px;"
                            >

                            <strong>VENDER</strong>

                        </div>

                    </button>

                    <ul
                        class="dropdown-menu"
                        aria-labelledby="dropdownVender"
                    >

                        <li>
                            <a
                                class="dropdown-item"
                                href="../VENDER/index.php"
                            >
                                VENTAS
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- ==========================
                     COMPRAR
                =========================== -->
                <div class="text-center dropdown">

                    <button
                        class="
                            btn
                            btn-light
                            dropdown-toggle
                            d-flex
                            flex-column
                            justify-content-center
                            align-items-center
                        "
                        type="button"
                        id="dropdownComprar"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <div>

                            <img
                                src="../../contenido/iconos/comprar.png"
                                alt="logo comprar"
                                style="width: 25px;"
                            >

                            <strong>COMPRAR</strong>

                        </div>

                    </button>

                    <ul
                        class="dropdown-menu"
                        aria-labelledby="dropdownComprar"
                    >

                        <li>
                            <a
                                class="dropdown-item"
                                href="../COMPRAR/compras.php"
                            >
                                COMPRAS
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- ==========================
                     CARRITO
                =========================== -->
                <div class="text-center">

                    <a
                        href="../CARRITO/index.php"
                        class="
                            btn
                            btn-light
                            d-flex
                            flex-column
                            justify-content-center
                            align-items-center
                        "
                    >

                        <div>

                            <img
                                src="../../contenido/iconos/carrito-de-compras(1).png"
                                alt="logo carrito"
                                style="width: 25px;"
                            >

                            <strong>CARRITO</strong>

                        </div>

                        <span id="carrito"></span>

                    </a>

                </div>

            </div>

            <!-- ==============================
                 CERRAR SESIÓN
            =============================== -->
            <div class="mt-3 mt-xl-0 text-center">

                <a
                    href="../../funciones/auth/cerrar_sesion.php"
                    class="btn btn-dark"
                >
                    Cerrar Sesión
                </a>

            </div>

        </div>

    </div>

    <!-- ========================================
         BUSCADOR
    ========================================= -->
    <div id="cont_buscador" class="input-group bg-light p-3">

        <div class="form-outline">

            <input
                type="search"
                id="form1"
                class="form-control"
                placeholder="Buscar..."
            >

        </div>

        <button
            type="button"
            class="btn btn-primary busca"
            id="boton_buscador"
        >

            <i class="fas fa-search"></i>
            Buscar

        </button>

        <div
            id="suggestions"
            class="
                bg-light
                border
                rounded
                p-2
                position-absolute
            "
            style="display: none;"
        ></div>

    </div>

</nav>