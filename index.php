<?php
/**
 * 
 * Set error reporting settings.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Include the necessary controller files.
 */
require_once 'controllers/HomeController.php';
require_once 'controllers/ProviderController.php';

/**
 * Set the default page to 'homepage' if not specified.
 */
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controller = null;

switch ($page) {
    case 'home':
        $controller = new HomeController();
        break;
    case 'provider':
        $controller = new ProviderController();
        break;
    default:
        http_response_code(404);
        echo '404 - Page not found';
}

if ($controller !== null) {
    if (method_exists($controller, $action)) {
        $controller->$action($_GET);
    } else {
        http_response_code(404);
        echo '404 - Action not found';
    }
}