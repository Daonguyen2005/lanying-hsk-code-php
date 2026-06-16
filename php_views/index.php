<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Simple Router
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

$controllerName = ucfirst($url[0]) . 'Controller';
$controllerFile = 'Controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    
    $methodName = isset($url[1]) ? $url[1] : 'index';
    if (method_exists($controller, $methodName)) {
        call_user_func_array([$controller, $methodName], array_slice($url, 2));
    } else {
        echo "404 - Method not found";
    }
} else {
    // Default to Home if empty or not found
    switch ('/' . ($url[0] ?? '')) {
        case '/profile':
            require 'Views/profile/index.php';
            break;
        default:
            if(file_exists('Controllers/HomeController.php')) {
                require_once 'Controllers/HomeController.php';
                $controller = new HomeController();
                $controller->index();
            } else {
                echo "MVC Setup Error: HomeController not found";
            }
            break;
    }
}
