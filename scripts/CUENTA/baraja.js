window.addEventListener("DOMContentLoaded", function () {

    $(document).ready(function () {

        var clienteId = localStorage.getItem("clienteId");

        if (!clienteId) {

            location.href = "../INDEX/index.php";

        } else {

            const mainDeck = document.querySelector(".main-deck");
            const extraDeck = document.querySelector(".extra-side-deck");
            const sideDeck = document.querySelector("#side + .extra-side-deck");

            for (let i = 0; i < 60; i++) {

                const cardSlot = document.createElement("div");
                cardSlot.classList.add("card-slot");
                cardSlot.dataset.zona = "main";
                mainDeck.appendChild(cardSlot);
            }

            for (let i = 0; i < 15; i++) {

                const cardSlot = document.createElement("div");
                cardSlot.classList.add("card-slot");
                cardSlot.dataset.zona = "extra";
                extraDeck.appendChild(cardSlot);
            }

            for (let i = 0; i < 15; i++) {

                const cardSlot = document.createElement("div");
                cardSlot.classList.add("card-slot");
                cardSlot.dataset.zona = "side";
                sideDeck.appendChild(cardSlot);
            }


            function cargarBarajas(clienteId) {
                $.ajax({
                    type: 'POST',
                    url: '/funciones/CUENTA/datos_barajas.php',
                    data: { "clienteId": clienteId, "dato1": "1" },
                    success: function (response) {

                        try {

                            const data = JSON.parse(response);

                            if (data !== "no hay datos") {

                                if (data) {

                                    const selectElement = document.getElementById("barajas");
                                    const barajas = data; 

                                    selectElement.innerHTML = "";

                                    const barajasOrdenadas = barajas.sort((a, b) => (b.principal === "SI" ? 1 : 0) - (a.principal === "SI" ? 1 : 0));

                                    barajasOrdenadas.forEach(baraja => {

                                        const option = document.createElement("option");
                                        option.value = baraja.nombre_baraja;
                                        option.textContent = baraja.nombre_baraja;
                                        selectElement.appendChild(option);
                                    });

                                    const principalIndex = barajasOrdenadas.findIndex(baraja => baraja.principal === "SI");

                                    if (principalIndex !== -1) {

                                        selectElement.selectedIndex = principalIndex;
                                        cargarCartas(clienteId, barajasOrdenadas[principalIndex].nombre_baraja);

                                    } else if (selectElement.options.length > 0) {

                                        cargarCartas(clienteId, selectElement.options[0].value);
                                    }
                                } else {

                                    console.error("La estructura de 'response.resultado' no es válida o no es un array.");
                                }
                            }
                        } catch (error) {

                            console.error("Error al procesar el JSON:", error.message);
                        }
                    },
                    error: function () {

                        console.error("Error al obtener sugerencias.");
                    }
                });
            }

            function cargarCartas(clienteId, nombreBaraja) {

                if (!nombreBaraja) return; 

                $.ajax({
                    type: 'POST',
                    url: '/funciones/CUENTA/datos_barajas.php',
                    data: { "clienteId": clienteId, "nombre_baraja": nombreBaraja, "dato2": "2" },
                    success: function (response) {
                        try {
                            const data = JSON.parse(response);

                            if (data !== "no hay datos") {

                                if (data.resultado && Array.isArray(data.resultado)) {

                                    document.querySelectorAll(".card-slot img").forEach(img => img.remove());

                                    data.resultado.forEach(carta => {
                                        const zona = carta.zona_carta;
                                        const nombreCarta = carta.nombre_carta;
                                        const tipoCarta = carta.tipo_carta;
                                        const urlImagen = carta.url_carta;

                                        let slot;
                                        if (zona === "main") {

                                            slot = document.querySelector('.card-grid.main-deck .card-slot[data-zona="main"]:empty');

                                        } else if (zona === "extra") {

                                            slot = document.querySelector('.card-grid.extra-side-deck .card-slot[data-zona="extra"]:empty');

                                        } else if (zona === "side") {

                                            slot = document.querySelector('.card-grid.extra-side-deck .card-slot[data-zona="side"]:empty');
                                        }

                                        if (slot) {

                                            const cardImage = document.createElement("img");
                                            cardImage.src = urlImagen;
                                            cardImage.alt = nombreCarta;
                                            cardImage.setAttribute("data-name", nombreCarta);
                                            cardImage.setAttribute("data-type", tipoCarta);
                                            cardImage.setAttribute("draggable", "true");
                                            slot.appendChild(cardImage);
                                        }
                                    });
                                } else {

                                    console.error("La estructura de 'response.resultado' no es válida o no es un array.");
                                }
                            }
                        } catch (error) {

                            console.error("Error al procesar el JSON:", error.message);
                        }
                    },
                    error: function () {

                        console.error("Error al obtener sugerencias.");
                    }
                });
            }

            cargarBarajas(clienteId);

            document.getElementById("barajas").addEventListener("change", function () {

                const selectedOption = this.options[this.selectedIndex].value;
                cargarCartas(clienteId, selectedOption);
            });


            $("#form2").on("keyup", function () {
                const query = $(this).val().trim();

                if (query.length >= 2) { 

                    $.ajax({
                        type: 'POST',
                        url: '/funciones/api.php',
                        data: { buscar: query },
                        success: function (response) {

                            const data = JSON.parse(response);
                            const suggestions = $("#suggestions2");

                            suggestions.empty(); 

                            const cardSlots = $(".card-slot1");

                            if (data.image_names && data.image_names.length > 0) {

                                cardSlots.each(function (index) {

                                    if (index < data.image_urls.length) {

                                        const img = `<img src="${data.image_urls[index]}" alt="${data.image_names[index]}" data-name="${data.image_names[index]}" data-type="${data.image_types[index]}"/>`;

                                        $(this).html(img);  

                                        $(this).data("image-url", data.image_urls[index]);
                                    } else {
                                        $(this).html("");  
                                    }
                                });
                            } else {
                                cardSlots.each(function () {

                                    $(this).html("");
                                });
                            }
                        },
                        error: function () {
                            console.error("Error al obtener sugerencias.");
                        }
                    });
                } else {

                    $(".card-slot1").html("");
                }
            });

            $(".card-slot").on("mouseenter", function () {

                const imageUrl = $(this).data("image-url");

                if (imageUrl) {

                    $(".big-card-slot").html(`<img src="${imageUrl}" alt="Carta ampliada" />`);
                }
            });

            $(".card-slot").on("mouseleave", function () {

                $(".big-card-slot").html("");
            });

            $(".card-slot1").on("mouseenter", function () {

                const imageUrl = $(this).data("image-url");

                if (imageUrl) {

                    $(".big-card-slot").html(`<img src="${imageUrl}" alt="Carta ampliada" />`);
                }
            });

            $(".card-slot1").on("mouseleave", function () {

                $(".big-card-slot").html("");
            });

            $(".main").on("mouseenter", ".card-slot img", function () {

                const imageUrl = $(this).attr("src");
            
                if (imageUrl) {

                    $(".big-card-slot").html(`<img src="${imageUrl}" alt="Carta ampliada" />`);
                }
            });
            
            $(".main").on("mouseleave", ".card-slot img", function () {

                $(".big-card-slot").html("");
            });

            $(document).on("click", ".suggestion2-item", function () {

                const selectedName = $(this).data("name");
                $("#form2").val(selectedName);
                $("#suggestions2").hide();
            });

            $(document).on("dragstart", ".card-slot1 img, .card-slot img", function (event) {

                const imageUrl = $(this).attr("src");
                const imageName = $(this).data("name");
                const imageType = $(this).data("type");
            
                if (!imageUrl || !imageName || !imageType) {

                    console.error("Faltan datos para arrastrar");
                    return;
                }
            
                const imageData = `${imageUrl}|${imageName}|${imageType}`;
                event.originalEvent.dataTransfer.setData("text/plain", imageData);
            
                console.log("Imagen arrastrada con datos:", imageData);
            });
            
            $(".card-slot").on("dragover", function (event) {

                event.preventDefault(); 
                $(this).css("background-color", "#ccc");
            });

            $(".card-slot").on("dragleave", function () {

                $(this).css("background-color", "");
            });

            $(".card-slot").on("drop", function (event) {

                event.preventDefault();  
                $(this).css("background-color", ""); 

                const droppedData = event.originalEvent.dataTransfer.getData("text/plain"); 

                if (droppedData) {

                    const [imageUrl, imageName, imageType] = droppedData.split("|");  

                    $(this).html(`<img src="${imageUrl}" alt="${imageName}" data-name="${imageName}" data-type="${imageType}" draggable="true" />`);
                    $(this).data("image-url", imageUrl);  
                    $(this).data("data-name", imageName);  
                    $(this).data("data-type", imageType);  

                    console.log("Imagen insertada con URL:", imageUrl, "Nombre:", imageName, "Tipo:", imageType);
                } else {

                    console.error("No se encontraron datos válidos en el drop.");
                }
            });

            $(".card-slot").on("click", function () {
                
                $(this).html("");
                $(this).removeData("image-url");
                console.log("Contenido eliminado del hueco.");
            });
        }
    });
});
