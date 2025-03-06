<?php
require_once '../config/config.php';
require_once '../core/Router.php';

// Instancia el enrutador y ejecuta la solicitud
$router = new Router();
$router->run();
?>
