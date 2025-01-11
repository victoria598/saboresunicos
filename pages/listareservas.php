<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sabores Únicos</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .nav-link {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-link:hover {
            background-color: #0a4b8d;
            color: #a8afb6;
        }

        .nav-link.active {
            background-color: #495057 !important;
            color: #ffffff !important;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sabores Únicos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto" id="navmenu">
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="container mt-5 pt-5">
            <h2 class="text-center mb-4">Lista de Reservas</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Número de Personas</th>
                        <th>Comentarios</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $file = '../database/reservas.json';
                        $reservas = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
                        
                        foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo $reserva['id']; ?></td>
                            <td><?php echo $reserva['nombre']; ?></td>
                            <td><?php echo $reserva['fecha']; ?></td>
                            <td><?php echo $reserva['hora']; ?></td>
                            <td><?php echo $reserva['personas']; ?></td>
                            <td><?php echo $reserva['comentarios']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick="CargarDatos(<?php echo $reserva['id']; ?>)">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="EliminarDatos(<?php echo $reserva['id']; ?>)">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar Reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" id="editId">
                            <div class="mb-3">
                                <label for="editNombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="editNombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="editFecha" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="editFecha" required>
                            </div>
                            <div class="mb-3">
                                <label for="editHora" class="form-label">Hora</label>
                                <input type="time" class="form-control" id="editHora" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPersonas" class="form-label">Número de Personas</label>
                                <input type="number" class="form-control" id="editPersonas" required>
                            </div>
                            <div class="mb-3">
                                <label for="editComentarios" class="form-label">Comentarios</label>
                                <textarea class="form-control" id="editComentarios" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    
    
    
    <script>
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../assets/xml/menu.xml", true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                const xmlDoc = xhr.responseXML;
                const items = xmlDoc.getElementsByTagName("item");
                let menuHTML = "";
                for (let i = 0; i < items.length; i++) {
                    const nombre = items[i].getElementsByTagName("nombre")[0].textContent;
                    const link = items[i].getElementsByTagName("link")[0].textContent;
                    menuHTML += `<li class="nav-item">
                                    <a class="nav-link" href="${link}">${nombre}</a>
                                </li>`;
                }
                document.getElementById("navmenu").innerHTML = menuHTML;

                const navLinks = document.querySelectorAll("#navmenu .nav-link");
                navLinks.forEach(link => {
                    link.addEventListener("click", function () {
                        navLinks.forEach(nav => nav.classList.remove("active"));
                        this.classList.add("active");
                    });
                });
            } else {
                console.error("Error al cargar el XML");
            }
        };
        xhr.send();
    </script>
    <script>
        function CargarDatos(id) {
            $.ajax({
                url: '../controller/obtenerdatos.php',
                type: 'GET',
                data: { id: id },
                success: function(response) {
                    const reserva = JSON.parse(response);
                    $('#editId').val(reserva.id);
                    $('#editNombre').val(reserva.nombre);
                    $('#editFecha').val(reserva.fecha);
                    $('#editHora').val(reserva.hora);
                    $('#editPersonas').val(reserva.personas);
                    $('#editComentarios').val(reserva.comentarios);
                }
            });
        }
    </script>
    <script>
        $('#editForm').on('submit', function(event) {
            event.preventDefault();
            const reservaData = {
                id: $('#editId').val(),
                nombre: $('#editNombre').val(),
                fecha: $('#editFecha').val(),
                hora: $('#editHora').val(),
                personas: $('#editPersonas').val(),
                comentarios: $('#editComentarios').val()
            };

            $.ajax({
                url: '../controller/editar.php',
                type: 'POST',
                data: { reserva: JSON.stringify(reservaData) },
                success: function(response) {
                    if (response === 'success') {
                        alert('Reserva actualizada correctamente');
                        location.reload();
                    } else {
                        alert('Hubo un error al actualizar la reserva');
                    }
                }
            });
        });
    </script>
    <script>
        function EliminarDatos(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                $.ajax({
                    url: '../controller/eliminar.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response === 'success') {
                            alert('Reserva eliminada con éxito');
                            location.reload();
                        } else {
                            alert('Error. No se eliminó la reserva');
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>