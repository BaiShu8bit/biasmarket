<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light">
    <div class="container-fluid" id="nav">

        <!-- LOGO -->
        <div class="d-flex align-items-center">
            <div id="logo">
                <a href="../INDEX/index.php">
                    <img src="../../contenido/iconos/logo.png" alt="Online_card" id="Online_card" width="76">
                </a>
                <span id="tittle">biasmarket</span>
            </div>
        </div>

        <!-- BOTÓN HAMBURGUESA -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- CONTENIDO COLAPSABLE -->
        <div class="collapse navbar-collapse justify-content-center" id="menuResponsive">

            <!-- FORMULARIO DE INICIO DE SESIÓN -->
            <form name="iniciar_sesion_vali" method="POST" class="d-flex flex-column flex-md-row justify-content-center align-items-center">
                <div class="mb-2 me-md-2">
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                </div>
                <div class="mb-2 me-md-2">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                </div>
                <div class="mb-2 me-md-2">
                    <button type="submit" id="iniciar_sesion" class="btn btn-light">Iniciar Sesión</button>
                </div>
                <div class="mb-2">
                    <a href="../REGISTRO/index.php" class="btn btn-dark" id="registrarse">Registrarse</a>
                </div>
            </form>
            <span id="mensaje_inicio"></span>

        </div>
    </div>
</nav>

<!--BUSCADOR-->
<div id="cont_buscador" class="input-group bg-light p-3">
    <div class="form-outline w-65">
        <input type="search" id="form1" class="form-control" placeholder="Buscar...">
    </div>
    <button type="button" class="btn btn-primary" id="boton_buscador">
        <i class="fas fa-search"></i> Buscar
    </button>
    <div id="suggestions" class="bg-light border rounded p-2 position-absolute" style="display: none;"></div>
</div>