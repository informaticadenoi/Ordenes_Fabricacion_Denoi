<?php include 'layout/header.php'; ?>
<?php include 'layout/navbar.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 bg-light vh-100 p-3">
            <h5 class="text-center text-danger">Gestión de Trabajos</h5>
            <button class="btn btn-success w-100 mb-3" data-bs-toggle="modal" data-bs-target="#agregarTrabajoModal">
                + Añadir Trabajo
            </button>
        </div>

        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="text-danger text-center">Listado de Trabajos</h2>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre Trabajo</th>
                        <th>Máquina Asignada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaTrabajos">
                    <!-- Se llenará con AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para agregar trabajo -->
<div class="modal fade" id="agregarTrabajoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Añadir Nuevo Trabajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarTrabajo">
                    <div class="mb-3">
                        <label for="codigoTrabajo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigoTrabajo" name="codigoTrabajo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombreTrabajo" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreTrabajo" name="nombreTrabajo" required>
                    </div>
                    <div class="mb-3">
                        <label for="codigoMaquina" class="form-label">Máquina</label>
                        <select class="form-select" id="codigoMaquina" name="codigoMaquina" required></select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal para editar trabajo -->
<div class="modal fade" id="editarTrabajoModal" tabindex="-1" aria-labelledby="editarTrabajoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarTrabajoModalLabel">Editar Trabajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarTrabajo">
                    <input type="hidden" id="editCodigoTrabajo" name="codigoTrabajo">

                    <div class="mb-3">
                        <label for="editNombreTrabajo" class="form-label">Nombre del Trabajo</label>
                        <input type="text" class="form-control" id="editNombreTrabajo" name="nombreTrabajo" required>
                    </div>

                    <div class="mb-3">
                        <label for="editCodigoMaquina" class="form-label">Máquina Asignada</label>
                        <select class="form-select" id="editCodigoMaquina" name="codigoMaquina" required>
                            <option value="">Seleccione una máquina</option>
                            <!-- Opciones dinámicas desde JS -->
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar Trabajo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- AJAX -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // ✅ Cargar trabajos desde la base de datos
        function cargarTrabajos() {
    $.get("?url=trabajos/obtenerTrabajos", function (data) {
        console.log("✅ Trabajos recibidos:", data);

        let tabla = $("#tablaTrabajos");
        tabla.empty();

        data.forEach((trabajo) => {
            let maquina = trabajo.nombre_maquina ? trabajo.nombre_maquina : "No asignada";

            tabla.append(`
                <tr>
                    <td>${trabajo.codigo_trabajo}</td>
                    <td>${trabajo.nombre_trabajo}</td>
                    <td>${maquina}</td>
                    <td>
                        <button class="btn btn-warning btn-sm editar-trabajo" 
                            data-codigo="${trabajo.codigo_trabajo}" 
                            data-nombre="${trabajo.nombre_trabajo}"
                            data-maquina="${trabajo.codigo_maquina || ''}">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm eliminar-trabajo" data-codigo="${trabajo.codigo_trabajo}">
                            Eliminar
                        </button>
                    </td>
                </tr>
            `);
        });

        console.log("✅ Tabla de trabajos actualizada.");
    }).fail(function (xhr, status, error) {
        console.error("❌ Error al cargar trabajos:", error);
    });
}


        // ✅ Cargar lista de máquinas
        function cargarMaquinas() {
            $.get("?url=trabajos/obtenerMaquinas", function (data) {
                console.log("✅ Máquinas recibidas:", data);

                let select = $("#codigoMaquina");
                select.empty();
                select.append('<option value="">Seleccione una máquina</option>');

                data.forEach((m) => {
                    select.append(`<option value="${m.codigo}">${m.nombre}</option>`);
                });

                console.log("✅ Opciones de máquinas agregadas correctamente.");
            }).fail(function (xhr, status, error) {
                console.error("❌ Error al cargar máquinas:", error);
            });
        }

        // ✅ Función para agregar un nuevo trabajo
        $("#formAgregarTrabajo").submit(function (e) {
            e.preventDefault();

            $.post("?url=trabajos/crearTrabajo", $(this).serialize(), function (response) {
                console.log("✅ Trabajo agregado:", response);

                $("#agregarTrabajoModal").modal("hide"); // Cerrar modal
                $("#formAgregarTrabajo")[0].reset(); // Limpiar formulario
                cargarTrabajos(); // Recargar la tabla

            }).fail(function (xhr, status, error) {
                console.error("❌ Error al agregar trabajo:", error);
            });
        });
        
        $("#formEditarTrabajo").submit(function (e) {
    e.preventDefault();

    $.post("?url=trabajos/editarTrabajo", $(this).serialize(), function (response) {
        console.log("✅ Trabajo editado:", response);

        $("#editarTrabajoModal").modal("hide"); // Cerrar modal
        cargarTrabajos(); // Recargar la lista de trabajos

    }).fail(function (xhr, status, error) {
        console.error("❌ Error al editar trabajo:", error);
    });
});

        // ✅ Función para eliminar un trabajo
        $(document).on("click", ".eliminar-trabajo", function () {
            let codigo = $(this).data("codigo");

            if (confirm("¿Seguro que deseas eliminar este trabajo?")) {
                $.post("?url=trabajos/eliminarTrabajo", { codigoTrabajo: codigo }, function (response) {
                    console.log("✅ Trabajo eliminado:", response);
                    cargarTrabajos(); // Recargar tabla de trabajos
                }).fail(function (xhr, status, error) {
                    console.error("❌ Error al eliminar trabajo:", error);
                });
            }
        });

        $(document).on("click", ".editar-trabajo", function () {
    let codigo = $(this).data("codigo");
    let nombre = $(this).data("nombre");
    let maquina = $(this).data("maquina");

    $("#editCodigoTrabajo").val(codigo);
    $("#editNombreTrabajo").val(nombre);

    // Cargar máquinas en el select y preseleccionar la actual
    $.get("?url=trabajos/obtenerMaquinas", function (data) {
        let select = $("#editCodigoMaquina");
        select.empty();
        select.append('<option value="">Seleccione una máquina</option>');

        data.forEach((m) => {
            let selected = (m.codigo == maquina) ? "selected" : "";
            select.append(`<option value="${m.codigo}" ${selected}>${m.codigo}</option>`);
        });
    });

    $("#editarTrabajoModal").modal("show"); // Abrir modal
});


        // 🔄 Llamamos a las funciones para cargar los datos al iniciar la página
        cargarTrabajos();
        cargarMaquinas();
    });
</script>


<?php include 'layout/footer.php'; ?>