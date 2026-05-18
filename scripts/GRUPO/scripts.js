let gruposGlobal = [];
let paginaActual = 1;
const porPagina = 5;

document.addEventListener("DOMContentLoaded", () => {
    cargarGrupos();
    renderBreadcrumb();
});

function cargarGrupos() {

    fetch("http://localhost:3000/api/groups")
        .then(res => res.json())
        .then(data => {

            gruposGlobal = data.sort((a, b) =>
                a.name.localeCompare(b.name)
            );

            renderPagina();
            renderPaginacion();

        })
        .catch(err => console.error(err));
}

function renderGrupos(grupos) {

    const lista = document.getElementById("grupo_list");

    if (!lista) return;
    lista.innerHTML = "";

    grupos.forEach(grupo => {

        const li = document.createElement("li");

        // imagen
        const img = document.createElement("img");
        img.src = grupo.image_url;
        img.classList.add("group-img");

        // nombre
        const name = document.createElement("div");
        name.classList.add("group-name");
        name.textContent = grupo.name;

        // albums
        const albums = document.createElement("div");
        albums.classList.add("group-albums");
        albums.textContent = `${grupo.album_count} albums`;

        // debut
        const debut = document.createElement("div");
        debut.classList.add("group-debut");
        debut.textContent = new Date(grupo.debut_date).getFullYear();

        li.appendChild(img);
        li.appendChild(name);
        li.appendChild(albums);
        li.appendChild(debut);

        li.addEventListener("click", () => {
            window.location.href = `../ALBUM/index.php?id=${grupo.id}`;
        });

        lista.appendChild(li);
    });
}

function renderPagina() {

    const lista = document.getElementById("grupo_list");
    if (!lista) return;

    lista.innerHTML = "";

    const inicio = (paginaActual - 1) * porPagina;
    const fin = inicio + porPagina;

    const gruposPagina = gruposGlobal.slice(inicio, fin);

    gruposPagina.forEach(grupo => {

        const li = document.createElement("li");

        const img = document.createElement("img");
        img.src = grupo.image_url;
        img.classList.add("group-img");

        const name = document.createElement("div");
        name.classList.add("group-name");
        name.textContent = grupo.name;

        const albums = document.createElement("div");
        albums.classList.add("group-albums");
        albums.textContent = `${grupo.album_count} albums`;

        const debut = document.createElement("div");
        debut.classList.add("group-debut");
        debut.textContent = new Date(grupo.debut_date).getFullYear();

        li.appendChild(img);
        li.appendChild(name);
        li.appendChild(albums);
        li.appendChild(debut);

        li.addEventListener("click", () => {
            window.location.href = `../ALBUM/index.php?id=${grupo.id}`;
        });

        lista.appendChild(li);
    });
}

function renderPaginacion() {

    const pageInfo = document.getElementById("pageInfo");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    if (!pageInfo || !prevBtn || !nextBtn) return;

    const totalPaginas = Math.ceil(gruposGlobal.length / porPagina);

    pageInfo.textContent = `Página ${paginaActual} de ${totalPaginas}`;

    prevBtn.onclick = () => {
        if (paginaActual > 1) {
            paginaActual--;
            renderPagina();
            renderPaginacion();
        }
    };

    nextBtn.onclick = () => {
        if (paginaActual < totalPaginas) {
            paginaActual++;
            renderPagina();
            renderPaginacion();
        }
    };
}

function renderBreadcrumb() {

    const breadcrumb = document.getElementById("breadcrumb");

    if (!breadcrumb) return;

    breadcrumb.innerHTML = `
        <li class="breadcrumb-item">
            <a href="/biasmarket/HTML/INDEX/index.php">
                🏠
            </a>
        </li>

        <li class="breadcrumb-item active">
            Grupos
        </li>
    `;
}