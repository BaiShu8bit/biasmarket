let allAlbums = [];
let currentPage = 1;
let itemsPerPage = 10;

function updateItemsPerPage() {

  const width = window.innerWidth;

  if (width <= 575.98) {

    itemsPerPage = 4;

  } else if (width <= 1199.98) {

    itemsPerPage = 9;

  } else {

    itemsPerPage = 10;
  }
}

let currentGroupName = "";

document.addEventListener("DOMContentLoaded", () => {

  const params = new URLSearchParams(window.location.search);
  const groupId = params.get("id");

  if (!groupId) {
    console.error("No groupId");
    return;
  }

  updateItemsPerPage();
  cargarGrupo(groupId);
});

function cargarGrupo(groupId) {

  fetch(`${API_URL}/api/groups/${groupId}`)
    .then((res) => {

      if (!res.ok) {
        throw new Error("Error groups API");
      }

      return res.json();
    })

    .then((groupData) => {

      console.log("GRUPO:", groupData);

      currentGroupName = groupData.name;

      const nombre = document.getElementById("nombre_grupo");

      if (nombre) {
        nombre.textContent = currentGroupName;
      }

      // 🔥 BREADCRUMB
      renderBreadcrumb(currentGroupName, groupId);

      return fetch(
        `${API_URL}/api/albums?groupId=${groupId}`
      );
    })

    .then((res) => {

      if (!res.ok) {
        throw new Error("Error albums API");
      }

      return res.json();
    })

    .then((albumData) => {

      console.log("ALBUMS RESPONSE COMPLETA:", albumData);

      if (!Array.isArray(albumData)) {
        console.error("Formato incorrecto:", albumData);
        return;
      }

      allAlbums = albumData;

      renderPage();
      setupPagination();
    })

    .catch((err) => {

      console.error("ERROR EN CADENA:", err);

    });
}

function renderAlbums(albums) {

  const contenedor = document.getElementById("albums_container");

  if (!contenedor) {
    console.error("No existe albums_container");
    return;
  }

  contenedor.innerHTML = "";

  if (!albums.length) {

    contenedor.innerHTML = "<p>No hay álbumes</p>";
    return;
  }

  albums.forEach((album) => {

    // CARD
    const card = document.createElement("div");
    card.classList.add("card-base", "card-small");

    // LINK
    const link = document.createElement("a");
    link.href = `../PHOTOCARDS/singles.php?album_id=${album.id}`;

    // IMG
    const img = document.createElement("img");

    img.src =
      album.image_url ||
      "https://via.placeholder.com/300x300";

    img.alt = album.title;

    // TITLE
    const title = document.createElement("h3");
    title.textContent = album.title;

    // APPEND
    link.appendChild(img);

    card.appendChild(link);
    card.appendChild(title);

    contenedor.appendChild(card);
  });
}

function renderPage() {

  const start = (currentPage - 1) * itemsPerPage;
  const end = start + itemsPerPage;

  const pageItems = allAlbums.slice(start, end);

  renderAlbums(pageItems);

  updatePaginationInfo();
}

function setupPagination() {

  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");

  if (!prevBtn || !nextBtn) return;

  prevBtn.onclick = () => {

    if (currentPage > 1) {

      currentPage--;

      renderPage();
    }
  };

  nextBtn.onclick = () => {

    const totalPages = Math.ceil(
      allAlbums.length / itemsPerPage
    );

    if (currentPage < totalPages) {

      currentPage++;

      renderPage();
    }
  };
}

function updatePaginationInfo() {

  const pageInfo = document.getElementById("pageInfo");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");

  if (!pageInfo || !prevBtn || !nextBtn) return;

  const totalPages = Math.ceil(
    allAlbums.length / itemsPerPage
  );

  pageInfo.textContent =
    `Página ${currentPage} de ${totalPages}`;

  prevBtn.disabled = currentPage === 1;
  nextBtn.disabled = currentPage === totalPages;
}

function renderBreadcrumb(groupName, groupId) {

  const breadcrumb = document.getElementById("breadcrumb");

  if (!breadcrumb) return;

  breadcrumb.innerHTML = `
  
    <li class="breadcrumb-item">
      <a href="/biasmarket/HTML/INDEX/index.php">
        🏠
      </a>
    </li>

    <li class="breadcrumb-item">
      <a href="/biasmarket/HTML/GRUPO/index.php">
        Grupos
      </a>
    </li>

    <li class="breadcrumb-item active">
      ${groupName}
    </li>

  `;
}

window.addEventListener("resize", () => {

  const oldItems = itemsPerPage;

  updateItemsPerPage();

  // si cambia, reseteamos paginación
  if (oldItems !== itemsPerPage) {

    currentPage = 1;

    renderPage();
  }
});