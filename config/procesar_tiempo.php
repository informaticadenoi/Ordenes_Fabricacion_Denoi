<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idColchon = $_POST['idColchon'];
    $accion = $_POST['accion']; // Puede ser "iniciar" o "finalizar"

    if ($accion === "iniciar") {
        $stmt = $conn->prepare("UPDATE tplan SET inicio_tiempo = NOW(), fin_tiempo = NULL WHERE idmodelo = ?");
        $stmt->execute([$idColchon]);
    } elseif ($accion === "finalizar") {
        $stmt = $conn->prepare("UPDATE tplan SET fin_tiempo = NOW() WHERE idmodelo = ?");
        $stmt->execute([$idColchon]);
    }

    echo json_encode(["success" => true]);
}
?>
