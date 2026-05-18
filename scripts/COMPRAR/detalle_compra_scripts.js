if (!localStorage.getItem("clienteId")) {
    location.href = "../INDEX/index.php";
} else {

    var clienteId = localStorage.getItem("clienteId");

    var queryString = window.location.search;

    var params = new URLSearchParams(queryString);

    var itemValue = params.get('item');

    var itemValueCod = decodeURIComponent(itemValue);

    console.log(itemValueCod); 

    var formData = new FormData();
    formData.append('clienteId', clienteId);
    formData.append('pedidoId', itemValueCod);

    $.ajax({
        url: '/funciones/COMPRAR/datos_compra.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {

            const datos = JSON.parse(response);

            document.getElementById("pedidoId").innerText = "Compra #" + datos[0].pedidoId;

            if (datos[0].estado_pedido == "enviado") {

                document.getElementById("confirmar_envio").style.display = "none";

                if (datos[0].gastos_envio == "5€") {

                    if (datos[0].numero_seguimiento != null) {
                        document.getElementById("numero_seguimiento").style.display = "block";
                        document.getElementById("input_numero_seguimiento").value = datos[0].numero_seguimiento;
                        document.getElementById("input_numero_seguimiento").disabled = true;
                    } else {
                    }
                }

                document.getElementById("confirmar_envio").style.display = "block";
            }

            if (datos[0].estado_pedido == "recibido") {

                document.getElementById("evaluacion_pedido").style.display = "block";

                    document.getElementById("evaluacion_general").innerHTML = "<strong>Evaluación general: </strong>" + datos[0].evaluacion;
                    document.getElementById("evaluacion_comentarios").innerHTML = "<strong>Comentarios: </strong>" + datos[0].comentarios;
            }

            datos.forEach(compra => {

                const resumen = `
                    <strong style="color: #012269;">Resumen:</strong><br><br>
                    <div class="row">
                        <strong>Contenido:</strong>
                        <span class="value">${compra.articulos}</span>
                    </div>
                    <div class="row">
                        <strong>Valor del pedido:</strong>
                        <span class="value">${compra.valor_pedido}</span>
                    </div>
                    <div class="row">
                        <strong>Gastos de envío:</strong>
                        <span class="value" id="gastos_envio">${compra.gastos_envio}</span>
                    </div>
                    <div class="row">
                        <strong>Total:</strong>
                        <span class="value" id="total_pedido">${compra.total_pedido}</span>
                    </div>
                `;
                $('#resumen-pedido').html(resumen);

                const direccionV = `
                    <strong style="color: #012269;">Dirección vendedor:</strong><br><br>
                    <div class="row">
                        <strong>Nombre:</strong>
                        <span class="value" id="direccion_nombreV">${compra.direccion_nombreV}</span>
                    </div>
                    <div class="row">
                        <strong>Calle:</strong>
                        <span class="value">${compra.direccion_calleV}</span>
                    </div>
                    <div class="row">
                        <strong>Código Postal:</strong>
                        <span class="value">${compra.direccion_codpostV} ${compra.direccion_localidadV}</span>
                    </div>
                    <div class="row">
                        <strong>País:</strong>
                        <span class="value">${compra.direccion_paisV}</span>
                    </div>
                `;
                $('#direccion-vendedor').html(direccionV);

                const direccionC = `
                    <strong style="color: #012269;">Dirección comprador:</strong><br><br>
                    <div class="row">
                        <strong>Nombre:</strong>
                        <span class="value">${compra.direccion_nombreC}</span>
                    </div>
                    <div class="row">
                        <strong>Calle:</strong>
                        <span class="value">${compra.direccion_calleC}</span>
                    </div>
                    <div class="row">
                        <strong>Código Postal:</strong>
                        <span class="value">${compra.direccion_codpostC} ${compra.direccion_localidadC}</span>
                    </div>
                    <div class="row">
                        <strong>País:</strong>
                        <span class="value">${compra.direccion_paisC}</span>
                    </div>
                `;
                $('#direccion-comprador').html(direccionC);

                const cartaHtml = `
                    <div class="carta">
                        <strong>Carta:</strong> 
                        <a href="https://onlinecardtcg.com/HTML/FICHA_CARTA/index.php?item=${encodeURIComponent(compra.nombre_carta)}" target="_blank">${compra.nombre_carta}</a>&nbsp;&nbsp;&nbsp;
                        <strong>Precio:</strong> ${compra.precio_carta}&nbsp;&nbsp;&nbsp;
                        <strong>Idioma:</strong> ${compra.idioma_carta}&nbsp;&nbsp;&nbsp;
                        <strong>Observación:</strong> ${compra.observacion_carta}
                    </div>
                `;
                $('#cartas').append(cartaHtml);
            });

        },
        error: function () {
            
            console.error("Error al cargar contenido dinámico");
        }
    });

}