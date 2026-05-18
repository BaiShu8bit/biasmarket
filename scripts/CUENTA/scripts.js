document.addEventListener("DOMContentLoaded", () => {

    const clienteId = localStorage.getItem("clienteId");

    if (!clienteId) {
        location.href = "../INDEX/index.php";
        return;
    }

    // =========================
    // SIDEBAR
    // =========================
    document.querySelectorAll(".sidebar-btn").forEach((btn) => {

        btn.addEventListener("click", () => {

            document.querySelectorAll(".sidebar-btn")
                .forEach(b => b.classList.remove("active"));

            btn.classList.add("active");

            const target = btn.dataset.target;

            document.querySelectorAll(".perfil-detalles > div")
                .forEach(div => div.classList.remove("seccion-activa"));

            const section = document.querySelector(".seccion-" + target);

            if (section) {
                section.classList.add("seccion-activa");
            }
        });
    });

    // =========================
    // CARGAR CUENTA
    // =========================
    obtenerCuenta(clienteId, renderCuenta);
    obtenerDireccionPrincipal(renderDireccionPrincipal);

    // =========================
    // MODAL
    // =========================
    const modal = document.getElementById("direccionModal");
    const form = document.getElementById("direccionForm");
    const closeModal = document.getElementById("closeModal");
    const btnNueva = document.getElementById("btn_nueva_direccion");

    // ABRIR MODAL
    if (btnNueva) {
        btnNueva.addEventListener("click", () => {

            modal.style.display = "flex";

            form.reset();
            document.getElementById("direccionId").value = "";

        });
    }

    // CERRAR MODAL
    if (closeModal) {
        closeModal.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    // cerrar clic fuera
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // =========================
    // GUARDAR DIRECCIÓN
    // =========================
    if (form) {

        form.addEventListener("submit", function (e) {

            e.preventDefault();

            const data = {
                nombre: document.getElementById("modal_nombre").value,
                linea2: document.getElementById("modal_linea2").value,
                calle: document.getElementById("modal_calle").value,
                codpostal: document.getElementById("modal_codpostal").value,
                localidad: document.getElementById("modal_localidad").value,
                pais: document.getElementById("modal_pais").value
            };

            guardarDireccion(data, function (res) {

                console.log("RESPUESTA:", res);

                if (res.status === "success") {

                    alert("Dirección guardada correctamente");

                    modal.style.display = "none";

                    form.reset();

                    location.reload();

                } else {
                    alert(res.message);
                }

            });

        });
    }

});


// =========================
// RENDER CUENTA
// =========================
function renderCuenta(u) {

    if (!u) return;

    document.getElementById("nombre_usuario1").textContent =
        u.nombre_usuario || "";

    document.getElementById("nombre").innerHTML =
        `<strong>NOMBRE:</strong> ${u.nombre || ""} ${u.apellido1 || ""}`;

    document.getElementById("email").innerHTML =
        `<strong>EMAIL:</strong> ${u.email || ""}`;

    document.getElementById("fechaRe").innerHTML =
        `<strong>FECHA ALTA:</strong> ${u.fecha_alta || ""}`;

    document.getElementById("pais").innerHTML =
        `<strong>PAÍS:</strong> ${u.pais || ""}`;
}

function renderDireccionPrincipal(d) {

    if (!d) {
        document.getElementById("linea1").textContent = "Sin dirección";
        return;
    }

    document.getElementById("linea1").textContent = d.nombre || "";
    document.getElementById("linea2").textContent = d.linea2 || "";
    document.getElementById("calle").textContent = d.calle || "";
    document.getElementById("codpostal").textContent = d.codpostal + " " + d.localidad;
    document.getElementById("pais").textContent = d.pais || "";
}