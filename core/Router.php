<?php
class Router {
    public function run() {
        $url = $_GET['url'] ?? 'home';
        $parts = explode('/', $url);
        $controllerName = ucfirst($parts[0]) . 'Controller'; // Primer segmento -> Controlador
        $action = $parts[1] ?? 'index'; // Segundo segmento -> Método del controlador
        $params = array_slice($parts, 2); // Resto de segmentos -> Parámetros opcionales

        $controllerPath = __DIR__ . "/../app/controllers/$controllerName.php";

        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controller = new $controllerName();

            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], $params);
            } else {
                echo "❌ Error: Método '$action' no encontrado en '$controllerName'.";
            }
        } else {
            echo "❌ Error: Controlador '$controllerName' no encontrado.";
        }
    }
}
?>
