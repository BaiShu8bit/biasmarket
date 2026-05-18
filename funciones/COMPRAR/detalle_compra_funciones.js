document.addEventListener("DOMContentLoaded", function () {

    $(document).ready(function () {

        var clienteId = localStorage.getItem("clienteId");

        var queryString = window.location.search;

        var params = new URLSearchParams(queryString);

        var itemValue = params.get('item');

        var itemValueCod = decodeURIComponent(itemValue);

        console.log(itemValueCod);

        const modal = document.getElementById("modal_confirmar_envio");
        const openModalButton = document.getElementById("button_confirmar_envio");
        const closeModalButton = document.getElementById("close_modal");
        const form = document.getElementById("evaluacion_form");

        openModalButton.addEventListener("click", () => {

            console.log("Abriendo modal...");
            modal.classList.remove("hidden");
        });
        
        closeModalButton.addEventListener("click", () => {

            modal.classList.add("hidden");
        });

        form.addEventListener("submit", (e) => {

            e.preventDefault();
            const evaluacion = document.getElementById("evaluacion").value;
            const comentarios = document.getElementById("comentarios").value;

            console.log("Evaluación:", evaluacion);
            console.log("Comentarios:", comentarios);

            var gastos_envio = document.getElementById("gastos_envio").innerText;
            var total_pedido = document.getElementById("total_pedido").innerText;
            var direccion_nombreV = document.getElementById("direccion_nombreV").innerText;

            var formData = new FormData();
            formData.append('clienteId', clienteId);
            formData.append('pedidoId', itemValueCod);
            formData.append('gastos_envio', gastos_envio);
            formData.append('total_pedido', total_pedido);
            formData.append('direccion_nombreV', direccion_nombreV);
            formData.append('evaluacion', evaluacion);
            formData.append('comentarios', comentarios);

            $.ajax({
                type: 'POST',
                url: '/funciones/COMPRAR/llegada.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {

                    location.href = "./compras.php";

                },
                error: function (xhr, status, error) {

                    console.error(error);
                }
            });

            modal.classList.add("hidden");
            alert("Gracias por tu evaluación!");
        });
    });
});
