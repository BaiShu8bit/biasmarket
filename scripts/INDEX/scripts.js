const API_URL =
  window.location.hostname === "localhost"
    ? "http://localhost:3000"
    : `http://${window.location.hostname}:3000`;

const BASE_URL = "/biasmarket";

document.addEventListener("DOMContentLoaded", function () {
  cargarPhotocards();
  cargarAlbums();
  cargarGrupos();

  // ALERTA (lo dejamos igual)
  if (sessionStorage.getItem("showAlert") === "true") {
    setTimeout(function () {
      document.getElementById("alerta").innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="position: fixed; top: 17%; left: 50%; transform: translateX(-50%);
                z-index: 9999; width: 50%;">
                <strong>¡Éxito!</strong> Tu publicación se ha subido correctamente.
            </div>
            `;

      sessionStorage.removeItem("showAlert");
    }, 500);

    setTimeout(function () {
      document.getElementById("alerta").innerHTML = "";
    }, 3000);
  }
});

// ----------------------- //
// PHOTOCARDS
// ----------------------- //

function cargarPhotocards() {
  const contenedor = document.getElementById("imagenes");

  if (!contenedor) {
    console.error("No existe el contenedor #imagenes");
    return;
  }

  fetch(`${API_URL}/api/photocards`)
    .then((response) => response.json())
    .then((data) => {
      if (!Array.isArray(data)) {
        console.error("Respuesta inválida de la API:", data);
        return;
      }

      contenedor.innerHTML = "";

      // Mezclar aleatorio
      const aleatorias = data.sort(() => Math.random() - 0.5);

      // Solo 8
      const seleccion = aleatorias.slice(0, 4);

      seleccion.forEach((card) => {
        // 🔥 CONTENEDOR PRINCIPAL
        const container = document.createElement("div");
        container.classList.add("card-base", "card-large");

        const link = document.createElement("a");
        link.href = `../PHOTOCARDS/index.php?id=${card.id}`;

        const img = document.createElement("img");
        img.src = card.watermarked_image_url;
        img.classList.add("imagen");

        link.appendChild(img);
        container.appendChild(link);

        // 🔥 CONTENEDOR TEXTO
        const textContainer = document.createElement("div");
        textContainer.classList.add("text-container");

        // 🔥 SEPARAR TEXTO
        const partes = card.name.split(" - ");

        const grupo = partes[0] || "";
        const miembro = partes[1] || "";
        const descripcion = partes[2] || "";

        // 🔥 ELEMENTOS TEXTO
        const title = document.createElement("h4");
        title.textContent = grupo;
        title.classList.add("promo-title");

        const member = document.createElement("p");
        member.textContent = miembro;
        member.classList.add("promo-description");

        const description = document.createElement("p");
        description.textContent = descripcion;
        description.classList.add("promo-description");

        // 🔥 MONTAR
        textContainer.appendChild(title);
        textContainer.appendChild(member);
        textContainer.appendChild(description);

        container.appendChild(textContainer);

        contenedor.appendChild(container);
      });
    })
    .catch((error) => {
      console.error("Error cargando photocards:", error);
    });
}

// ----------------------- //
// ALBUMES
// ----------------------- //
function cargarAlbums() {
  const contenedorDestacado = document.getElementById("album_destacado");
  const lista = document.getElementById("album_list");

  if (!contenedorDestacado || !lista) {
    console.error("No existen los contenedores de álbumes");
    return;
  }

  fetch(`${API_URL}/api/albums`)
    .then((res) => res.json())
    .then((data) => {
      if (!Array.isArray(data)) return;

      const aleatorios = data.sort(() => Math.random() - 0.5);

      const principal = aleatorios[0];
      const otros = aleatorios.slice(1, 5);

      // =========================
      // ⭐ ÁLBUM DESTACADO
      // =========================

      const card = document.createElement("div");
      card.className = "card-base card-small";

      const link = document.createElement("a");
      link.href = `../PHOTOCARDS/singles.php?album_id=${principal.id}`;

      const img = document.createElement("img");
      img.src = principal.image_url;

      const title = document.createElement("h3");
      title.textContent = principal.group.name;

      const subtitle = document.createElement("p");
      subtitle.textContent = principal.title;

      link.appendChild(img);
      card.appendChild(link);
      card.appendChild(title);
      card.appendChild(subtitle);

      contenedorDestacado.innerHTML = "";
      contenedorDestacado.appendChild(card);

      // =========================
      // 📋 LISTA CON ICONO + PREVIEW
      // =========================

      lista.innerHTML = "";

      otros.forEach((album) => {
        const li = document.createElement("li");

        // fila interna (flex)
        const row = document.createElement("div");
        row.classList.add("list-row");

        // título del álbum (hacerlo clickeable)
        const text = document.createElement("span");
        const textLink = document.createElement("a");
        textLink.href = `../PHOTOCARDS/singles.php?album_id=${album.id}`;
        textLink.textContent = album.title;
        textLink.style.color = "inherit";
        textLink.style.textDecoration = "none";
        text.appendChild(textLink);

        // wrapper icono + imagen (CLAVE)
        const wrapper = document.createElement("span");
        wrapper.classList.add("preview-wrapper");

        const icon = document.createElement("span");
        icon.classList.add("preview-icon");
        icon.textContent = "📷";

        const img = document.createElement("img");
        img.classList.add("preview-image");
        img.src = album.image_url;

        // montar estructura
        wrapper.appendChild(icon);
        wrapper.appendChild(img);

        row.appendChild(text);
        row.appendChild(wrapper);

        li.appendChild(row);
        lista.appendChild(li);
      });
    })
    .catch((err) => {
      console.error("Error cargando albums:", err);
    });
}

// ----------------------- //
// GRUPOS
// ----------------------- //
function cargarGrupos() {
  const contenedorDestacado = document.getElementById("grupo_destacado");
  const lista = document.getElementById("grupo_list");

  if (!contenedorDestacado || !lista) {
    console.error("No existen los contenedores de grupos");
    return;
  }

  fetch(`${API_URL}/api/groups`)
    .then((res) => res.json())
    .then((data) => {
      if (!Array.isArray(data)) return;

      const aleatorios = data.sort(() => Math.random() - 0.5);

      const principal = aleatorios[0];
      const otros = aleatorios.slice(1, 5);

      // =========================
      // ⭐ GRUPO DESTACADO
      // =========================

      const card = document.createElement("div");
      card.className = "card-base card-small";

      const link = document.createElement("a");
      link.href = `../ALBUM/index.php?id=${principal.id}`;

      const img = document.createElement("img");
      img.src = principal.image_url;

      const title = document.createElement("h3");
      title.textContent = principal.name;

      const fandom = document.createElement("p");
      fandom.textContent = principal.fandom_name;

      link.appendChild(img);
      card.appendChild(link);
      card.appendChild(title);
      card.appendChild(fandom);

      contenedorDestacado.innerHTML = "";
      contenedorDestacado.appendChild(card);

      // =========================
      // 📋 LISTA
      // =========================

      lista.innerHTML = "";

      otros.forEach((grupo) => {
        const li = document.createElement("li");

        // fila interna (flex)
        const row = document.createElement("div");
        row.classList.add("list-row");

        // nombre grupo (hacerlo clickeable)
        const text = document.createElement("span");
        const textLink = document.createElement("a");
        textLink.href = `../ALBUM/index.php?id=${grupo.id}`;
        textLink.textContent = grupo.name;
        textLink.style.color = "inherit";
        textLink.style.textDecoration = "none";
        text.appendChild(textLink);

        // wrapper icono + imagen (CLAVE)
        const wrapper = document.createElement("span");
        wrapper.classList.add("preview-wrapper");

        const icon = document.createElement("span");
        icon.classList.add("preview-icon");
        icon.textContent = "📷";

        const img = document.createElement("img");
        img.classList.add("preview-image");
        img.src = grupo.image_url;

        // montar estructura
        wrapper.appendChild(icon);
        wrapper.appendChild(img);

        row.appendChild(text);
        row.appendChild(wrapper);

        li.appendChild(row);
        lista.appendChild(li);
      });
    })
    .catch((err) => {
      console.error("Error cargando grupos:", err);
    });
}


// =========================
// 🛞 CAROUSEL
// =========================
const items = document.querySelectorAll(".slider-item");
const dots = document.querySelectorAll(".dot");
const background = document.getElementById("carousel-background");
const carouselContainer = document.getElementById("Carousel-slider");

let currentIndex = 0;

function updateCarousel() {

  dots.forEach((dot) => {
    dot.classList.remove("active");
  });

  if (dots[currentIndex]) {
    dots[currentIndex].classList.add("active");
  }

  // Update background using data attribute
  if (carouselContainer) {
    carouselContainer.setAttribute("data-slide", currentIndex);
  }

  items.forEach((item, index) => {

    item.classList.remove("active");

    let position =
      (index - currentIndex + items.length) % items.length;

        // convertir para tener negativos
        if (position > items.length / 2) {
            position -= items.length;
        }

        /* CENTRO */
        if (position === 0) {

            item.classList.add("active");

            item.style.transform =
                "translateX(0px) scale(1)";

            item.style.opacity = "1";

            item.style.zIndex = "10";
        }

        /* IZQUIERDA */
        else if (position === -1) {

            item.style.transform =
                "translateX(-130px) scale(0.88) rotateY(25deg)";

            item.style.opacity = "0.7";

            item.style.zIndex = "5";
        }

        /* DERECHA */
        else if (position === 1) {

            item.style.transform =
                "translateX(130px) scale(0.88) rotateY(-25deg)";

            item.style.opacity = "0.7";

            item.style.zIndex = "5";
        }

        /* RESTO */
        else {

            item.style.transform =
                "translateX(0px) scale(0)";

            item.style.opacity = "0";

            item.style.zIndex = "0";
        }
    });
}

function nextSlide() {

    currentIndex =
        (currentIndex + 1) % items.length;

    updateCarousel();
}

updateCarousel();

setInterval(nextSlide, 2500);

// =========================
// CONTROL MANUAL
// =========================

function prevSlide() {

    currentIndex =
        (currentIndex - 1 + items.length)
        % items.length;

    updateCarousel();
}

// click izquierda/derecha
carouselContainer.addEventListener("click", (e) => {

    // evitar conflicto con click del álbum
    if (e.target.classList.contains("slider-item")) {
        return;
    }

    const mitad =
        window.innerWidth / 2;

    if (e.clientX < mitad) {

        prevSlide();

    } else {

        nextSlide();
    }
});

// botones
document.getElementById("prev-slide").addEventListener("click", prevSlide);
document.getElementById("next-slide").addEventListener("click", nextSlide);

// =========================
// CLICK SLIDE
// =========================

items.forEach(item => {

    item.addEventListener("click", () => {

        const albumId =
            item.dataset.albumId;

        if (!albumId) return;

        window.location.href =
            `${BASE_URL}/HTML/PHOTOCARDS/singles.php?album_id=${albumId}`;
    });
});