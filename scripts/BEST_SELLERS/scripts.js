let todasLasCartas = [];

let cartasMostradas = 12;

const API_URL =
  window.location.hostname === "localhost"
    ? "http://localhost:3000"
    : `http://${window.location.hostname}:3000`;

// ======================================
// LOAD
// ======================================

document.addEventListener("DOMContentLoaded", function () {

    cargarBestSellers();

    // botón ver más
    document
    .getElementById("btnVerMas")
    .addEventListener("click", mostrarMas);
});

// ======================================
// FETCH
// ======================================

function cargarBestSellers() {

    fetch(`${API_URL}/api/photocards`)

        .then((response) => response.json())

        .then((data) => {

            if (!Array.isArray(data)) {

                console.error("Respuesta inválida:", data);

                return;
            }

            // mezclar aleatorio
            todasLasCartas =
                data.sort(() => Math.random() - 0.5);

            renderCards();
        })

        .catch((error) => {

            console.error(
                "Error cargando photocards:",
                error
            );
        });
}

// ======================================
// RENDER
// ======================================

function renderCards() {

    const contenedor =
        document.getElementById("imagenes");

    contenedor.innerHTML = "";

    const seleccion =
        todasLasCartas.slice(0, cartasMostradas);

    seleccion.forEach((card) => {

        // CONTENEDOR
        const container =
            document.createElement("div");

        container.classList.add(
            "card-base",
            "card-large"
        );

        // LINK
        const link =
            document.createElement("a");

        link.href =
            `../PHOTOCARDS/index.php?id=${card.id}`;

        // IMG
        const img =
            document.createElement("img");

        img.src =
            card.watermarked_image_url;

        img.classList.add("imagen");

        link.appendChild(img);

        container.appendChild(link);

        // TEXTO
        const textContainer =
            document.createElement("div");

        textContainer.classList.add(
            "text-container"
        );

        // PARTES
        const partes =
            card.name.split(" - ");

        const grupo =
            partes[0] || "";

        const miembro =
            partes[1] || "";

        const descripcion =
            partes[2] || "";

        // TITLE
        const title =
            document.createElement("h4");

        title.textContent = grupo;

        title.classList.add("promo-title");

        // MEMBER
        const member =
            document.createElement("p");

        member.textContent = miembro;

        member.classList.add(
            "promo-description"
        );

        // DESC
        const description =
            document.createElement("p");

        description.textContent =
            descripcion;

        description.classList.add(
            "promo-description"
        );

        // APPEND
        textContainer.appendChild(title);

        textContainer.appendChild(member);

        textContainer.appendChild(description);

        container.appendChild(textContainer);

        contenedor.appendChild(container);
    });

    // ocultar botón si no quedan más
    const btn =
        document.getElementById("btnVerMas");

    if (
        cartasMostradas >=
        todasLasCartas.length
    ) {

        btn.style.display = "none";
    }
}

// ======================================
// VER MÁS
// ======================================

function mostrarMas() {

    cartasMostradas += 8;

    renderCards();
}