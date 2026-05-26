$(document).ready(function () {

    $.ajax({
        url: '../../funciones/VENDER/datos_venta.php',
        type: 'POST',
        dataType: 'json',

        success: function (datos) {

            console.log(datos);

            if (!datos || datos.length === 0) {
                console.error("No hay pedidos");
                return;
            }

            const tbody =
                document.getElementById("table-body");

            tbody.innerHTML = "";

            datos.forEach(pedido => {

                let certificado = "No";

                if (pedido.totalPedido > 50) {
                    certificado = "Sí";
                }

                const tr = `
                    <tr>
                        <td>
                            <a href="detalle_venta.php?item=${pedido.pedidoId}">
                                ${pedido.pedidoId}
                            </a>
                        </td>
                        <td>${pedido.fechaPedido}</td>
                        <td>${pedido.clienteId}</td>
                        <td>${certificado}</td>
                        <td>${pedido.totalPedido} €</td>
                    </tr>
                `;

                tbody.innerHTML += tr;
            });
        },

        error: function (xhr, status, error) {
            console.error("Error AJAX:", error);
            console.log(xhr.responseText);
        }
    });
});