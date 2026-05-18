function obtenerCuenta(clienteId, callback) {

    $.ajax({
        url: "../../funciones/CUENTA/datos_cuenta.php",
        type: "POST",
        dataType: "json",
        data: { clienteId: clienteId },

        success: function (res) {

            console.log("RESPUESTA CUENTA:", res);

            if (res.status !== "success") {
                console.error("Error backend:", res.message);
                return;
            }

            callback(res.data);
        },

        error: function (xhr) {
            console.error("ERROR AJAX:", xhr.responseText);
        }
    });
}

function guardarDireccion(data, callback) {

    $.ajax({
        url: "../../funciones/CUENTA/guardar_direccion.php",
        type: "POST",
        data: data,
        dataType: "json",

        success: function (res) {
            callback(res);
        },

        error: function (xhr) {
            console.error("ERROR AJAX:", xhr.responseText);
        }
    });

}

function obtenerDireccionPrincipal(callback) {
    $.ajax({
        url: "../../funciones/CUENTA/direccion_principal.php",
        type: "GET",
        dataType: "json",
        success: function (res) {
            callback(res);
        },
        error: function (xhr) {
            console.error("ERROR DIRECCIÓN:", xhr.responseText);
        }
    });
}