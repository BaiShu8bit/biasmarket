document.addEventListener("DOMContentLoaded", function () {

    $(document).ready(function () {

        var queryString = window.location.search;

        var params = new URLSearchParams(queryString);

        var itemValue = params.get('item');

        var itemValueCod = decodeURIComponent(itemValue);

        var clienteId = localStorage.getItem("clienteId");

        document.addEventListener('click', function (event) {

            if (event.target.classList.contains('btn-danger')) {

                handleDelete(event);
            }

            function handleDelete(event) {

                const wishlistItem = event.target.closest('.item');
                if (wishlistItem) {

                    var nombre_carta = wishlistItem.querySelector('a').textContent;
                    var idioma_carta = wishlistItem.querySelector('span:nth-child(2)').textContent.split(': ')[1];
                    var cantidad_carta = wishlistItem.querySelector('span:nth-child(3)').textContent.split(': ')[1];
                    var precio_carta = wishlistItem.querySelector('span:nth-child(4)').textContent.split(': ')[1].replace('€', '');
                    var wishlistId = wishlistItem.querySelector('button.btn-danger').getAttribute('data-id');

                    var formData = new FormData();

                    formData.append('clienteId', clienteId);
                    formData.append('wishlistId', wishlistId);
                    console.log(formData);

                    if (confirm("ESTÁ A PUNTO DE BORRAR SU WISHLIST, ¿SEGURO QUE DESEA CONTINUAR?")) {
                        $.ajax({
                            type: 'POST',
                            url: '/funciones/COMPRAR/borrar_carta_wishlist.php',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {

                                if (response === "Error") {

                                    alert("Ha ocurrido un error, sentimos las molestias.");

                                } else {

                                    location.reload();
                                }
                            },
                            error: function (xhr, status, error) {

                                console.error(error);
                            }
                        });
                    }
                }
            }

            // Manejar edición
            if (event.target.classList.contains("edit-btn")) {

                const wishlistItem = event.target.closest('.item');

                const cardData = {

                    id: wishlistItem.querySelector('.btn-danger').dataset.id, 
                    nombre: wishlistItem.querySelector('a').textContent, 
                    idioma: wishlistItem.querySelector('span:nth-child(2)').textContent.replace('Idioma: ', ''), 
                    cantidad: wishlistItem.querySelector('span:nth-child(3)').textContent.replace('Cantidad: ', ''),
                    precio: wishlistItem.querySelector('span:nth-child(4)').textContent.replace('Precio: ', '').replace('€', '')
                };

                openEditModal(cardData);
            }

            function openEditModal(cardData) {
                const cardNameInput = document.getElementById("cardNameInput");
                const editModal = document.getElementById("editModal");
                const cardIdiomaInput = document.getElementById("cardIdiomaInput");
                const cardCantidadInput = document.getElementById("cardCantidadInput");
                const cardPrecioInput = document.getElementById("cardPrecioInput");
                const idCartaInput = document.getElementById("idcarta");

                cardNameInput.innerText = cardData.nombre;
                cardIdiomaInput.value = cardData.idioma;
                cardCantidadInput.value = cardData.cantidad;
                cardPrecioInput.value = cardData.precio;
                idCartaInput.value = cardData.id;

                editModal.style.display = "block";
            }

            let isSubmitting = false;

            document.getElementById('guardar').addEventListener('click', function () {

                if (isSubmitting) return; 
                isSubmitting = true;

                const editForm = document.getElementById('editForm');
                const formData = new FormData(editForm);
                formData.append('clienteId', clienteId);

                $.ajax({
                    type: 'POST',
                    url: '/funciones/COMPRAR/editar_cartas_wishlist.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        isSubmitting = false; 

                        if (response === "Error") {

                            alert("Ha ocurrido un error al guardar los cambios.");
                        } else {

                            location.reload();
                        }
                    },
                    error: function (xhr, status, error) {

                        isSubmitting = false; 
                        console.error(error);
                    }
                });
            });

            window.addEventListener("click", function (e) {

                const editModal = document.getElementById("editModal");

                if (e.target === editModal) {
                    
                    editModal.style.display = "none";
                }
            });
        });
    });
});
