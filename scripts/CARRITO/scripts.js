document.addEventListener("DOMContentLoaded", () => {

    cargarCarrito();
});

// ======================================
// CARGAR CARRITO
// ======================================

async function cargarCarrito() {

    const carrito =
        JSON.parse(localStorage.getItem("carrito")) || [];

    const contenedor =
        document.getElementById("cartas_carrito");

    const noCartas =
        document.getElementById("no_cartas_carrito");

    const siCartas =
        document.getElementById("si_cartas_carrito");

    // ==========================
    // CARRITO VACÍO
    // ==========================

    if (carrito.length === 0) {

        noCartas.style.display = "block";

        siCartas.style.display = "none";

        return;
    }

    noCartas.style.display = "none";

    siCartas.style.display = "block";

    contenedor.innerHTML = "";

    let total = 0;

    let totalArticulos = 0;

    const vendedores = {};

    // ==========================
    // RECORRER CARRITO
    // ==========================

    for (const [index, item] of carrito.entries()) {

        // ==========================
        // API PHOTOCARD
        // ==========================

        let photocard = null;

        try {

            const response = await fetch(
                `${API_URL}/api/photocards/${item.photocardId}`
            );

            photocard = await response.json();

        } catch (error) {

            console.error(
                "Error cargando photocard:",
                error
            );
        }

        total +=
            parseFloat(item.precio) * item.cantidad;

        totalArticulos += item.cantidad;

        // vendedores únicos

        if (!vendedores[item.vendedor]) {

            vendedores[item.vendedor] = 1;

        } else {

            vendedores[item.vendedor]++;
        }

        // ==========================
        // DATOS PHOTOCARD
        // ==========================

        const nombreCarta =
            photocard?.name || "Photocard";

        const imagenCarta =
            photocard?.watermarked_image_url ||
            "https://via.placeholder.com/300";

        // ==========================
        // RENDER
        // ==========================

        contenedor.innerHTML += `

            <div
                class="card mb-3 shadow-sm"
                style="
                    border-radius:16px;
                    overflow:hidden;
                "
            >

                <div class="carrito-card">

                    <!-- IMAGEN -->

                    <div style="flex: 0 0 auto;">
                        <img
                            src="${imagenCarta}"
                            class="img-photocard"
                        />
                    </div>

                    <!-- INFO -->

                    <div style="flex: 1;">
            <div class="card-body p-0">

                <h5>${nombreCarta}</h5>

                 <p><strong>Vendedor:</strong> ${item.vendedor}</p>

                    <p><strong>Estado:</strong> ${traducirEstado(item.estado)}</p>

                    <p><strong>Precio:</strong> ${item.precio}€</p>

                </div>
            </div>

                    <!-- DERECHA -->

                    <div style="flex: 0 0 auto; text-align:center;">
                     <p>Cantidad: ${item.cantidad}</p>

            <button
        class="btn btn-danger"
        onclick="eliminarDelCarrito(${index})"
    >
        Eliminar
    </button>
</div>

                </div>

            </div>
        `;
    }

    // ==========================
    // VENDEDORES
    // ==========================

    const vendedoresDiv =
        document.getElementById("vendedores");

    vendedoresDiv.innerHTML = "";

    Object.keys(vendedores).forEach(vendedor => {

        vendedoresDiv.innerHTML += `

            <p>

                <strong>
                    ${vendedor}
                </strong>

                (${vendedores[vendedor]} artículos)

            </p>
        `;
    });

    // ==========================
    // RESUMEN
    // ==========================

    document.getElementById("numero_pedidos")
        .textContent = Object.keys(vendedores).length;

    document.getElementById("numero_articulos")
        .textContent = totalArticulos;

    document.getElementById("valor_pedido")
        .textContent = total.toFixed(2) + "€";

    const envio = 5.99;

    document.getElementById("gastos_envio")
        .textContent = envio.toFixed(2) + "€";

    document.getElementById("total")
        .textContent =
            (total + envio).toFixed(2) + "€";
}

// ======================================
// ELIMINAR
// ======================================

