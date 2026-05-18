window.addEventListener("DOMContentLoaded", function () {

    const clienteId = localStorage.getItem("clienteId");

    // =========================
    // 👤 CARGA DE USUARIO
    // =========================
    if (clienteId) {

        $.ajax({
            url: "/biasmarket/funciones/datos_usuario.php",
            type: "POST",
            data: { clienteId: clienteId },

            success: function (response) {

                try {
                    const data = JSON.parse(response);

                    if (!data || !data[0]) return;

                    const user = data[0];

                    if (document.getElementById("nombre_usuario")) {
                        document.getElementById("nombre_usuario").innerHTML =
                            (user.nombre_usuario || "").toUpperCase();
                    }

                    if (document.getElementById("monedero")) {
                        document.getElementById("monedero").innerHTML =
                            (user.monedero || 0) + "€";
                    }

                    if (document.getElementById("carrito")) {
                        document.getElementById("carrito").innerHTML =
                            user.total_cartas || 0;
                    }

                } catch (e) {
                    console.error("Error parseando usuario:", e);
                }
            },

            error: function () {
                console.error("Error al cargar datos del usuario");
            }
        });
    }


    // =========================
    // 🔎 BUSCADOR (SUGERENCIAS)
    // =========================
    $(document).ready(function () {

        $("#form1").on("keyup", function () {

            const query = $(this).val().trim();

            if (query.length < 2) {
                $("#suggestions").hide();
                return;
            }

            $.ajax({
                type: "POST",
                url: "/biasmarket/funciones/api/photocards.php",
                data: { buscar: query },

                success: function (response) {

                    let data;

                    try {
                        data = JSON.parse(response);
                    } catch (e) {
                        console.error("Respuesta inválida:", response);
                        return;
                    }

                    const suggestions = $("#suggestions");
                    suggestions.empty();

                    if (data.image_names && data.image_names.length > 0) {

                        data.image_names.forEach((name, index) => {

                            const item = `
                                <div class="suggestion-item" data-name="${name}">
                                    <img src="${data.image_urls[index]}" width="40"/>
                                    ${name}
                                </div>
                            `;

                            suggestions.append(item);
                        });

                        suggestions.show();

                    } else {
                        suggestions.hide();
                    }
                },

                error: function () {
                    console.error("Error al obtener sugerencias");
                }
            });
        });


        // =========================
        // CLICK SUGERENCIAS
        // =========================
        $(document).on("click", ".suggestion-item", function () {

            const selectedName = $(this).data("name");

            $("#form1").val(selectedName);

            $("#suggestions").hide();
        });


        // =========================
        // CLICK FUERA
        // =========================
        $(document).click(function (e) {

            if (!$(e.target).closest("#cont_buscador").length) {
                $("#suggestions").hide();
            }
        });

    });

});
