<?php
require_once __DIR__ . "/../../config/database.php"; // ✅ Conexión a la BD

class RutaController {
    private $conn;

    public function __construct() {
        $database = new Database(); // ✅ Crear instancia de conexión
        $this->conn = $database->getConnection();
    }

    // ✅ Mostrar la vista principal
    public function index() {
        require_once "../views/rutas.php";
    }

    // ✅ Obtener todas las rutas
    public function obtenerRutas() {
        try {
            $stmt = $this->conn->query("SELECT id, numero_plantilla, descripcion FROM hojas_rutas ORDER BY numero_plantilla ASC");
            $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            header("Content-Type: application/json");
            echo json_encode($rutas);
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }

    // ✅ Crear una nueva ruta
    public function crearRuta() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $numeroPlantilla = $_POST["numero_plantilla"];
                $descripcion = $_POST["descripcion"];

                $stmt = $this->conn->prepare("INSERT INTO hojas_rutas (numero_plantilla, descripcion) VALUES (?, ?)");
                $stmt->execute([$numeroPlantilla, $descripcion]);

                echo json_encode(["success" => true]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }

    // ✅ Editar una ruta existente
    public function editarRuta() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $idRuta = $_POST["id_ruta"];
                $numeroPlantilla = $_POST["numero_plantilla"];
                $descripcion = $_POST["descripcion"];

                $stmt = $this->conn->prepare("UPDATE hojas_rutas SET numero_plantilla = ?, descripcion = ? WHERE id = ?");
                $stmt->execute([$numeroPlantilla, $descripcion, $idRuta]);

                echo json_encode(["success" => true]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }

    // ✅ Eliminar una ruta
    public function eliminarRuta() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $idRuta = $_POST["id_ruta"];

                $stmt = $this->conn->prepare("DELETE FROM hojas_rutas WHERE id = ?");
                $stmt->execute([$idRuta]);

                echo json_encode(["success" => true]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }

    // Obtenemos los trabajos por hoja de ruta
    public function obtenerTrabajosPorHoja() {
        $id = $_GET["id"] ?? 0;
    
        $stmt = $this->conn->prepare("
            SELECT ht.id, ht.orden, t.nombre_trabajo, t.codigo_trabajo, m.nombre AS nombre_maquina
            FROM hojas_rutas_trabajos ht
            JOIN trabajos t ON ht.codigo_trabajo = t.codigo_trabajo
            LEFT JOIN maquinas m ON ht.codigo_maquina = m.codigo
            WHERE ht.id_hoja_ruta = ?
            ORDER BY ht.orden ASC
        ");
    
        $stmt->execute([$id]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        header("Content-Type: application/json");
        echo json_encode($resultado);
        exit();
    }
    
    // Agregar un trabajo a una hoja de ruta
    public function agregarTrabajoARuta() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // ✅ Obtener los datos del formulario
                $idHojaRuta = $_POST["idHojaRuta"];
                $codigoTrabajo = $_POST["codigoTrabajo"];
                $codigoMaquina = $_POST["codigoMaquina"];
                $ordenTrabajo = $_POST["ordenTrabajo"];
    
                // ✅ Validar si la hoja de ruta existe
                $stmt = $this->conn->prepare("SELECT id FROM hojas_rutas WHERE id = ?");
                $stmt->execute([$idHojaRuta]);
                if ($stmt->rowCount() == 0) {
                    echo json_encode(["error" => "❌ Error: La hoja de ruta no existe."]);
                    exit();
                }
    
                // ✅ Validar si el trabajo existe
                $stmt = $this->conn->prepare("SELECT codigo_trabajo FROM trabajos WHERE codigo_trabajo = ?");
                $stmt->execute([$codigoTrabajo]);
                if ($stmt->rowCount() == 0) {
                    echo json_encode(["error" => "❌ Error: El trabajo no existe."]);
                    exit();
                }
    
                // ✅ Validar si la máquina existe
                $stmt = $this->conn->prepare("SELECT codigo FROM maquinas WHERE codigo = ?");
                $stmt->execute([$codigoMaquina]);
                if ($stmt->rowCount() == 0) {
                    echo json_encode(["error" => "❌ Error: La máquina no existe."]);
                    exit();
                }
    
                // ✅ Insertar el trabajo en la hoja de ruta
                $stmt = $this->conn->prepare("
                    INSERT INTO hojas_rutas_trabajos (id_hoja_ruta, codigo_trabajo, codigo_maquina, orden) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$idHojaRuta, $codigoTrabajo, $codigoMaquina, $ordenTrabajo]);
    
                echo json_encode(["success" => true, "message" => "✅ Trabajo agregado correctamente a la hoja de ruta."]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }
    // Editar un trabajo en una hoja de ruta
    public function editarTrabajoEnHoja() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $idHojaRuta = $_POST["idHojaRuta"] ?? null;
                $codigoTrabajo = $_POST["codigoTrabajo"] ?? null;
                $codigoMaquina = $_POST["codigoMaquina"] ?? null;
                $orden = $_POST["orden"] ?? null;
    
                if (!$idHojaRuta || !$codigoTrabajo || !$codigoMaquina || !$orden) {
                    echo json_encode(["error" => "❌ Error: Datos incompletos."]);
                    exit();
                }
    
                $stmt = $this->conn->prepare("
                    UPDATE hojas_rutas_trabajos 
                    SET codigo_maquina = ?, orden = ?
                    WHERE id_hoja_ruta = ? AND codigo_trabajo = ?
                ");
                $stmt->execute([$codigoMaquina, $orden, $idHojaRuta, $codigoTrabajo]);
    
                echo json_encode(["success" => true]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }
    // Eliminar un trabajo de una hoja de ruta
    public function eliminarTrabajoDeHoja() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $idHojaRuta = $_POST["idHojaRuta"] ?? null;
                $codigoTrabajo = $_POST["codigoTrabajo"] ?? null;
                var_dump($idHojaRuta);
                if (!$idHojaRuta || !$codigoTrabajo) {
                    echo json_encode(["error" => "❌ Error: Datos incompletos."]);
                    exit();
                }
    
                $stmt = $this->conn->prepare("
                    DELETE FROM hojas_rutas_trabajos 
                    WHERE id_hoja_ruta = ? AND codigo_trabajo = ?
                ");
                $stmt->execute([$idHojaRuta, $codigoTrabajo]);
    
                echo json_encode(["success" => true]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "❌ Error: " . $e->getMessage()]);
        }
        exit();
    }
    
    
}
?>