function eliminarDelCarrito(index) {

    let carrito =
        JSON.parse(localStorage.getItem("carrito")) || [];

    carrito.splice(index, 1);

    localStorage.setItem(
        "carrito",
        JSON.stringify(carrito)
    );

    cargarCarrito();
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

// ======================================
// PROCEDER AL PAGO
// ======================================

document.addEventListener("DOMContentLoaded", () => {

    const botonProceder =
        document.getElementById("proceder_pago");

    const botonPagar =
        document.getElementById("pagar");

    const overlay =
        document.getElementById("overlay");

    const tarjeta =
        document.getElementById("tarjeta");

    // ==========================
    // ABRIR PAGO
    // ==========================

    botonProceder.addEventListener("click", () => {

        overlay.style.display = "flex";

        tarjeta.style.display = "block";

        botonProceder.style.display = "none";

        botonPagar.style.display = "block";
    });

    // ==========================
    // CERRAR CLICK FUERA
    // ==========================

    overlay.addEventListener("click", (e) => {

        if (e.target === overlay) {

            overlay.style.display = "none";

            tarjeta.style.display = "none";

            botonProceder.style.display = "block";

            botonPagar.style.display = "none";
        }
    });
});

// ======================
// FORMULARIO PAGO
// ======================

document
    .getElementById("payment-form")
    .addEventListener("submit", validarFormulario);

async function validarFormulario(e) {

    e.preventDefault();

    let valido = true;

    // ======================
    // INPUTS
    // ======================

    let cardNumber =
        document.getElementById("input_card_number");

    let cardHolder =
        document.getElementById("input_card_holder");

    let cardExpiry =
        document.getElementById("input_card_expiry");

    let cvv =
        document.getElementById("input_cvv");

    // ======================
    // MENSAJES
    // ======================

    let mensajeCardNumber =
        document.getElementById("mensaje_input_card_number");

    let mensajeCardHolder =
        document.getElementById("mensaje_input_card_holder");

    let mensajeCardExpiry =
        document.getElementById("mensaje_input_card_expiry");

    let mensajeCvv =
        document.getElementById("mensaje_input_cvv");

    // ======================
    // LIMPIAR MENSAJES
    // ======================

    mensajeCardNumber.textContent = "";
    mensajeCardHolder.textContent = "";
    mensajeCardExpiry.textContent = "";
    mensajeCvv.textContent = "";

    // ======================
    // NUMERO TARJETA
    // ======================

    let numeroSinEspacios =
        cardNumber.value.replace(/\s/g, "");

    if (!/^\d{16}$/.test(numeroSinEspacios)) {

        mensajeCardNumber.textContent =
            "La tarjeta debe tener 16 números";

        valido = false;
    }

    // ======================
    // TITULAR
    // ======================

    if (cardHolder.value.trim().length < 3) {

        mensajeCardHolder.textContent =
            "Introduce un nombre válido";

        valido = false;
    }

    // ======================
    // FECHA
    // ======================

    if (
        !/^(0[1-9]|1[0-2])\/\d{2}$/
            .test(cardExpiry.value)
    ) {

        mensajeCardExpiry.textContent =
            "Formato inválido (MM/AA)";

        valido = false;
    }

    // ======================
    // CVV
    // ======================

    if (!/^\d{3}$/.test(cvv.value)) {

        mensajeCvv.textContent =
            "El CVV debe tener 3 números";

        valido = false;
    }

    // ======================
    // SI HAY ERRORES
    // ======================

    if (!valido) {

        return;
    }

    // ======================
    // PAGAR
    // ======================

    const carrito =
        JSON.parse(
            localStorage.getItem("carrito")
        ) || [];

    if (carrito.length === 0) {

        alert("Carrito vacío");

        return;
    }

    try {

        const response =
            await fetch(
                "../COMPRAR/procesar_compra.php",
                {
                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/json"
                    },

                    body: JSON.stringify({
                        carrito: carrito
                    })
                }
            );

        const data =
            await response.json();

        if (data.success) {

            alert("Compra realizada");

            localStorage.removeItem(
                "carrito"
            );

            window.location.href =
                "../INDEX/index.php";

        } else {

            alert(data.message);
        }

    } catch (error) {

        console.error(error);

        alert(
            "Error realizando compra"
        );
    }
}