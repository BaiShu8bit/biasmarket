document.addEventListener("DOMContentLoaded", function () {

    $(document).ready(function () {

        if (localStorage.getItem("clienteId")) {

            //VENDER
            document.getElementById("form_publicacion").addEventListener("submit", function (event) {

                event.preventDefault();

                var url = window.location.href;

                var searchParams = new URLSearchParams(new URL(url).search);

                var nombre_carta = searchParams.get("item");

                //console.log(nombre_carta);

                var form_cantidad = document.getElementById("form_cantidad").value;
                var form_idioma = document.getElementById("form_idioma").value;
                var form_estado = document.getElementById("form_estado").value;
                var form_observaciones = document.getElementById("form_observaciones").value;
                var form_precio = document.getElementById("form_precio").value;

                console.log("nombre_carta:", nombre_carta);
                console.log("form_cantidad:", form_cantidad);
                console.log("form_idioma:", form_idioma);
                console.log("form_estado:", form_estado);
                console.log("form_observaciones:", form_observaciones);
                console.log("form_precio:", form_precio);

                var formData = new FormData();
                formData.append('nombre_carta', nombre_carta);
                formData.append('form_cantidad', form_cantidad);
                formData.append('form_idioma', form_idioma);
                formData.append('form_estado', form_estado);
                formData.append('form_observaciones', form_observaciones);
                formData.append('form_precio', form_precio);

                $.ajax({
                    type: 'POST',
                    url: '/funciones/FICHA_CARTA/formulario_publicacion.php',
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
            });

            //EDITAR LA publicacion
            function editar_publicacion() {

                var id_editar = document.getElementById("id_editar").value;
                var estado_editar = document.getElementById("estado_editar").value;
                var idioma_editar = document.getElementById("idioma_editar").value;
                var observacion_editar = document.getElementById("observacion_editar").value;
                var precio_editar = document.getElementById("precio_editar").value;
                var cantidad_editar = document.getElementById("cantidad_editar").value;

                /*
                console.log("Id publicacion:", id_editar);
                console.log("Nombre de usuario:", nombre_editar);
                console.log("Estado de la carta:", estado_editar);
                console.log("Idioma de la carta:", idioma_editar);
                console.log("Observación de la carta:", observacion_editar);
                console.log("Precio de la carta:", precio_editar);
                console.log("Carrito de la carta:", cantidad_editar);
                */

                var formData = new FormData();
                formData.append('id_editar', id_editar);
                formData.append('estado_editar', estado_editar);
                formData.append('idioma_editar', idioma_editar);
                formData.append('observacion_editar', observacion_editar);
                formData.append('precio_editar', precio_editar);
                formData.append('cantidad_editar', cantidad_editar);
                console.log(formData)

                $.ajax({
                    type: 'POST',
                    url: '/funciones/FICHA_CARTA/editar_publicacion.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        if (response === "1") {
                            window.location.reload();
                        }
                    },
                    error: function (xhr, status, error) {

                        console.error(error);
                    }
                });
            };

            $(function () {

                $("form[name='editar_publicacion_vali']").validate({

                    rules: {

                        precio_editar: {
                            required: true,
                            minlength: 1,
                            number: true,
                            min: 0.01
                        },
                        cantidad_editar: {
                            required: true,
                            minlength: 1,
                            number: true,
                            min: 0
                        },
                    },
                    messages: {

                        precio_editar: {
                            required: "Por favor, introduzca un precio.",
                            number: "El precio debe ser un número válido.",
                            min: "El precio mínimo permitido es de 0.01.",
                            minlength: "El precio debe tener al menos 1 carácter."
                        },
                        cantidad_editar: {
                            required: "Por favor, introduzca una cantidad.",
                            number: "La cantidad debe ser un número válido.",
                            min: "La cantidad mínima permitido es de 0.",
                            minlength: "La cantidad debe tener al menos 1 carácter."
                        },
                    },

                    submitHandler: function (form) {
                        //form.submit();
                        editar_publicacion()
                        //location.reload();
                    }
                });
            });

            //EVENTOS
            const modalEditar = document.getElementById('modal_editar');
            modalEditar.addEventListener('show.bs.modal', event => {

                const button = event.relatedTarget;

                const publicacionId = button.getAttribute('data-publicacionid');

                document.getElementById('id_editar').value = publicacionId;
            });

            tabla_publicaciones = document.getElementById("tabla_publicaciones");

            tabla_publicaciones.addEventListener('click', function (event) {

                if (event.target.classList.contains('boton_carrito')) {

                    var boton = event.target;

                    var idpublicacion = boton.getAttribute('id');

                    var fila = boton.closest('tr');


                    if (fila) {

                        var nombre_usuario = fila.cells[0].textContent;
                        var estado_carta = fila.cells[1].textContent;
                        var idioma_carta = fila.cells[2].querySelector('img').getAttribute('value');
                        var observacion_carta = fila.cells[3].textContent;
                        var precio_carta = fila.cells[4].textContent;
                        var cantidad_carta = fila.cells[6].querySelector('select').value;
                        var idpublicacion = fila.cells[7].querySelector('img').getAttribute('value');

                        var url = window.location.href;

                        var searchParams = new URLSearchParams(new URL(url).search);

                        var nombre_carta = searchParams.get("item");

                        var clienteId = localStorage.getItem("clienteId");

                        /*
                            console.log("ID de la publicacion:", idpublicacion);
                            console.log("Nombre de usuario:", nombre_usuario);
                            console.log("Estado de la carta:", estado_carta);
                            console.log("Idioma de la carta:", idioma_carta);
                            console.log("Observación de la carta:", observacion_carta);
                            console.log("Precio de la carta:", precio_carta);
                            console.log("Carrito de la carta:", cantidad_carta);
                        */
                        var formData = new FormData();
                        formData.append('idpublicacion', idpublicacion);
                        formData.append('clienteId', clienteId);
                        formData.append('nombre_usuario', nombre_usuario);
                        formData.append('nombre_carta', nombre_carta);
                        formData.append('estado_carta', estado_carta);
                        formData.append('idioma_carta', idioma_carta);
                        formData.append('observacion_carta', observacion_carta);
                        formData.append('precio_carta', precio_carta);
                        formData.append('cantidad_carta', cantidad_carta);

                        $.ajax({
                            type: 'POST',
                            url: '/funciones/FICHA_CARTA/anyadir_a_carrito.php',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {

                                const data = typeof response === "string" ? JSON.parse(response) : response;
                    
                                if (data.success === "Operación completada correctamente.") {
        
                                    location.reload();
        
                                } else {
        
                                    alert(data.success || "Ocurrió un error inesperado.");
                                }
                            },
                            error: function (xhr, status, error) {

                                console.error(error);
                            }
                        });

                    } else {

                        console.error('No se encontró la fila asociada al botón.');
                    }
                }
            });

            const table = document.getElementById('tabla_publicaciones');
            const idioma_editar = document.getElementById('idioma_editar');
            const observacion_editar = document.getElementById('observacion_editar');
            const precio_editar = document.getElementById('precio_editar');
            const cantidad_editar = document.getElementById('cantidad_editar');
            const id_editar = document.getElementById('id_editar');

            table.addEventListener('click', (e) => {

                e.stopPropagation();

                const fila = e.target.closest('tr');

                if (fila) {

                    const idiomaImagen = fila.children[2]?.querySelector('img');
                    const idiomaValor = idiomaImagen?.getAttribute('value');
                    console.log("Valor del idioma en la imagen:", idiomaValor);

                    if (idiomaValor) {
                        let encontrado = false;
                        for (let option of idioma_editar.options) {
                            if (option.value.toLowerCase() === idiomaValor.toLowerCase()) {
                                idioma_editar.value = option.value; // Asigna el valor correcto
                                encontrado = true;
                                break;
                            }
                        }

                        if (!encontrado) {
                            console.log("No se encontró una opción que coincida con el idioma en la imagen.");
                        }
                    } else {
                        console.log("No se encontró el atributo `value` en la imagen del idioma.");
                    }

                    observacion_editar.value = fila.children[3]?.textContent || '';

                    let precio = fila.children[4]?.textContent || '';
                    precio = precio.replace(/,/g, '.').replace('€', '').trim();
                    precio_editar.value = precio;

                    cantidad_editar.value = fila.children[5]?.textContent || '';

                    const imagen = e.target.querySelector('.boton_carrito');
                    if (imagen) {
                        id_editar.value = imagen.id || '';
                    }
                }
            });

            document.getElementById("datos_form_want").addEventListener("click", function(){

                event.preventDefault();

                var clienteId = localStorage.getItem("clienteId"); 

                var url = window.location.href;

                var searchParams = new URLSearchParams(new URL(url).search);

                var nombre_carta = searchParams.get("item");

                const form_want_lista = document.getElementById('form_want_lista').value;

                if(form_want_lista == ""){
                    
                    document.getElementById("mensaje_lista").innerHTML = "Debe crear primero una lista."
                    event.preventDefault();
                    return;
                }

                const form_want_cantidad = document.getElementById('form_want_cantidad').value;
                const form_want_idioma = document.getElementById('form_want_idioma').value;
                const form_want_estado = document.getElementById('form_want_estado').value;
                const form_want_precio = document.getElementById('form_want_precio').value;

                var formData = new FormData();
                formData.append('clienteId', clienteId);
                formData.append('nombre_carta', nombre_carta);
                formData.append('form_want_lista', form_want_lista);
                formData.append('form_want_cantidad', form_want_cantidad);
                formData.append('form_want_idioma', form_want_idioma);
                formData.append('form_want_estado', form_want_estado);
                formData.append('form_want_precio', form_want_precio);

                $.ajax({
                    type: 'POST',
                    url: '/funciones/FICHA_CARTA/anyadir_wishlist.php',
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
        }
    });
});

