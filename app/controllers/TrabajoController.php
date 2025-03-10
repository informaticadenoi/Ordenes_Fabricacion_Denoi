<?php
require_once "../config/database.php";

class TrabajoController {
    // Mostrar vista de trabajos
    public function index() {
        require_once "../views/trabajos.php";
    }

    // Crear nuevo trabajo y asignarlo a una máquina
    public function crearTrabajo() {
        global $conn;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $codigoTrabajo = $_POST["codigoTrabajo"];
            $nombreTrabajo = $_POST["nombreTrabajo"];
            $codigoMaquina = $_POST["codigoMaquina"];

            // Insertar en la base de datos
            $stmt = $conn->prepare("INSERT INTO trabajos (codigo_trabajo, nombre_trabajo) VALUES (?, ?)");
            $stmt->execute([$codigoTrabajo, $nombreTrabajo]);

            // Insertar en la tabla de relaciones con máquinas
            $stmt = $conn->prepare("INSERT INTO trabajo_maquina (codigo_trabajo, codigo_maquina) VALUES (?, ?)");
            $stmt->execute([$codigoTrabajo, $codigoMaquina]);

            echo json_encode(["success" => true]);
            exit();
        }
    }

    // Eliminar un trabajo
    public function eliminarTrabajo() {
        global $conn;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $codigoTrabajo = $_POST["codigoTrabajo"];

            // Eliminar de la tabla de relaciones primero
            $stmt = $conn->prepare("DELETE FROM trabajo_maquina WHERE codigo_trabajo = ?");
            $stmt->execute([$codigoTrabajo]);

            // Luego eliminar el trabajo
            $stmt = $conn->prepare("DELETE FROM trabajos WHERE codigo_trabajo = ?");
            $stmt->execute([$codigoTrabajo]);

            echo json_encode(["success" => true]);
            exit();
        }
    }

    // Obtener lista de trabajos
    public function obtenerTrabajos() {
        global $conn;

        $stmt = $conn->query("
            SELECT t.codigo_trabajo, t.nombre_trabajo, m.nombre AS nombre_maquina
            FROM trabajos t
            LEFT JOIN trabajo_maquina tm ON t.codigo_trabajo = tm.codigo_trabajo
            LEFT JOIN maquinas m ON tm.codigo_maquina = m.codigo
            ORDER BY t.nombre_trabajo ASC
        ");
        $trabajos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($trabajos);
        exit();
    }

    // Editar trabajo (opcional)
    public function actualizarTrabajo() {
        global $conn;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $codigoTrabajo = $_POST["codigoTrabajo"];
            $nuevoNombre = $_POST["nuevoNombre"];
            $nuevaMaquina = $_POST["nuevaMaquina"];

            // Actualizar nombre del trabajo
            $stmt = $conn->prepare("UPDATE trabajos SET nombre_trabajo = ? WHERE codigo_trabajo = ?");
            $stmt->execute([$nuevoNombre, $codigoTrabajo]);

            // Actualizar máquina asignada
            $stmt = $conn->prepare("UPDATE trabajo_maquina SET codigo_maquina = ? WHERE codigo_trabajo = ?");
            $stmt->execute([$nuevaMaquina, $codigoTrabajo]);

            echo json_encode(["success" => true]);
            exit();
        }
    }
}
