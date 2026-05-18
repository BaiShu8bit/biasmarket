function obtenerCartaPorId(id, callback) {

    $.ajax({
        url: "../src/data/photocards.json",
        type: "GET",
        dataType: "json",

        success: function (res) {
            callback(res);
        },

        error: function (xhr) {
            console.error("ERROR API:", xhr.responseText);
        }
    });

}

function renderFicha(carta) {

    if (!carta) return;

    // IMAGEN
    document.getElementById("imagen_ficha_carta").src =
        carta.watermarked_image_url || "";

    // DUPLICADAS (tabs)
    const img2 = document.getElementById("imagen_ficha_carta2");
    if (img2) img2.src = carta.watermarked_image_url;

    const img3 = document.getElementById("imagen_ficha_carta3");
    if (img3) img3.src = carta.watermarked_image_url;

    // INFO
    document.getElementById("tendencia_precio").textContent =
        carta.name || "";

    document.getElementById("imagen_efecto").textContent =
        `${carta.group?.name || ""} - ${carta.member?.stage_name || ""}`;

    // NUEVA INFO
    const nombreCarta = document.getElementById("nombre_carta");
    if (nombreCarta) {
        nombreCarta.textContent = carta.name || "";
    }

    const grupoCarta = document.getElementById("grupo_carta");
    if (grupoCarta) {
        grupoCarta.textContent = carta.group?.name || "";
    }

    const miembroCarta = document.getElementById("miembro_carta");
    if (miembroCarta) {
        miembroCarta.textContent =
            carta.member?.stage_name || "";
    }

    const albumCarta = document.getElementById("album_carta");
    if (albumCarta) {
        albumCarta.textContent =
            carta.album?.title || "";
    }

    // LINKS
    const linkGrupo = document.getElementById("link_grupo");

    if (linkGrupo && carta.group?.id) {
        linkGrupo.href =
            `../ALBUM/index.php?id=${carta.group.id}`;
    }

    const linkAlbum = document.getElementById("link_album");

    if (linkAlbum && carta.album?.id) {
        linkAlbum.href =
            `../PHOTOCARDS/singles.php?album_id=${carta.album.id}`;
    }

    // FORMULARIO PUBLICACIÓN
    const formPublicacion =
        document.getElementById("form_publicacion");

    if (formPublicacion) {

        formPublicacion.addEventListener("submit", function (e) {

            e.preventDefault();

            const formData = new FormData();

            formData.append(
                "photocard_id",
                carta.photocardId
            );

            formData.append(
                "form_cantidad",
                document.getElementById("form_cantidad").value
            );

            formData.append(
                "form_estado",
                document.getElementById("form_estado").value
            );

            formData.append(
                "form_observaciones",
                document.getElementById("form_observaciones").value
            );

            formData.append(
                "form_precio",
                document.getElementById("form_precio").value
            );

            fetch(
                "./insertar_publicaciones/formulario_publicacion.php",
                {
                    method: "POST",
                    body: formData
                }
            )
            .then(res => res.json())
            .then(data => {

                console.log(data);

                if (data.success) {

                    alert(data.message);

                    // RESET FORM
                    formPublicacion.reset();

                    // RECARGAR TABLA
                    if ($.fn.DataTable.isDataTable('#tabla_publicaciones')) {
                        $('#tabla_publicaciones')
                            .DataTable()
                            .ajax
                            .reload(null, false);
                    }

                } else {

                    alert(data.message || "Error");

                }

            })
            .catch(err => {

                console.error(err);

                alert("Error al publicar");

            });

        });

    }

}