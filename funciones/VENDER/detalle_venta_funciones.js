document.addEventListener("DOMContentLoaded", function () {

    $(document).ready(function () {

        var clienteId = localStorage.getItem("clienteId");

        var queryString = window.location.search;

        var params = new URLSearchParams(queryString);

        var itemValue = params.get('item');

        var itemValueCod = decodeURIComponent(itemValue);

        console.log(itemValueCod); 

        document.getElementById("button_confirmar_envio").addEventListener("click", function (event) {

            var gastos_envio = document.getElementById("gastos_envio").textContent ;
            var total_pedido = document.getElementById("total_pedido").textContent ;

            var formData = new FormData();
            formData.append('clienteId', clienteId);
            formData.append('pedidoId', itemValueCod);
            formData.append('gastos_envio', gastos_envio);
            formData.append('total_pedido', total_pedido);

            $.ajax({
                type: 'POST',
                url: '/funciones/VENDER/enviar.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {

                    location.href = "./index.php";

                },
                error: function (xhr, status, error) {

                    console.error(error);
                }
            });
        })

        document.getElementById("button_numero_seguimiento").addEventListener("click", function (event) {

            var numero_seguimiento = document.getElementById("input_numero_seguimiento").value;

            var formData = new FormData();
            formData.append('numero_seguimiento', numero_seguimiento);
            formData.append('pedidoId', itemValueCod);

            $.ajax({
                type: 'POST',
                url: '/funciones/VENDER/numero_seguimiento.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {

                    location.reload();

                },
                error: function (xhr, status, error) {

                    console.error(error);
                }
            });
        })

    });
});
