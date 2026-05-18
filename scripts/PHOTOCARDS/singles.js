let allPhotocards = [];
let currentPage = 1;
let itemsPerPage = 8; // ❗ IMPORTANTE: LET, no const

function updateItemsPerPage() {

  const width = window.innerWidth;

  if (width <= 575.98) {
    itemsPerPage = 3;   // smartphone
  } 
  else if (width <= 1199.98) {
    itemsPerPage = 6;   // tablet
  } 
  else {
    itemsPerPage = 8;   // desktop
  }
}

document.addEventListener("DOMContentLoaded", () => {

  updateItemsPerPage();

  const params = new URLSearchParams(window.location.search);
  const albumId = params.get("album_id");

  if (!albumId) {
    console.error("No albumId en la URL");
    return;
  }

  console.log("ALBUM ID:", albumId);

  cargarPhotocards(albumId);
});

function cargarPhotocards(albumId) {

  console.log("URL:", window.location.href);
  console.log("albumId:", albumId);
  
  const contenedor = document.getElementById("imagenes");

  if (!contenedor) {
    console.error("No existe #imagenes");
    return;
  }

  fetch(`http://localhost:3000/api/photocards/album/${albumId}`)
    .then((res) => {
      if (!res.ok) throw new Error("Error API photocards");
      return res.json();
    })
    .then((data) => {

      if (!Array.isArray(data)) {
        console.error("Formato incorrecto:", data);
        return;
      }

      allPhotocards = data;
      currentPage = 1;

      if (data.length > 0) {
        const groupName = data[0].group?.name || "Grupo";
        const albumTitle = data[0].album?.title || "Álbum";
        const groupId = data[0].group?.id || "";

        renderBreadcrumb(groupName, albumTitle, groupId);
      }

      renderPage();
      setupPagination();
    })
    .catch((err) => {
      console.error("ERROR FETCH PHOTOCARDS:", err);
      contenedor.innerHTML = "<p>Error cargando photocards</p>";
    });
}

function renderPhotocards(photocards) {

  const contenedor = document.getElementById("imagenes");
  const nombreGrupo = document.getElementById("nombre_grupo");

  contenedor.innerHTML = "";

  if (!photocards || photocards.length === 0) {
    contenedor.innerHTML = "<p>No hay photocards</p>";
    return;
  }

  if (photocards.length > 0 && photocards[0].album) {
    const albumTitle = photocards[0].album.title || "";
    nombreGrupo.textContent = albumTitle;
  }

  photocards.forEach((card) => {

    const container = document.createElement("div");
    container.classList.add("card-base", "card-large");

    const link = document.createElement("a");
    link.href = `../PHOTOCARDS/index.php?id=${card.id}`;

    const img = document.createElement("img");
    img.src =
      card.watermarked_image_url ||
      card.image_url ||
      "https://via.placeholder.com/300";

    link.appendChild(img);
    container.appendChild(link);

    if (card.name) {

      const partes = card.name.split(" - ");

      const grupo = partes[0] || "";
      const miembro = partes[1] || "";
      const descripcion = partes[2] || "";

      const title = document.createElement("h3");
      title.textContent = grupo;

      const member = document.createElement("p");
      member.textContent = miembro;

      container.appendChild(title);
      container.appendChild(member);

      if (descripcion) {
        const desc = document.createElement("p");
        desc.textContent = descripcion;
        container.appendChild(desc);
      }
    }

    contenedor.appendChild(container);
  });
}

function renderPage() {

  const start = (currentPage - 1) * itemsPerPage;
  const end = start + itemsPerPage;

  const pageItems = allPhotocards.slice(start, end);

  renderPhotocards(pageItems);
  updatePaginationInfo();
}

function setupPagination() {

  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");

  prevBtn.addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      renderPage();
    }
  });

  nextBtn.addEventListener("click", () => {
    const totalPages = Math.ceil(allPhotocards.length / itemsPerPage);

    if (currentPage < totalPages) {
      currentPage++;
      renderPage();
    }
  });
}

function updatePaginationInfo() {

  const pageInfo = document.getElementById("pageInfo");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");

  const totalPages = Math.ceil(allPhotocards.length / itemsPerPage);

  pageInfo.textContent = `Página ${currentPage} de ${totalPages}`;

  prevBtn.disabled = currentPage === 1;
  nextBtn.disabled = currentPage === totalPages;
}

function renderBreadcrumb(groupName, albumTitle, groupId) {

  const breadcrumb = document.getElementById("breadcrumb");

  if (!breadcrumb) {
    console.warn("Breadcrumb no existe en esta vista");
    return;
  }

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

    <li class="breadcrumb-item active">
      ${albumTitle}
    </li>
  `;
}

window.addEventListener("resize", () => {

  const oldItems = itemsPerPage;

  updateItemsPerPage();

  if (oldItems !== itemsPerPage) {
    currentPage = 1;
    renderPage();
  }
});