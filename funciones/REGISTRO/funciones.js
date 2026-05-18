document.addEventListener("DOMContentLoaded", function () {

    function enviar_datos() {

        var formData = new FormData();

        formData.append('nombre', document.getElementById("nombre").value);
        formData.append('apellido1', document.getElementById("apellido1").value);
        formData.append('apellido2', document.getElementById("apellido2").value);
        formData.append('fecha_nacimiento', document.getElementById("fecha_nacimiento").value);
        formData.append('pais', document.getElementById("pais").value);
        formData.append('email', document.getElementById("email").value);
        formData.append('usuario', document.getElementById("usuario").value);
        formData.append('password', document.getElementById("password").value);

        $.ajax({
            type: 'POST',
            url: '../../funciones/REGISTRO/formulario_registro.php',
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            success: function (data) {

                console.log(data);

                if (data.status === "error") {

                    alert(data.message);

                    if (data.message.includes("usuario")) {
                        document.getElementById("mensaje_usuario").innerHTML = data.message;
                    }

                    if (data.message.includes("correo")) {
                        document.getElementById("mensaje_email").innerHTML = data.message;
                    }

                    return;
                }

                if (data.status === "success") {

                    localStorage.setItem("clienteId", data.clienteId);
                    location.href = "../../HTML/INDEX/index.php";
                }
            },

            error: function (xhr) {
                console.error("Error AJAX:", xhr.responseText);
            }
        });
    }

    $("form[name='formulario_registro_vali']").validate({

        rules: {
            nombre: "required",
            apellido1: "required",
            fecha_nacimiento: "required",
            pais: "required",
            email: { required: true, email: true },
            usuario: "required",
            password: { required: true, minlength: 8, maxlength: 12 }
        },

        submitHandler: function () {
            enviar_datos();
            return false;
        }
    });

});