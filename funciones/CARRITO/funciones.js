document.addEventListener("DOMContentLoaded", function () {

    $(document).ready(function () {

        document.addEventListener('click', function (event) {

            if (event.target.matches('.card-header button')) {

                var id_usuario = localStorage.getItem("clienteId");
                var vendedor = event.target.id;

                var formData = new FormData();

                var cardBody = event.target.closest('.card-body');

                if (!cardBody) {

                    console.error('No se encontró el contenedor .card-body');
                    return;
                }

                var cartasList = cardBody.querySelector('.cartas-list');

                if (!cartasList) {

                    console.error('No se encontró el contenedor .cartas-list');
                    return;
                }

                var cartas = [];

                cartasList.querySelectorAll('.carta-item').forEach((carta) => {

                    var nombre_carta = carta.querySelector('h6').textContent.trim();

                    var cantidadTexto = carta.querySelector('p:nth-child(2)').textContent;
                    var cantidadMatch = cantidadTexto.match(/Cantidad:\s*(\d+)/);
                    var cantidad_carta = cantidadMatch ? parseInt(cantidadMatch[1], 10) : 0;

                    var estado_carta = carta.querySelector('p:nth-child(2)').textContent.split('|')[1].split(':')[1].trim();
                    var idioma_carta = carta.querySelector('p:nth-child(2)').textContent.split('|')[2].split(':')[1].trim();

                    var observacion_carta = carta.querySelector('p:nth-child(3)').textContent.split(':')[1].trim();
                    var precio_carta = carta.querySelector('.carta-precio p').textContent.replace('€', '').trim();

                    cartas.push({
                        id_usuario,
                        vendedor,
                        nombre_carta,
                        cantidad_carta,
                        estado_carta,
                        idioma_carta,
                        observacion_carta,
                        precio_carta,
                    });
                });

                formData.append('cartas', JSON.stringify(cartas));

                $.ajax({
                    type: 'POST',
                    url: '/funciones/CARRITO/quitar_articulos.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        const data = typeof response === "string" ? JSON.parse(response) : response;

                        if (data.success === "Cartas procesadas correctamente.") {

                            location.reload();

                        } else {

                            alert(data.message || "Ocurrió un error inesperado.");
                        }
                    },
                    error: function (response) {

                    }
                })
            }

            if (event.target.matches('.cartas-list button[title="Eliminar"]')) {

                const cartaItem = event.target.closest(".carta-item");

                if (cartaItem) {

                    var id_usuario = localStorage.getItem("clienteId");
                    const vendedor = event.target.getAttribute("data-vendedor");
                    const nombre_carta = cartaItem.querySelector("h6").textContent.trim();
                    const detalles = cartaItem.querySelector("p:nth-of-type(1)").textContent.trim();
                    const observacion_carta = cartaItem.querySelector("p:nth-of-type(2)").textContent.trim().replace("Observación:", "").trim();
                    const precio_carta = cartaItem.querySelector(".carta-precio p").textContent.trim().replace("€", "");

                    const [cantidadTexto, estadoTexto, idiomaTexto] = detalles.split('|').map(text => text.split(':')[1].trim());
                    const selectCantidad = cartaItem.querySelector("select");
                    const cantidad_carta = parseInt(selectCantidad.value, 10);
                    const estado_carta = estadoTexto;
                    const idioma_carta = idiomaTexto;

                    var cartaData = [];

                    cartaData.push({
                        id_usuario,
                        vendedor,
                        nombre_carta,
                        cantidad_carta,
                        estado_carta,
                        idioma_carta,
                        observacion_carta,
                        precio_carta,
                    });

                    console.log("Datos procesados de la carta:", cartaData);

                    const formData = new FormData();

                    formData.append('cartas', JSON.stringify(cartaData));

                    $.ajax({
                        type: 'POST',
                        url: '/funciones/CARRITO/quitar_articulo.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {

                            const data = typeof response === "string" ? JSON.parse(response) : response;

                            if (data.success === "Cartas procesadas correctamente.") {

                                location.reload();

                            } else {

                                alert(data.message || "Ocurrió un error inesperado.");
                            }
                        },
                        error: function (response) {

                        }
                    })
                }
            }
        });

        document.getElementById("proceder_pago").addEventListener("click", function () {

            document.getElementById("pagar").style.display = "block";
            document.getElementById("confirmar_compra").style.display = "block";
        });

        document.getElementById("pagar").addEventListener("click", function () {

            const password = document.getElementById("confirmar_compra").value;
            const nombre_direccion = document.getElementById("nombre").value;

            if (nombre_direccion != "") {

                document.getElementById("nombre").disabled = false;

                if (password == "") {

                    document.getElementById("mensaje_confirmar_compra").innerHTML = "Introduzca su contraseña";
                    return;
                } else {

                    var monedero = document.getElementById("monedero").textContent;
                    var total = document.getElementById("total").textContent;

                    monedero = parseFloat(monedero);
                    total = parseFloat(total.replace("€", "").trim());

                    console.log("monedero:" + monedero + " total:" + total);

                    var total_compra = monedero - total;

                    console.log("total_compra:" + total_compra);


                    if (total_compra >= 0) {

                        datos_compra(password, total_compra);
                    }

                    if (total_compra < 0) {

                        document.getElementById("overlay").style.display = "flex";
                        document.getElementById("tarjeta").style.display = "block";

                        document.getElementById("pagar_ahora").addEventListener("click", function () {

                            var input_card_number = document.getElementById("input_card_number").value;
                            var input_card_holder = document.getElementById("input_card_holder").value;
                            var input_card_expiry = document.getElementById("input_card_expiry").value;
                            var input_cvv = document.getElementById("input_cvv").value;

                            if (!input_card_number || !input_card_holder || !input_card_expiry || !input_cvv) {

                                document.getElementById("mensaje_input_card_number").innerText = "Debe insertar un dato";
                                document.getElementById("mensaje_input_card_holder").innerText = "Debe insertar un dato";
                                document.getElementById("mensaje_input_card_expiry").innerText = "Debe insertar un dato";
                                document.getElementById("mensaje_input_cvv").innerText = "Debe insertar un dato";

                            } else {

                                total_compra = 0;
                                datos_compra(password, total_compra);
                            }
                        });
                    }
                }
            } else {

                document.getElementById("nombre").disabled = true;
            }
        });

        function datos_compra(password, total_compra) {

            const clienteId = localStorage.getItem("clienteId");
            const cards = document.querySelectorAll("#cartas_carrito .card");
            const resultados = [];

            const direccion = {

                nombre: document.getElementById("nombre").textContent,
                lineaExtra: document.getElementById("linea_extra").textContent,
                calle: document.getElementById("calle").textContent,
                codpostal: document.getElementById("codpostal").textContent,
                pais: document.getElementById("pais").textContent
            };

            cards.forEach(card => {

                const vendedor = card.querySelector(".card-header h5").textContent.trim();
                const resumen = card.querySelector(".resumen");
                const cartas = [];

                const totalArticulos = resumen.querySelector("p:nth-of-type(1)").textContent.split(":")[1].trim();
                const precioTotal = resumen.querySelector("p:nth-of-type(2)").textContent.split(":")[1].trim();
                const gastosEnvio = resumen.querySelector("p:nth-of-type(3)").textContent.split(":")[1].trim();
                const totalPedido = resumen.querySelector("p:nth-of-type(4)").textContent.split(":")[1].trim();

                const cartaItems = card.querySelectorAll(".cartas-list .carta-item");

                cartaItems.forEach(cartaItem => {

                    const nombreCarta = cartaItem.querySelector(".carta-info h6")?.textContent.trim() || "Sin nombre";
                    const cantidadTexto = cartaItem.querySelector(".carta-info p:nth-of-type(1)")?.textContent || "0";
                    const cantidad = cantidadTexto.split(":")[1]?.split("|")[0].trim() || "0";
                    const estadoTexto = cartaItem.querySelector(".carta-info p")?.textContent.trim() || "";
                    const estado = estadoTexto.includes("Estado:") ? estadoTexto.split("Estado:")[1].split("|")[0].trim() : "Desconocido";
                    const idioma = estadoTexto.includes("Idioma:")
                        ? estadoTexto.split("Idioma:")[1]?.split("|")[0].trim() || "Desconocido"
                        : "Desconocido";
                    const observacion = cartaItem.querySelector(".carta-info p:nth-of-type(2)")?.textContent.split(":")[1].trim() || "Sin observación";
                    const precioTexto = cartaItem.querySelector(".carta-precio p")?.textContent || "0.00€";
                    const precio = precioTexto.includes(":") ? precioTexto.split(":")[1].trim() : precioTexto.trim();

                    cartas.push({
                        nombreCarta,
                        cantidad,
                        estado,
                        idioma,
                        observacion,
                        precio
                    });
                });

                resultados.push({
                    clienteId,
                    password,
                    direccion,
                    vendedor,
                    totalArticulos,
                    precioTotal,
                    gastosEnvio,
                    totalPedido,
                    total_compra,
                    cartas
                });
            });

            console.log(resultados);

            const formData = new FormData();

            formData.append('resultados', JSON.stringify(resultados));

            $.ajax({
                type: 'POST',
                url: '/funciones/CARRITO/pagar.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let data;
                
                    try {
                        // Intentamos hacer un JSON.parse, si no es un JSON válido, fallará.
                        data = JSON.parse(response);
                    } catch (e) {
                        sessionStorage.setItem('showAlert', 'true');
                        window.location.href = "../INDEX/index.php";
                    }
                
                    // Ahora puedes trabajar con 'data' como una cadena o un objeto JSON.
                    if (typeof data === 'object' && data !== null && data.error) {
                        if (confirm("Por favor introduzca bien su contraseña")) {
                            location.reload();
                        }
                    } else {
                        sessionStorage.setItem('showAlert', 'true');
                        window.location.href = "../INDEX/index.php";
                    }
                },
                error: function (response) {

                }
            });
        }

    });
});
