<?php


class PlanificacionController {
    // Mostrar vista de planificacion
    public function index() {
        require_once "../views/planificacion.php";
    }

     // Crear nueva orden de fabricación
     public function crearOrden() {
        global $conn; // Usar la conexión global

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $modelo = $_POST["modelo"];
            $ordenf = $_POST["ordenf"];
            $idruta = $_POST["idruta"];

            // Insertar en la base de datos
            $stmt = $conn->prepare("INSERT INTO tplan (idmodelo, ordenf, idruta) VALUES (?, ?, ?)");
            $stmt->execute([$modelo, $ordenf, $idruta]);

            echo json_encode(["success" => true]);
            exit();
        }
    }

    // Eliminar orden de fabricación
    public function eliminarOrden() {
        global $conn;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idOrden = $_POST["idOrden"];

            $stmt = $conn->prepare("DELETE FROM tplan WHERE id = ?");
            $stmt->execute([$idOrden]);

            echo json_encode(["success" => true]);
            exit();
        }
    }

    // Obtener órdenes de fabricación
    public function obtenerOrdenes() {
        global $conn;
        $stmt = $conn->query("
            SELECT t.id, c.nombreColchon, t.ordenf, r.ruta, t.terminado
            FROM tplan t
            LEFT JOIN colchones c ON t.idmodelo = c.idColchon
            LEFT JOIN ruta r ON t.idruta = r.idruta
            ORDER BY t.ordenf ASC
        ");
        $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($ordenes);
        exit();
    }

    // Marcar orden como finalizada
    public function finalizarOrden() {
        global $conn;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idOrden = $_POST["idOrden"];

            $stmt = $conn->prepare("UPDATE tplan SET terminado = 'SI' WHERE id = ?");
            $stmt->execute([$idOrden]);

            echo json_encode(["success" => true]);
            exit();
        }
    }
}