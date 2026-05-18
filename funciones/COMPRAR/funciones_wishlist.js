document.addEventListener("DOMContentLoaded", function () {

    $(document).ready(function () {

        var clienteId = localStorage.getItem("clienteId")

        function anyadir_wishlist() {

            var wishlistName = document.getElementById("wishlistName").value;

            var formData = new FormData();

            formData.append('clienteId', clienteId);
            formData.append('wishlistName', wishlistName);
            console.log(formData);

            $.ajax({
                type: 'POST',
                url: '/funciones/COMPRAR/anyadir_wishlist.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response == "Error") {

                        document.getElementById("mensaje_nueva_wishlist").innerText = "Ese nombre ya esta siendo ocupado por una lista, elija otro";

                    } else {
                        location.reload();
                    }
                },
                error: function (xhr, status, error) {

                    console.error(error);
                }
            });
        };

        $(function () {

            $("form[name='createWishlistForm']").validate({

                rules: {
                    wishlistName: "required"
                },
                messages: {
                    wishlistName: "Por favor introduzca un nombre."
                },
                submitHandler: function (form) {

                    anyadir_wishlist();
                    return false;
                }
            });
        });

        document.addEventListener('click', function (event) {

            if (event.target.classList.contains('btn-danger')) {

                handleDelete(event);
            }

            function handleDelete(event) {

                const wishlistItem = event.target.closest('.wishlist-item');

                if (wishlistItem) {

                    const wishlistName = wishlistItem.querySelector('a').textContent;
                    console.log('Eliminar:', wishlistName);

                    var formData = new FormData();

                    formData.append('clienteId', clienteId);
                    formData.append('wishlistName', wishlistName);
                    console.log(formData);

                    if (confirm("ESTA APUNTO DE BORRAR SU WISHLIST, ¿SEGURO QUE DESEA CONTINUAR?")) {
                        $.ajax({
                            type: 'POST',
                            url: '/funciones/COMPRAR/borrar_wishlist.php',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                if (response == "Error") {

                                    alert("A ocurrido un error, sentimos las molestias.");

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

            if (event.target && event.target.classList.contains("edit-btn")) {

                const listName = event.target.closest(".wishlist-item").querySelector("a").textContent;
                openEditModal(listName);
            }         

            function openEditModal(listName) {

                const editModal = document.getElementById("editModal");
                const listNameInput = document.getElementById("listNameInput");
                const nombre_original = document.getElementById("nombre_original");

                listNameInput.value = listName;
                nombre_original.value = listName;

                editModal.style.display = "block";
            }

            window.addEventListener("click", function (e) {

                const editModal = document.getElementById("editModal");
                if (e.target === editModal) {
                    editModal.style.display = "none";
                }
            });
        });

        document.getElementById("guardar").addEventListener("click", function (e) {

            e.preventDefault();

            const listNameInput = document.getElementById("listNameInput");
            const listNameInputOriginal = document.getElementById("nombre_original");

            const newName = listNameInput.value;
            const originalName = listNameInputOriginal.value;

            if (newName === "") {
                
                alert("El nombre de la lista no puede estar vacío.");
                return;
            }

            var formData = new FormData();

            formData.append('clienteId', clienteId);
            formData.append('originalName', originalName);
            formData.append('newName', newName);
            console.log(formData);

            $.ajax({
                type: 'POST',
                url: '/funciones/COMPRAR/editar_wishlist.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response == "Error") {

                        alert("No se encontró ninguna lista con ese nombre original para actualizar.");
                    } else {
                        location.reload();
                    }
                },
                error: function (xhr, status, error) {

                    console.error(error);
                }
            });
        })
    });
});
