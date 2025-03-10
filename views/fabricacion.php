<?php include 'layout/header.php'; ?>
<?php include 'layout/navbar.php'; ?>

<?php require_once "../config/database.php"; // Conexión a la BD ?>

<?php
// Obtener los colchones y sus tiempos de fabricación
$stmt = $conn->query("
    SELECT r.idruta, r.ruta, c.idColchon, c.nombreColchon, t.ordenf, t.inicio_tiempo, t.fin_tiempo
    FROM ruta r
    LEFT JOIN colchones c ON r.idruta = c.ruta
    LEFT JOIN tplan t ON c.idColchon = t.idmodelo
    ORDER BY r.idruta, c.nombreColchon
");
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar los datos por ruta
$rutas = [];
foreach ($datos as $fila) {
    if (!isset($rutas[$fila['idruta']])) {
        $rutas[$fila['idruta']] = [
            'nombre' => $fila['ruta'],
            'colchones' => []
        ];
    }

    $rutas[$fila['idruta']]['colchones'][] = [
        'idColchon' => $fila['idColchon'],
        'nombreColchon' => $fila['nombreColchon'],
        'ordenf' => $fila['ordenf'] ?? 'N/A',
        'inicio_tiempo' => $fila['inicio_tiempo'],
        'fin_tiempo' => $fila['fin_tiempo']
    ];
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Menú lateral -->
        <div class="col-md-3 col-lg-2 bg-light vh-100 p-3">
            <h5 class="text-center text-danger">Rutas</h5>
            <div class="list-group">
                <?php $first = true; ?>
                <?php foreach ($rutas as $idruta => $info): ?>
                    <a href="#" class="list-group-item list-group-item-action <?= $first ? 'active' : '' ?>" 
                       data-tab="<?= $idruta ?>">
                        <?= $info['nombre'] ?>
                    </a>
                    <?php $first = false; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="text-danger text-center">ÓRDENES DE FABRICACIÓN</h2>
            
            <div class="tab-content">
                <?php $first = true; ?>
                <?php foreach ($rutas as $idruta => $info): ?>
                    <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="ruta-<?= $idruta ?>">
                        <h4 class="text-primary"><?= $info['nombre'] ?></h4>
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Colchón</th>
                                    <th>Orden de Fabricación</th>
                                    <th>Tiempo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($info['colchones'] as $colchon): ?>
                                    <tr>
                                        <td><?= $colchon['idColchon'] ?></td>
                                        <td><?= $colchon['nombreColchon'] ?></td>
                                        <td><?= $colchon['ordenf'] ?></td>
                                        <td>
                                            <?php if ($colchon['inicio_tiempo'] && !$colchon['fin_tiempo']): ?>
                                                <span class="timer" id="timer-<?= $colchon['idColchon'] ?>"
                                                      data-start-time="<?= $colchon['inicio_tiempo'] ?>">0:00</span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="colchon/<?= $colchon['idColchon'] ?>" class="btn btn-primary btn-sm">
                                                Ver Colchón
                                            </a>

                                            <?php if (empty($colchon['inicio_tiempo'])): ?>
                                                <!-- Botón de iniciar -->
                                                <button class="btn btn-success btn-sm start-btn" data-id="<?= $colchon['idColchon'] ?>">
                                                    Iniciar
                                                </button>
                                            <?php elseif (empty($colchon['fin_tiempo'])): ?>
                                                <!-- Botón de finalizar -->
                                                <button class="btn btn-danger btn-sm stop-btn" data-id="<?= $colchon['idColchon'] ?>">
                                                    Finalizar
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php $first = false; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Iniciar contador
    $(".start-btn").click(function () {
        let id = $(this).data("id");

        $.post("../config/procesar_tiempo.php", { idColchon: id, accion: "iniciar" }, function (response) {
            location.reload();
        });
    });

    // Finalizar contador
    $(".stop-btn").click(function () {
        let id = $(this).data("id");

        $.post("../config/procesar_tiempo.php", { idColchon: id, accion: "finalizar" }, function (response) {
            location.reload();
        });
    });

    // Actualizar contadores en vivo
    setInterval(function () {
        $(".timer").each(function () {
            let startTime = new Date($(this).data("start-time"));
            let now = new Date();
            let diff = Math.floor((now - startTime) / 1000);
            let minutes = Math.floor(diff / 60);
            let seconds = diff % 60;
            $(this).text(minutes + ":" + (seconds < 10 ? "0" : "") + seconds);
        });
    }, 1000);
});
</script>
<script>
$(document).ready(function () {
    // Cargar la última pestaña activa desde localStorage
    let activeTab = localStorage.getItem("activeTab");
    if (activeTab) {
        $(".list-group-item").removeClass("active");
        $(".tab-pane").removeClass("show active");
        $(".list-group-item[data-tab='" + activeTab + "']").addClass("active");
        $("#ruta-" + activeTab).addClass("show active");
    } else {
        $(".list-group-item").first().addClass("active");
        $(".tab-pane").first().addClass("show active");
    }

    // Cambiar entre pestañas y guardar en localStorage
    $(".list-group-item").click(function (e) {
        e.preventDefault();
        $(".list-group-item").removeClass("active");
        $(this).addClass("active");

        let idruta = $(this).data("tab");
        $(".tab-pane").removeClass("show active");
        $("#ruta-" + idruta).addClass("show active");

        localStorage.setItem("activeTab", idruta); // Guardar pestaña activa
    });

    // Iniciar contador
    $(".start-btn").click(function () {
        let id = $(this).data("id");
        let activeTab = localStorage.getItem("activeTab"); // Guardar pestaña actual

        $.post("../config/procesar_tiempo.php", { idColchon: id, accion: "iniciar" }, function (response) {
            localStorage.setItem("activeTab", activeTab); // Restaurar pestaña activa después de recargar
            location.reload();
        });
    });

    // Finalizar contador
    $(".stop-btn").click(function () {
        let id = $(this).data("id");
        let activeTab = localStorage.getItem("activeTab"); // Guardar pestaña actual

        $.post("../config/procesar_tiempo.php", { idColchon: id, accion: "finalizar" }, function (response) {
            localStorage.setItem("activeTab", activeTab); // Restaurar pestaña activa después de recargar
            location.reload();
        });
    });

    // Actualizar contadores en vivo
    setInterval(function () {
        $(".timer").each(function () {
            let startTime = new Date($(this).data("start-time"));
            let now = new Date();
            let diff = Math.floor((now - startTime) / 1000);
            let minutes = Math.floor(diff / 60);
            let seconds = diff % 60;
            $(this).text(minutes + ":" + (seconds < 10 ? "0" : "") + seconds);
        });
    }, 1000);
});
</script>


<?php include 'layout/footer.php'; ?>
