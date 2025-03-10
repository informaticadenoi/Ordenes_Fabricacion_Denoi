<?php include 'layout/header.php'; ?>
<?php include 'layout/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center text-danger">Gestión de Hojas de Ruta</h2>

    <!-- Botón para agregar nueva hoja de ruta -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarRuta">
        + Añadir Hoja de Ruta
    </button>

    <!-- Acordeón para mostrar las hojas de ruta -->
    <div class="accordion" id="hojasRutasAccordion">
        <!-- Se llenará con AJAX -->
    </div>
</div>

<<!-- MODAL PARA AGREGAR TRABAJO -->
    <div class="modal fade" id="modalAgregarTrabajo" tabindex="-1" aria-labelledby="modalAgregarTrabajoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarTrabajoLabel">Añadir Trabajo a la Hoja de Ruta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarTrabajo">
                        <input type="hidden" id="idHojaRuta" name="idHojaRuta">

                        <div class="mb-3">
                            <label for="codigoTrabajo" class="form-label">Seleccionar Trabajo</label>
                            <select class="form-select" id="codigoTrabajo" name="codigoTrabajo" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="codigoMaquina" class="form-label">Seleccionar Máquina</label>
                            <select class="form-select" id="codigoMaquina" name="codigoMaquina" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="ordenTrabajo" class="form-label">Orden de Trabajo</label>
                            <input type="number" class="form-control" id="ordenTrabajo" name="ordenTrabajo" required
                                min="1">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Trabajo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL PARA AGREGAR NUEVA HOJA DE RUTA -->
    <div class="modal fade" id="modalAgregarRuta" tabindex="-1" aria-labelledby="modalAgregarRutaLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarRutaLabel">Añadir Nueva Hoja de Ruta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarRuta">
                        <div class="mb-3">
                            <label for="numeroPlantilla" class="form-label">Número de Plantilla</label>
                            <input type="number" class="form-control" id="numeroPlantilla" name="numero_plantilla"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Ruta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA EDITAR TRABAJO -->
    <div class="modal fade" id="modalEditarTrabajo" tabindex="-1" aria-labelledby="modalEditarTrabajoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarTrabajoLabel">Editar Trabajo en Hoja de Ruta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarTrabajo">
                        <input type="hidden" id="editIdHojaRuta" name="idHojaRuta">
                        <input type="hidden" id="editCodigoTrabajo" name="codigoTrabajo">

                        <div class="mb-3">
                            <label for="editCodigoMaquina" class="form-label">Seleccionar Máquina</label>
                            <select class="form-select" id="editCodigoMaquina" name="codigoMaquina" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="editOrdenTrabajo" class="form-label">Orden de Trabajo</label>
                            <input type="number" class="form-control" id="editOrdenTrabajo" name="orden" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {

            // ✅ Cargar hojas de ruta
            function cargarHojasRutas() {
                $.get("?url=ruta/obtenerRutas", function (data) {
                    let rutas = data;
                    let accordion = $("#hojasRutasAccordion");
                    accordion.empty();

                    rutas.forEach((ruta, index) => {
                        let idCollapse = `collapse${ruta.id}`;
                        accordion.append(`
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading${ruta.id}">
                            <button class="accordion-button ${index === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#${idCollapse}">
                                ${ruta.descripcion} (Plantilla: ${ruta.numero_plantilla})
                            </button>
                        </h2>
                        <div id="${idCollapse}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" data-bs-parent="#hojasRutasAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Orden</th>
                                            <th>Trabajo</th>
                                            <th>Máquina</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="trabajosRuta${ruta.id}">
                                        <!-- Trabajos se cargarán con AJAX -->
                                    </tbody>
                                </table>
                                <button class="btn btn-primary btn-sm mt-2 agregar-trabajo" data-id="${ruta.id}">
                                    + Añadir Trabajo
                                </button>
                            </div>
                        </div>
                    </div>
                `);

                        // Cargar trabajos de cada hoja de ruta
                        cargarTrabajosPorHoja(ruta.id);
                    });
                });
            }

            // ✅ Cargar trabajos por hoja de ruta con botones de editar y eliminar
            function cargarTrabajosPorHoja(idHojaRuta) {
                $.get("?url=ruta/obtenerTrabajosPorHoja&id=" + idHojaRuta, function (data) {
                    let trabajos = data;
                    let tabla = $(`#trabajosRuta${idHojaRuta}`);
                    tabla.empty();

                    trabajos.forEach((trabajo) => {
                        tabla.append(`
                <tr>
                    <td>${trabajo.orden}</td>
                    <td>${trabajo.nombre_trabajo}</td>
                    <td>${trabajo.nombre_maquina || 'No asignada'}</td>
                    <td>
                        <button class="btn btn-warning btn-sm editar-trabajo" 
                                data-id-hoja="${idHojaRuta}" 
                                data-codigo-trabajo="${trabajo.codigo_trabajo}" 
                                data-maquina="${trabajo.codigo_maquina}" 
                                data-orden="${trabajo.orden}">
                            ✏ Editar
                        </button>
                        <button class="btn btn-danger btn-sm eliminar-trabajo" 
                                data-id-hoja="${idHojaRuta}" 
                                data-codigo-trabajo="${trabajo.codigo_trabajo}">
                            ❌ Eliminar
                        </button>
                    </td>
                </tr>
            `);
                    });
                }).fail(function (xhr, status, error) {
                    console.error("❌ Error al cargar trabajos:", xhr.responseText);
                });
            }




            // ✅ Mostrar modal para añadir trabajo
            $(document).ready(function () {
                // ✅ Mostrar modal para añadir trabajo
                $(document).on("click", ".agregar-trabajo", function () {
                    let idHojaRuta = $(this).data("id");
                    $("#idHojaRuta").val(idHojaRuta);

                    // 🟢 Cargar trabajos dinámicamente
                    $.get("?url=trabajos/obtenerTrabajos", function (data) {
                        let trabajos = typeof data === "string" ? JSON.parse(data) : data;
                        let selectTrabajo = $("#codigoTrabajo");
                        selectTrabajo.empty();
                        selectTrabajo.append('<option value="">Seleccione un trabajo</option>');
                        trabajos.forEach((trabajo) => {
                            selectTrabajo.append(`<option value="${trabajo.codigo_trabajo}">${trabajo.nombre_trabajo}</option>`);
                        });
                    });

                    // 🟢 Cargar máquinas dinámicamente
                    $.get("?url=trabajos/obtenerMaquinas", function (data) {
                        let maquinas = typeof data === "string" ? JSON.parse(data) : data;
                        let selectMaquina = $("#codigoMaquina");
                        selectMaquina.empty();
                        selectMaquina.append('<option value="">Seleccione una máquina</option>');
                        maquinas.forEach((maquina) => {
                            selectMaquina.append(`<option value="${maquina.codigo}">${maquina.nombre}</option>`);
                        });
                    });

                    $("#modalAgregarTrabajo").modal("show");
                });

                // ✅ Guardar nuevo trabajo en la hoja de ruta
                $("#formAgregarTrabajo").submit(function (e) {
                    e.preventDefault();

                    $.post("?url=ruta/agregarTrabajoARuta", $(this).serialize(), function (response) {
                        console.log(response);
                        let data = typeof response === "string" ? JSON.parse(response) : response;

                        if (data.success) {
                            alert("Trabajo agregado correctamente");
                            $("#modalAgregarTrabajo").modal("hide");

                            let idHojaRuta = $("#idHojaRuta").val();
                            cargarTrabajosPorHoja(idHojaRuta);
                        } else {
                            alert("Error: " + data.error);
                        }
                    });
                });
            });
            // ✅ Mostrar modal con datos del trabajo a editar
            $(document).on("click", ".editar-trabajo", function () {
                let idHojaRuta = $(this).data("id-hoja");
                let codigoTrabajo = $(this).data("codigo-trabajo");
                let codigoMaquina = $(this).data("maquina");
                let ordenTrabajo = $(this).data("orden");

                $("#editIdHojaRuta").val(idHojaRuta);
                $("#editCodigoTrabajo").val(codigoTrabajo);
                $("#editOrdenTrabajo").val(ordenTrabajo);

                // Cargar lista de máquinas en el select
                $.get("?url=trabajos/obtenerMaquinas", function (data) {
                    let maquinas = data;
                    let select = $("#editCodigoMaquina");
                    select.empty();

                    maquinas.forEach((m) => {
                        let selected = m.codigo == codigoMaquina ? "selected" : "";
                        select.append(`<option value="${m.codigo}" ${selected}>${m.nombre}</option>`);
                    });

                    $("#modalEditarTrabajo").modal("show");
                });
            });


            // ✅ Guardar edición
            $("#formEditarTrabajo").submit(function (e) {
                e.preventDefault();
                $.post("?url=ruta/editarTrabajoEnHoja", $(this).serialize(), function () {
                    $("#modalEditarTrabajo").modal("hide");
                    let idHojaRuta = $("#editIdHojaRuta").val();
                    cargarTrabajosPorHoja(idHojaRuta);
                });
            });
            // ✅ Eliminar trabajo
            $(document).on("click", ".eliminar-trabajo", function () {
                if (confirm("¿Estás seguro de que quieres eliminar este trabajo?")) {
                    let idHojaRuta = $(this).data("id-hoja");
                    let codigoTrabajo = $(this).data("codigo-trabajo");
                    console.log(idHojaRuta, codigoTrabajo);
                    $.post("?url=ruta/eliminarTrabajoDeHoja", { idHojaRuta, codigoTrabajo }, function () {
                        cargarTrabajosPorHoja(idHojaRuta);
                    });
                }
            });


            cargarHojasRutas(); // Cargar las hojas de ruta al inicio
        });
    </script>

    <?php include 'layout/footer.php'; ?>