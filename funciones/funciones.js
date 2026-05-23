const API_URL =
  window.location.hostname === "localhost"
    ? "http://localhost:3000"
    : `http://${window.location.hostname}:3000`;

$(document).ready(function () {

    // Asegurar que jQuery está cargado
    if (typeof $ === 'undefined') {
        console.error("jQuery no está cargado");
        return;
    }

    // 🔥 LOGIN
    function iniciar_sesion() {

        var usuario = document.getElementById("usuario").value;
        var password = document.getElementById("password").value;

        var formData = new FormData();
        formData.append('usuario', usuario);
        formData.append('password', password);

        $.ajax({
            type: 'POST',
            url: '/biasmarket/funciones/auth/inicio_sesion.php',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {
                console.log("Raw response:", response);

                // Parse the JSON response (it comes as a string)
                var data = JSON.parse(response);
                console.log("Parsed data:", data);

                if (data.status === "success") {
                    localStorage.setItem("clienteId", data.clienteId);
                    location.reload();
                } else {
                    var mensaje_inicio = document.getElementById("mensaje_inicio");

                    if (mensaje_inicio) {
                        mensaje_inicio.innerHTML =
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            '<h4>Error</h4><p>' + data.message + '</p>' +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>';
                    }

                    document.getElementById("usuario").value = "";
                    document.getElementById("password").value = "";
                }
            },

            error: function (xhr, status, error) {
                console.error("AJAX error:", error);
            }
        });
    }

    // 🔥 VALIDACIÓN FORM LOGIN
    $("form[name='iniciar_sesion_vali']").validate({

        rules: {
            usuario: "required",
            password: {
                required: true,
                minlength: 1
            }
        },

        messages: {
            usuario: "Por favor introduzca su usuario",
            password: {
                required: "Por favor introduzca su contraseña",
                minlength: "Mínimo 1 carácter"
            }
        },

        submitHandler: function () {
            $("#mensaje_inicio").html("");
            iniciar_sesion();
            return false;
        }
    });

    // 🔥 LOGOUT (solo si existe el botón)
    const btnLogout = document.getElementById("cerrar_sesion");

    if (btnLogout) {
        btnLogout.addEventListener("click", function () {

            $.ajax({
                type: 'POST',
                url: '/biasmarket/funciones/auth/cerrar_sesion.php',

                success: function (response) {

                    if (response == 1) {
                        localStorage.removeItem("clienteId");
                        location.reload();
                    }
                },

                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });
    }

    // ==================================
    // BUSCADOR
    // ==================================

// =========================
// 🔥 CLICK EN SUGERENCIAS
// =========================
$(document).on("click", ".suggestion-item", function () {

    const photocardId =
        $(this).data("id");

    window.location.href =
        `/biasmarket/HTML/PHOTOCARDS/index.php?id=${photocardId}`;
});

    // 🔥 OCULTAR SUGERENCIAS
    $(document).click(function (e) {
        if (!$(e.target).closest("#cont_buscador").length) {
            $("#suggestions").hide();
        }
    });

    // ==================================
    // 🔥 BOTÓN BUSCAR
    // ==================================
    $("#boton_buscador").on("click", async function () {

    const query =
        $("#form1").val().trim().toLowerCase();

    if (query.length < 2) {

        alert("Introduce al menos 2 caracteres");

        return;
    }

    try {

        const response = await fetch(
            `${API_URL}/api/photocards`
        );

        const photocards =
            await response.json();

        const resultado =
            photocards.find(card =>

                card.name &&
                card.name.toLowerCase().includes(query)
            );

        if (resultado) {

            window.location.href =
                `/biasmarket/HTML/PHOTOCARDS/index.php?id=${resultado.id}`;

        } else {

            alert("No se encontraron resultados");
        }

    } catch (error) {

        console.error(error);
    }
});

// 🔥 AUTOCOMPLETE BUSCADOR

$("#form1").on("input", async function () {

    const query =
        $(this).val().trim().toLowerCase();

    if (query.length < 2) {

        $("#suggestions").hide();

        return;
    }

    try {

        const response = await fetch(
            `${API_URL}/api/photocards`
        );

        const photocards =
            await response.json();

        const resultados =
            photocards
                .filter(card =>

                    card.name &&
                    card.name.toLowerCase().includes(query)
                )
                .slice(0, 6);

        let html = "";

        resultados.forEach(card => {

            html += `

                <div
                    class="suggestion-item p-2"
                    data-id="${card.id}"

                    style="
                        cursor:pointer;
                        display:flex;
                        align-items:center;
                        gap:10px;
                    "
                >

                    <img
                        src="${card.watermarked_image_url}"

                        style="
                            width:40px;
                            height:55px;
                            object-fit:cover;
                            border-radius:6px;
                        "
                    >

                    <span>

                        ${card.name}

                    </span>

                </div>
            `;
        });

        $("#suggestions")
            .html(html)
            .show();

    } catch (error) {

        console.error(error);
    }
}); 
});
