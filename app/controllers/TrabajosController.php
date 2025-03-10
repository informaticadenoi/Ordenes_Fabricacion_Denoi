<?php
require_once __DIR__ . "/../../config/database.php"; // ✅ Incluir conexión

class TrabajosController {
    private $conn;

    public function __construct() {
        $database = new Database(); // ✅ Crear instancia de conexión
        $this->conn = $database->getConnection();
    }
    public function index() {
        require_once "../views/trabajos.php";
    }

    public function obtenerTrabajos() {
        try {
            
            ob_clean(); // ✅ Limpiar cualquier salida previa

            $stmt = $this->conn->query("
                SELECT t.codigo_trabajo, t.nombre_trabajo, m.nombre AS nombre_maquina 
                FROM trabajos t
                LEFT JOIN maquinas m ON t.codigo_maquina = m.codigo 
                ORDER BY t.nombre_trabajo ASC
            ");
            $trabajos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header("Content-Type: application/json");
            echo json_encode($trabajos);
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }

    public function crearTrabajo() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $codigoTrabajo = $_POST["codigoTrabajo"];
                $nombreTrabajo = $_POST["nombreTrabajo"];
                $codigoMaquina = $_POST["codigoMaquina"];

                $stmt = $this->conn->prepare("
                    INSERT INTO trabajos (codigo_trabajo, nombre_trabajo, codigo_maquina) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$codigoTrabajo, $nombreTrabajo, $codigoMaquina]);

                echo json_encode(["success" => true]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }

    public function editarTrabajo() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $codigoTrabajo = $_POST["codigoTrabajo"];
                $nombreTrabajo = $_POST["nombreTrabajo"];
                $codigoMaquina = $_POST["codigoMaquina"];
    
                $stmt = $this->conn->prepare("
                    UPDATE trabajos SET nombre_trabajo = ?, codigo_maquina = ? WHERE codigo_trabajo = ?
                ");
                $stmt->execute([$nombreTrabajo, $codigoMaquina, $codigoTrabajo]);
    
                echo json_encode(["success" => true]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }
    
    public function eliminarTrabajo() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $codigoTrabajo = $_POST["codigoTrabajo"];

                $stmt = $this->conn->prepare("DELETE FROM trabajos WHERE codigo_trabajo = ?");
                $stmt->execute([$codigoTrabajo]);

                echo json_encode(["success" => true]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }

    public function obtenerMaquinas() {
        try {
            
            
            $stmt = $this->conn->query("SELECT codigo, nombre FROM maquinas ORDER BY nombre ASC");
            $maquinas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header("Content-Type: application/json");
            echo json_encode($maquinas);
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }
    
}
?>
