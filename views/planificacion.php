<?php include 'layout/header.php'; ?>
<?php include 'layout/navbar.php'; ?>


<div class="container mt-4">
    <h2 class="text-danger text-center">Planificación de Producción</h2>

    <!-- Sección de Creación de Orden de Fabricación -->
    <div class="card p-3 mb-3">
        <h4>Crear Orden de Fabricación</h4>
        <form id="crearOrdenForm">
            <div class="row">
                <div class="col-md-4">
                    <label>Modelo</label>
                    <select id="modelo" class="form-control" required>
                        <option value="">Seleccione...</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Orden de Fabricación</label>
                    <input type="number" id="ordenf" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Ruta</label>
                    <select id="idruta" class="form-control" required>
                        <option value="">Seleccione...</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Añadir Orden</button>
        </form>
    </div>

    <!-- Tabla de Órdenes de Fabricación -->
    <div class="card p-3">
        <h4>Órdenes de Fabricación</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Modelo</th>
                    <th>Orden Fabricación</th>
                    <th>Ruta</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="ordenesTabla">
                <!-- Se llenará con AJAX -->
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    cargarOrdenes();
    cargarModelos();
    cargarRutas();

    // Enviar formulario de creación de orden
    $("#crearOrdenForm").submit(function (e) {
        e.preventDefault();

        $.post("planificacion/crear", {
            modelo: $("#modelo").val(),
            ordenf: $("#ordenf").val(),
            idruta: $("#idruta").val()
        }, function (response) {
            cargarOrdenes();
            $("#crearOrdenForm")[0].reset();
        }, "json");
    });

    // Finalizar orden
    $(document).on("click", ".finalizar-btn", function () {
        let id = $(this).data("id");

        $.post("planificacion/finalizar", { idOrden: id }, function (response) {
            cargarOrdenes();
        }, "json");
    });

    // Eliminar orden
    $(document).on("click", ".eliminar-btn", function () {
        let id = $(this).data("id");

        if (confirm("¿Seguro que quieres eliminar esta orden?")) {
            $.post("planificacion/eliminar", { idOrden: id }, function (response) {
                cargarOrdenes();
            }, "json");
        }
    });

    function cargarOrdenes() {
        $.get("planificacion/ordenes", function (data) {
            let ordenes = JSON.parse(data);
            let html = "";

            ordenes.forEach(o => {
                html += `
                    <tr>
                        <td>${o.id}</td>
                        <td>${o.nombreColchon}</td>
                        <td>${o.ordenf}</td>
                        <td>${o.ruta}</td>
                        <td>${o.terminado}</td>
                        <td>
                            <button class="btn btn-success btn-sm finalizar-btn" data-id="${o.id}">Finalizar</button>
                            <button class="btn btn-danger btn-sm eliminar-btn" data-id="${o.id}">Eliminar</button>
                        </td>
                    </tr>`;
            });

            $("#ordenesTabla").html(html);
        });
    }

    function cargarModelos() {
        $.get("planificacion/modelos", function (data) {
            let modelos = JSON.parse(data);
            modelos.forEach(m => {
                $("#modelo").append(`<option value="${m.idColchon}">${m.nombreColchon}</option>`);
            });
        });
    }

    function cargarRutas() {
        $.get("planificacion/rutas", function (data) {
            let rutas = JSON.parse(data);
            rutas.forEach(r => {
                $("#idruta").append(`<option value="${r.idruta}">${r.ruta}</option>`);
            });
        });
    }
});
</script>

<?php include 'layout/footer.php'; ?>
