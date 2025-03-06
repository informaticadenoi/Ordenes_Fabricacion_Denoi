<?php
$host = "localhost";  // Servidor de la BD
$user = "root";       // Usuario de la BD
$password = "";       // Contraseña (en XAMPP suele estar vacío)
$dbname = "copia_denoi"; // Nombre de la base de datos

try {
    // Crear conexión con PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mensaje si la conexión es exitosa (solo para pruebas)
    echo "✅ Conexión exitosa a la base de datos";
} catch (PDOException $e) {
    die("❌ Error en la conexión: " . $e->getMessage());
}
?>
