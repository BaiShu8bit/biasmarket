document.addEventListener("DOMContentLoaded", function () {

    $(document).ready(function () {

        const clienteId = localStorage.getItem("clienteId");

        if (clienteId) {

            const barajas_select = document.getElementById("barajas");

            const guardarBarajaBtn = document.getElementById('boton_guardar');
            const guardarBarajaModal = document.getElementById('guardarBarajaModal');
            const guardarNombreBtn = document.getElementById('guardarNombreBtn');
            const nombreBarajaInput = document.getElementById('nombreBaraja');

            const renombrarBarajaBtn = document.getElementById('boton_renombrar');
            const renombrarBarajaModal = document.getElementById('renombrarBarajaModal');
            const renombrarNombreBtn = document.getElementById('renombrarNombreBtn');
            const renombreBarajaInput = document.getElementById('renombreBaraja');

            const crearBarajaBtn = document.getElementById('boton_crear');
            const crearBarajaModal = document.getElementById('crearBarajaModal');
            const crearNombreBtn = document.getElementById('crearNombreBtn');
            const crearBarajaInput = document.getElementById('crearBaraja');

            guardarBarajaBtn.addEventListener('click', () => {

                if (barajas_select.options.length > 0) {

                    const selectElement = document.getElementById("barajas");
                    const selectedOption = selectElement.options[selectElement.selectedIndex];

                    const cardSlots = document.querySelectorAll(".card-slot");

                    const cards = [];

                    if (cardSlots.length > 0) {

                        cardSlots.forEach((slot, index) => {

                            const img = slot.querySelector("img");
                            const zone = slot.getAttribute("data-zona");

                            if (img) {
                                const name = img.getAttribute("data-name");
                                const type = img.getAttribute("data-type");
                                const url = img.getAttribute("src");

                                console.log("Procesando imagen:", { name, type, zone, url });

                                if (name && type && zone && url) {

                                    cards.push({ name, type, zone, url });
                                } else {

                                    console.log(`Faltan datos para la imagen en el slot ${index + 1}`);
                                }
                            }
                        });

                        console.log("Cartas extraídas:", cards);

                        cards.forEach((card) => {

                            console.log(`Nombre: ${card.name}, Tipo: ${card.type}, Zona: ${card.zone}, URL: ${card.url}`);
                        });
                    }
                    else {
                        console.log("No se encontraron imágenes dentro de los slots.");
                    }

                    guardarBaraja(clienteId, selectedOption.value, cards);

                } else {

                    guardarBarajaModal.style.display = 'flex';
                }
            });

            renombrarBarajaBtn.addEventListener('click', () => {

                renombrarBarajaModal.style.display = 'flex';

            });

            crearBarajaBtn.addEventListener('click', () => {

                crearBarajaModal.style.display = 'flex';

            });

            guardarNombreBtn.addEventListener('click', () => {

                const nombreBaraja = nombreBarajaInput.value.trim();

                if (nombreBaraja) {

                    alert(`Baraja guardada con nombre: ${nombreBaraja}`);
                    guardarBarajaModal.style.display = 'none';

                } else {

                    alert('Por favor, introduce un nombre para la baraja.');
                }
            });

            guardarBarajaModal.addEventListener('click', (event) => {

                if (event.target === guardarBarajaModal) {

                    guardarBarajaModal.style.display = 'none';
                }
            });

            document.getElementById("guardarNombreBtn").addEventListener("click", function () {

                var nombre = document.getElementById("nombreBaraja").value;

                const cardSlots = document.querySelectorAll(".card-slot");

                const cards = [];

                if (cardSlots.length > 0) {

                    cardSlots.forEach((slot, index) => {
                        const img = slot.querySelector("img");
                        const zone = slot.getAttribute("data-zona");

                        if (img) {
                            const name = img.getAttribute("data-name");
                            const type = img.getAttribute("data-type");
                            const url = img.getAttribute("src"); 

                            console.log("Procesando imagen:", { name, type, zone, url });

                            if (name && type && zone && url) {

                                cards.push({ name, type, zone, url });
                            } else {

                                console.log(`Faltan datos para la imagen en el slot ${index + 1}`);
                            }
                        }
                    });

                    console.log("Cartas extraídas:", cards);

                    cards.forEach((card) => {

                        console.log(`Nombre: ${card.name}, Tipo: ${card.type}, Zona: ${card.zone}, URL: ${card.url}`);
                    });
                }
                else {
                    console.log("No se encontraron imágenes dentro de los slots.");
                }

                guardarBaraja(clienteId, nombre, cards);
            });

            document.getElementById("boton_borrar").addEventListener("click", function () {

                const selectElement = document.getElementById("barajas");
                const selectedOption = selectElement.options[selectElement.selectedIndex];

                var formData = new FormData();
                formData.append('clienteId', clienteId);
                formData.append('nombre_baraja', selectedOption.value);

                $.ajax({
                    type: 'POST',
                    url: '/funciones/CUENTA/borrar_baraja.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        location.reload();
                    },
                    error: function () {
                        console.error("Error al obtener sugerencias.");
                    }
                })

            })

            renombrarNombreBtn.addEventListener('click', () => {

                const renombreBaraja = renombreBarajaInput.value.trim();

                if (renombreBaraja) {

                    alert(`Baraja guardada con nombre: ${renombreBaraja}`);
                    renombrarBarajaModal.style.display = 'none';

                } else {

                    alert('Por favor, introduce un nombre para la baraja.');
                }
            });

            renombrarBarajaModal.addEventListener('click', (event) => {

                if (event.target === renombrarBarajaModal) {

                    renombrarBarajaModal.style.display = 'none';
                }
            });

            document.getElementById("renombrarNombreBtn").addEventListener("click", function () {

                const selectElement = document.getElementById("barajas");
                const selectedOption = selectElement.options[selectElement.selectedIndex];

                if (barajas_select.options.length > 0) {

                    var nombre_baraja = document.getElementById("renombreBaraja").value;

                    var formData = new FormData();
                    formData.append('clienteId', clienteId);
                    formData.append('nombre_baraja', nombre_baraja);
                    formData.append('nombre_original', selectedOption.value);

                    $.ajax({
                        type: 'POST',
                        url: '/funciones/CUENTA/renombrar_baraja.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {

                            const data = JSON.parse(response);

                            if (data == "Ese nombre ya esta siendo utilizado, elija otro") {

                                if (confirm("Ese nombre ya esta siendo utilizado, elija otro")) {

                                    location.reload();
                                }
                            } else {
                                location.reload();
                            }

                        },
                        error: function () {

                            console.error("Error al obtener sugerencias.");
                        }
                    });


                } else {

                    if (confirm("Primero debe pulsar 'Guardar Baraja'")) {

                        location.reload();
                    }
                }

            });

            crearNombreBtn.addEventListener('click', () => {

                const crearBaraja = crearBarajaInput.value.trim();

                if (crearBaraja) {

                    alert(`Baraja guardada con nombre: ${crearBaraja}`);
                    crearBarajaModal.style.display = 'none';

                } else {

                    alert('Por favor, introduce un nombre para la baraja.');
                }
            });

            crearBarajaModal.addEventListener('click', (event) => {

                if (event.target === crearBarajaModal) {

                    crearBarajaModal.style.display = 'none';
                }
            });

            document.getElementById("crearNombreBtn").addEventListener("click", function () {

                const crearBaraja = document.getElementById("crearBaraja").value.trim();
            
                if (crearBaraja) {

                    const selectElement = document.getElementById("barajas");
            
                    const option = document.createElement("option");
                    option.value = crearBaraja;
                    option.textContent = crearBaraja;
                    selectElement.appendChild(option);
            
                    selectElement.value = crearBaraja;

                    const allSlots = document.querySelectorAll(".card-slot");

                    allSlots.forEach(slot => {

                        slot.innerHTML = ""; 
                    });
            
                    cargarCartas(clienteId, crearBaraja);

                } else {

                    console.error("El nombre de la baraja no puede estar vacío.");
                }
            });
            
            document.getElementById("barajas").addEventListener("change", function () {

                const allSlots = document.querySelectorAll(".card-slot");

                allSlots.forEach(slot => {

                    slot.innerHTML = ""; 
                });

                const selectElement = this;
                const selectedValue = selectElement.value;
            
                if (selectedValue) {

                    console.log(`Baraja seleccionada: ${selectedValue}`);
                    cargarCartas(clienteId, selectedValue);

                } else {
                    console.error("No hay una baraja seleccionada.");
                }
            });
            

            function guardarBaraja(clienteId, nombre_baraja, cartas_baraja) {

                var formData = new FormData();
                formData.append('clienteId', clienteId);
                formData.append('nombre_baraja', nombre_baraja);
                formData.append('cartas_baraja', JSON.stringify(cartas_baraja));

                $.ajax({
                    type: 'POST',
                    url: '/funciones/CUENTA/guardar_baraja.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        location.reload();
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

            document.getElementById("boton_imprimir").addEventListener("click", function(){

                const selectElement = document.getElementById("barajas");
                const selectedOption = selectElement.options[selectElement.selectedIndex];

                var formData = new FormData();
                formData.append('clienteId', clienteId);
                formData.append('nombre_baraja', selectedOption.value);

                $.ajax({
                    type: 'POST',
                    url: '/funciones/CUENTA/imprimir_baraja.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        const link = document.createElement('a');
                        link.href = response; 
                        link.download = 'DeckList.pdf';  
                        link.click();
                    },
                    error: function () {
                        
                        console.error("Error al obtener sugerencias.");
                    }
                });
            });
        }
    });
});