<?php include 'layout/header.php'; ?>
<?php
require_once "../config/database.php"; // Incluir conexión

?>

<div class="container mt-4">
    <h2 class="text-primary text-center">Bienvenido a la Página de Inicio</h2>
    <p class="text-center">Este es el contenido de la página principal.</p>
    <a href="?url=fabricacion">Vista de los trabajadores</a>
</div>

<?php include 'layout/footer.php'; ?>
