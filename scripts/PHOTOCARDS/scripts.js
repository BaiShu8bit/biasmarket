console.log("scripts.js cargado");

const API_URL =
  window.location.hostname === "localhost"
    ? "http://localhost:3000"
    : `http://${window.location.hostname}:3000`;

document.addEventListener("DOMContentLoaded", () => {

    const params = new URLSearchParams(window.location.search);

    const photocardId = params.get("id");

    if (!photocardId) {

        console.error("No hay ID en la URL");

        return;
    }

    cargarFicha(photocardId);

    iniciarFormulario(photocardId);

    iniciarTabla(photocardId);
    
});



/*
============================================
CARGAR PHOTOCARD
============================================
*/

function cargarFicha(id) {

    fetch(`${API_URL}/api/photocards/${id}`)

        .then(res => res.json())

        .then(card => {

            console.log("PHOTOCARD:", card);

            /*
            ============================
            IMÁGENES
            ============================
            */

            const imagenes = [
                "imagen_ficha_carta",
                "imagen_ficha_carta2",
                "imagen_ficha_carta3"
            ];

            imagenes.forEach(idImagen => {

                const img = document.getElementById(idImagen);

                if (img) {

                    img.src = card.watermarked_image_url || "";
                }
            });

            /*
            ============================
            INFO
            ============================
            */

            const nombreCarta = document.getElementById("nombre_carta");

            if (nombreCarta) {

                nombreCarta.textContent = card.name || "";
            }

            const grupo = document.getElementById("grupo_carta");

            if (grupo) {

                grupo.textContent = card.group?.name || "";
            }

            const miembro = document.getElementById("miembro_carta");

            if (miembro) {

                miembro.textContent = card.member?.stage_name || "";
            }

            const album = document.getElementById("album_carta");

            if (album) {

                album.textContent = card.album?.title || "";
            }

            /*
            ============================
            LINKS
            ============================
            */

            const grupoLink = document.getElementById("link_grupo");

            if (grupoLink && card.group) {

                grupoLink.href =
                    `../ALBUM/index.php?id=${card.group.id}`;
            }

            const albumLink = document.getElementById("link_album");

            if (albumLink && card.album) {

                albumLink.href =
                    `../PHOTOCARDS/singles.php?album_id=${card.album.id}`;
            }

            renderBreadcrumb(
                card.group?.name || "",
                card.album?.title || "",
                card.group?.id || "",
                card.album?.id || "",
                card.member?.stage_name || ""
            );

        })

        .catch(err => {

            console.error("Error cargando ficha:", err);
        });
        
}



/*
============================================
FORMULARIO PUBLICACIÓN
============================================
*/

