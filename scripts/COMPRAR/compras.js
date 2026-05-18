document.addEventListener("DOMContentLoaded", () => {

    cargarPedidos();
});

// ======================================
// CARGAR PEDIDOS
// ======================================

async function cargarPedidos() {

    try {

        const response = await fetch(
            "../COMPRAR/obtener_pedidos.php"
        );

        const pedidos =
            await response.json();

        console.log(pedidos);

        renderPedidos(pedidos);

    } catch (error) {

        console.error(error);
    }
}

// ======================================
// RENDER
// ======================================

function renderPedidos(pedidos) {

    const tbody =
        document.getElementById("table-body");

    tbody.innerHTML = "";

    // ==========================
    // VACÍO
    // ==========================

    if (pedidos.length === 0) {

        tbody.innerHTML = `

            <tr>

                <td colspan="5">

                    No tienes pedidos

                </td>

            </tr>
        `;

        return;
    }

    // ==========================
    // FILAS
    // ==========================

pedidos.forEach(pedido => {

    tbody.innerHTML += `

        <tr data-status="${pedido.estadoPedido}">

            <td data-label="ID Pedido">
                #${pedido.pedidoId}
            </td>

            <td data-label="Fecha Compra">
                ${formatearFecha(pedido.fechaPedido)}
            </td>

            <td data-label="Vendedor">
                Pedido BiasMarket
            </td>

            <td data-label="¿Certificado?">
                Sí
            </td>

            <td data-label="Total">
                ${pedido.totalPedido}€
            </td>

            <td data-label="Data">

                 <a
                    href="../COMPRAR/generar_pdf.php?id=${pedido.pedidoId}"
                    target="_blank"
                    class="btn-pdf"
                 >
                    📄 Ver PDF
                 </a>

            </td>

        </tr>
    `;
});

    activarFiltros();
}

// ======================================
// FILTROS
// ======================================

function activarFiltros() {

    const botones =
        document.querySelectorAll(".filter-btn");

    botones.forEach(btn => {

        btn.addEventListener("click", () => {

            botones.forEach(b =>
                b.classList.remove("active")
            );

            btn.classList.add("active");

            const filtro =
                btn.dataset.filter;

            const filas =
                document.querySelectorAll(
                    "#table-body tr"
                );

            filas.forEach(fila => {

                if (
                    fila.dataset.status === filtro
                ) {

                    fila.style.display = "";

                } else {

                    fila.style.display = "none";
                }
            });
        });
    });
}

// ======================================
// FECHA
// ======================================

function formatearFecha(fecha) {

    const date = new Date(fecha);

    return date.toLocaleDateString("es-ES");
}