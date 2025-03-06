<?php
class Router {
    public function run() {
        $url = $_GET['url'] ?? 'home';
        $controllerName = ucfirst($url) . 'Controller';
        $controllerPath = __DIR__ . "/../app/controllers/$controllerName.php"; // RUTA ABSOLUTA

        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controller = new $controllerName();
            $controller->index();
        } else {
            echo "Página no encontrada.";
        }
    }
}
?>