function iniciarFormulario(photocardId) {

    const formulario = document.getElementById("form_publicacion");

    if (!formulario) return;

    // EVITAR DOBLE EVENTO
    if (formulario.dataset.listenerAdded) return;

    formulario.dataset.listenerAdded = "true";

    formulario.addEventListener("submit", function (e) {

        e.preventDefault();

        const formData = new FormData(formulario);

        /*
        ============================
        DATOS PUBLICACIÓN
        ============================
        */

        formData.append(
            "photocard_id",
            photocardId
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

        /*
        ============================
        DATOS PHOTOCARD
        ============================
        */

        formData.append(
            "nombre_carta",
            document.getElementById("nombre_carta").textContent
        );

        /*
        ============================
        ENVIAR
        ============================
        */

        fetch("/biasmarket/funciones/PHOTOCARDS/formulario_publicacion.php", {

            method: "POST",

            body: formData
        })

        .then(res => res.json())

        .then(data => {

            console.log("RESPUESTA:", data);

            if (data.success) {

                alert("¡Photocard puesta en venta correctamente!");

                formulario.reset();

                location.reload();
                

                cargar();

            } else {

                alert(data.message);
            }

        })

        .catch(err => {

            console.error(err);

            alert("Error del servidor");
        });
    });
}



/*
============================================
TABLA PUBLICACIONES
============================================
*/

let photocardIdGlobal = null;
let ordenCampo = "precioCarta";
let ordenDir = "ASC";

function iniciarTabla(photocardId) {

    photocardIdGlobal = photocardId;

    cargar();

    // filtro instantáneo
    $("#filtroSelect").on("change", function () {
        cargar();
    });

    // click en precio para ordenar
    $("#tabla_publicaciones th[data-sort]").on("click", function () {

        const campo = $(this).data("sort");

        if (ordenCampo === campo) {
            ordenDir = (ordenDir === "ASC") ? "DESC" : "ASC";
        } else {
            ordenCampo = campo;
            ordenDir = "ASC";
        }

        cargar();
    });
}

function cargar() {

    $.ajax({
        url: "./insertar_publicaciones/server_processing.php",
        type: "POST",
        dataType: "json",
        data: {
            photocard_id: photocardIdGlobal,
            filter_option: $("#filtroSelect").val(),
            order_field: ordenCampo,
            order_dir: ordenDir
        },
        success: function (data) {

            let html = "";

            if (data.length === 0) {
                html = `
                    <tr>
                        <td colspan="6" class="text-center">
                            Ninguna carta disponible en este momento
                        </td>
                    </tr>
                `;
            } else {

                data.forEach(p => {

                    html += `
                        <tr>
                            <td>${p.nombre_usuario}</td>
                            <td>${p.estadoCarta}</td>
                            <td>${p.observacionesCarta ?? ""}</td>
                            <td>${parseFloat(p.precioCarta).toFixed(2)}€</td>
                            <td>${p.cantidadCarta}</td>
                            <td>
                                <button
                                    class="cart-btn boton_carrito"
                                    data-publicacion-id="${p.publicacionId}"
                                    data-photocard-id="${p.photocardId}"
                                    data-vendedor="${p.nombre_usuario}"
                                    data-estado="${p.estadoCarta}"
                                    data-observaciones="${p.observacionesCarta}"
                                    data-precio="${p.precioCarta}"
                                    data-cantidad="1">
                                    🛒
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            $("#tabla_publicaciones tbody")
                .empty()
                .append(html);
        }
    });
}
// ==========================
// CARRITO
// ==========================

document.addEventListener("click", function (e) {

    const boton = e.target.closest(".boton_carrito");
    if (!boton) return;

    const fila = boton.closest("tr");
    const selectCantidad = fila.querySelector(".cantidad-select");

    const cantidadSeleccionada = selectCantidad
        ? parseInt(selectCantidad.value)
        : 1;

    const producto = {
        publicacionId: boton.dataset.publicacionId,
        photocardId: boton.dataset.photocardId,
        nombreCarta: boton.dataset.nombreCarta,
        imagenCarta: boton.dataset.imagenCarta,
        vendedor: boton.dataset.vendedor,
        estado: boton.dataset.estado,
        observaciones: boton.dataset.observaciones,
        precio: boton.dataset.precio,
        cantidad: cantidadSeleccionada
    };

    let carrito =
        JSON.parse(localStorage.getItem("carrito")) || [];

    const existe = carrito.find(item =>
        item.publicacionId === producto.publicacionId
    );

    if (existe) {

        existe.cantidad += cantidadSeleccionada;

    } else {

        carrito.push(producto);
    }

    localStorage.setItem(
        "carrito",
        JSON.stringify(carrito)
    );

    console.log("CARRITO:", carrito);

    alert("Photocard añadida al carrito");
});

// ==========================
// EDITAR PUBLICACIÓN
// ==========================
document.addEventListener("click", function (e) {

    const btn = e.target.closest(".btn-editar");
    if (!btn) return;

    document.getElementById("id_editar").value = btn.dataset.id;
    document.getElementById("estado_editar").value = btn.dataset.estado;
    document.getElementById("observacion_editar").value = btn.dataset.observacion;
    document.getElementById("precio_editar").value = btn.dataset.precio;
    document.getElementById("cantidad_editar").value = btn.dataset.cantidad;
});

document.getElementById("editar_publicacion")
.addEventListener("submit", function (e) {

    e.preventDefault();

    const formData = new FormData(this);

    fetch("/biasmarket/funciones/PHOTOCARDS/editar_publicacion.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {

            alert("Publicación actualizada correctamente");

            location.reload();

        } else {

            alert(data.message);
        }

    })
    .catch(err => {

        console.error(err);
        alert("Error del servidor");
    });
});

// ==========================
// ELIMINAR PUBLICACIÓN
// ==========================

document.getElementById("btn_eliminar_publicacion")
.addEventListener("click", function () {

    const id = document.getElementById("id_editar").value;

    if (!id) {
        alert("No se ha encontrado la publicación");
        return;
    }

    fetch("/biasmarket/funciones/PHOTOCARDS/eliminar_publicacion.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {

            alert("Publicación eliminada correctamente");

            location.reload();

        } else {

            alert(data.message);
        }

    })
    .catch(err => {

        console.error(err);
        alert("Error del servidor");
    });
});

// ==========================
// BREADCRUMB
// ==========================

function renderBreadcrumb(groupName, albumTitle, groupId, albumId, memberName) {
  const breadcrumb = document.getElementById("breadcrumb");

  breadcrumb.innerHTML = `
    <li class="breadcrumb-item">
      <a href="/biasmarket/HTML/INDEX/index.php">🏠</a>
    </li>

    <li class="breadcrumb-item">
      <a href="/biasmarket/HTML/GRUPO/index.php">Grupos</a>
    </li>

    <li class="breadcrumb-item">
      <a href="/biasmarket/HTML/ALBUM/index.php?id=${groupId}">
        ${groupName}
      </a>
    </li>

    <li class="breadcrumb-item">
    <a href="/biasmarket/HTML/PHOTOCARDS/singles.php?album_id=${albumId}">
      ${albumTitle}
    </a>
    </li>
    
    <li class="breadcrumb-item active">
      ${memberName}
    </li>
  `;
}


// ======================================
// TRADUCIR ESTADO
// ======================================

function traducirEstado(estado) {

    switch (estado) {

        case "mint":
            return `<span class="badge-estado mint">M</span>`;

        case "near_mint":
            return `<span class="badge-estado near-mint">NM</span>`;

        case "excellent":
            return `<span class="badge-estado excellent">EX</span>`;

        case "good":
            return `<span class="badge-estado good">GD</span>`;

        case "poor":
            return `<span class="badge-estado poor">PO</span>`;

        default:
            return `<span class="badge-estado">${estado}</span>`;
    }
}