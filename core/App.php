<?php
//core/App.php
class App {
    public function loadController($controllerName) {
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            return new $controllerName();
        } else {
            die("Controlador '$controllerName' no encontrado.");
        }
    }

    public function loadModel($modelName) {
        $modelFile = __DIR__ . '/../models/' . $modelName . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelName();
        } else {
            die("Modelo '$modelName' no encontrado.");
        }
    }
}
