<footer id="footer">
    <div class="container-fluid">

        <div class="row">
            <div class="col">

                <ul class="nav align-items-center justify-content-center fw-bold">

                    <li class="nav-item text-uppercase">
                        <a href="../../contenido/condiciones_legales.html" class="nav-link">
                            Condiciones legales
                        </a>
                    </li>

                    <li class="nav-item text-uppercase">
                        <a href="../../contenido/politica_de_privacidad.html" class="nav-link">
                            Política de privacidad
                        </a>
                    </li>

                    <li class="nav-item text-uppercase">
                        <a href="../../contenido/ayuda.html" class="nav-link">
                            Ayuda
                        </a>
                    </li>

                    <!-- IDIOMA -->
                    <li class="dropup nav-item ms-auto">

                        <a href="#" class="nav-link dropdown-toggle text-nowrap text-uppercase"
                           data-bs-toggle="dropdown">

                            <small class="text-muted">Idioma:</small>
                            <span class="mx-2">🌐</span>
                            <span>Español</span>

                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <h6 class="dropdown-header d-none d-lg-block">
                                    Escoge un idioma
                                </h6>
                            </li>

                            <li><a href="./?lang=en" class="dropdown-item text-uppercase">🇺🇸 Inglés</a></li>
                            <li><a href="./?lang=fr" class="dropdown-item text-uppercase">🇫🇷 Francés</a></li>
                            <li><a href="./?lang=de" class="dropdown-item text-uppercase">🇩🇪 Alemán</a></li>
                            <li><a href="./?lang=it" class="dropdown-item text-uppercase">🇮🇹 Italiano</a></li>

                            <li>
                                <p class="dropdown-item text-muted py-2 small">
                                    ESTO ESTARÁ IMPLEMENTADO EN UN FUTURO.
                                </p>
                            </li>

                        </ul>
                    </li>

                </ul>

            </div>
        </div>

        <!-- FECHA ACTUAL (Date object) -->
        <div class="row mt-2">
            <div class="col text-center small text-muted" id="fecha_footer">
                <!-- JS lo rellena -->
            </div>
        </div>

        <div id="socialmedia" class="row align-items-center">
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <div class="sharethis-inline-share-buttons"></div>
            </div>
        </div>

        <a href="#modal" id="backToTop">
            <span class="fonticon-chevron-up"></span>
        </a>

    </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const fecha = new Date();

    const fechaFormateada = fecha.toLocaleDateString("es-ES", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric"
    });

    const hora = fecha.toLocaleTimeString("es-ES");

    const salida = `${fechaFormateada} - ${hora}`;

    const contenedor = document.getElementById("fecha_footer");

    if (contenedor) {
        contenedor.textContent = salida;
    }
});
</script>