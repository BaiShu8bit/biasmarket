$(document).ready(function () {

    // Comprobar login
    if (!localStorage.getItem("clienteId")) {

        location.href = "../INDEX/index.php";
        return;
    }

    const clienteId =
        localStorage.getItem("clienteId");

    // Obtener ?item=
    const params =
        new URLSearchParams(window.location.search);

    const itemValue =
        params.get("item");

    const itemValueCod =
        decodeURIComponent(itemValue);

    console.log(itemValueCod);

    $.ajax({
        url: '../../funciones/VENDER/datos_venta.php',
        type: 'POST',

        data: {
            clienteId: clienteId,
            pedidoId: itemValueCod
        },

        dataType: 'json',

        success: function (datos) {

            console.log(datos);

            if (!datos || datos.length === 0) {

                console.error("No hay datos");
                return;
            }

            // Limpiar por si acaso
            $('#cartas').html('');

            document.getElementById("pedidoId").innerText =
                "Venta #" + datos[0].pedidoId;

            // ====================
            // PEDIDO ENVIADO
            // ====================
            if (datos[0].estado_pedido === "enviado") {

                document.getElementById("confirmar_envio")
                    .style.display = "none";

                if (datos[0].gastos_envio === "5€") {

                    document.getElementById("numero_seguimiento")
                        .style.display = "block";

                    if (datos[0].numero_seguimiento != null) {

                        document.getElementById("input_numero_seguimiento")
                            .value =
                            datos[0].numero_seguimiento;

                        document.getElementById("input_numero_seguimiento")
                            .disabled = true;

                    } else {

                        document.getElementById("button_numero_seguimiento")
                            .style.display = "block";
                    }
                }
            }

            // ====================
            // PEDIDO RECIBIDO
            // ====================
            if (datos[0].estado_pedido === "recibido") {

                document.getElementById("evaluacion_pedido")
                    .style.display = "block";

                document.getElementById("confirmar_envio")
                    .style.display = "none";

                document.getElementById("evaluacion_general")
                    .innerHTML =
                    "<strong>Evaluación general: </strong>" +
                    datos[0].evaluacion;

                document.getElementById("evaluacion_comentarios")
                    .innerHTML =
                    "<strong>Comentarios: </strong>" +
                    datos[0].comentarios;
            }

            // ====================
            // PINTAR DATOS
            // ====================
            datos.forEach(venta => {

                const resumen = `
                    <strong style="color: #012269;">
                        Resumen:
                    </strong>
                    <br><br>

                    <div class="row">
                        <strong>Contenido:</strong>
                        <span class="value">
                            ${venta.articulos}
                        </span>
                    </div>

                    <div class="row">
                        <strong>Valor del pedido:</strong>
                        <span class="value">
                            ${venta.valor_pedido}
                        </span>
                    </div>

                    <div class="row">
                        <strong>Gastos de envío:</strong>
                        <span class="value" id="gastos_envio">
                            ${venta.gastos_envio}
                        </span>
                    </div>

                    <div class="row">
                        <strong>Total:</strong>
                        <span class="value" id="total_pedido">
                            ${venta.total_pedido}
                        </span>
                    </div>
                `;

                $('#resumen-pedido')
                    .html(resumen);

                const direccion = `
                    <strong style="color: #012269;">
                        Dirección:
                    </strong>
                    <br><br>

                    <div class="row">
                        <strong>Nombre:</strong>
                        <span class="value">
                            ${venta.direccion_nombreC}
                        </span>
                    </div>

                    <div class="row">
                        <strong>Calle:</strong>
                        <span class="value">
                            ${venta.direccion_calleC}
                        </span>
                    </div>

                    <div class="row">
                        <strong>Código Postal:</strong>
                        <span class="value">
                            ${venta.direccion_codpostC}
                            ${venta.direccion_localidadC}
                        </span>
                    </div>

                    <div class="row">
                        <strong>País:</strong>
                        <span class="value">
                            ${venta.direccion_paisC}
                        </span>
                    </div>
                `;

                $('#direccion-comprador')
                    .html(direccion);

                const cartaHtml = `
                    <div class="carta">

                        <strong>Carta:</strong>

                        <a href="../../HTML/FICHA_CARTA/index.php?item=${encodeURIComponent(venta.nombre_carta)}"
                           target="_blank">

                            ${venta.nombre_carta}

                        </a>

                        &nbsp;&nbsp;&nbsp;

                        <strong>Precio:</strong>
                        ${venta.precio_carta}

                        &nbsp;&nbsp;&nbsp;

                        <strong>Idioma:</strong>
                        ${venta.idioma_carta}

                        &nbsp;&nbsp;&nbsp;

                        <strong>Observación:</strong>
                        ${venta.observacion_carta}
                    </div>
                `;

                $('#cartas').append(cartaHtml);
            });
        },

        error: function (xhr, status, error) {

            console.error("Error AJAX:", error);
            console.log(xhr.responseText);
        }
    });
});