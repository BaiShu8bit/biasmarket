if (!localStorage.getItem("clienteId")) {
    location.href = "../INDEX/index.php";
} else {

    var clienteId = localStorage.getItem("clienteId");

    $.ajax({
        url: '/funciones/VENDER/datos_tablas.php',
        type: 'POST',
        data: { "clienteId": clienteId },
        success: function (response) {
            try {
                const datos = JSON.parse(response);
                console.log(datos); // Verifica que los datos estén bien formateados.
    
                const pedidosUnicos = {};
    
                // Procesar los datos para crear un objeto único por pedidoId
                datos.forEach(item => {
                    if (!pedidosUnicos[item.ventaId]) {
                        const fechaOriginal = new Date(item.fecha_venta);
                        const fechaFormateada = `${fechaOriginal.getDate().toString().padStart(2, '0')}/${(fechaOriginal.getMonth() + 1).toString().padStart(2, '0')}/${fechaOriginal.getFullYear()} ${fechaOriginal.getHours().toString().padStart(2, '0')}:${fechaOriginal.getMinutes().toString().padStart(2, '0')}`;
    
                        const certificado = item.gastos_envio === "5€" ? "Sí" : "No";
    
                        pedidosUnicos[item.ventaId] = {
                            id: item.pedidoId,
                            Fecha: fechaFormateada,
                            Comprador: item.nombre_usuarioC,
                            Certificado: certificado,
                            Total: item.total_pedido,
                            estado: item.estado_pedido
                        };
                    }
                });
    
                document.querySelectorAll('.filter-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        // Cambiar estado de botones de filtro
                        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
    
                        const filter = this.getAttribute('data-filter');
                        const tableBody = document.getElementById('table-body');
                        tableBody.innerHTML = ''; // Limpiar la tabla
    
                        // Filtrar los pedidos según el estado
                        const dataFiltrada = Object.values(pedidosUnicos).filter(item => {

                            if (filter === 'paid') return item.estado === "pagado";
                            if (filter === 'sent') return item.estado === "enviado";
                            if (filter === 'received') return item.estado === "recibido";
                            return true; // Si no se filtra, mostrar todo
                        });
    
                        // Mostrar los datos filtrados en la tabla
                        dataFiltrada.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td><a href="./detalle_venta.php?item=${row.id}" class="a_tabla">${row.id}</a></td>
                                <td>${row.Fecha}</td>
                                <td>${row.Comprador}</td>
                                <td>${row.Certificado}</td>
                                <td>${row.Total}</td>
                            `;
                            tableBody.appendChild(tr);
                        });
                    });
                });
    
                // Simular clic en el primer botón de filtro al cargar
                document.querySelector('.filter-btn.active').click();
            } catch (error) {
                console.error("Error procesando los datos:", error);
            }
        },
        error: function () {
            console.error("Error al cargar contenido dinámico");
        }
    });
    
}