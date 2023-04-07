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
// require_once 'controllers/ProviderController.php';

/**
* Include the necessary model files.
*/
// require_once 'models/HomeModel.php';

/**
* Include the necessary model files.
*/
// require_once 'views/HomeView.php';

/**
 * Set the default page to 'homepage' if not specified.
 */
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        // $homeModel = new HomeModel();
        // $homeView = new View();
        $controller = new homeController();
        $controller->index();
        break;
    case 'provider':
        $controller = new providerController();
        $controller->index();
        break;
    default:
        http_response_code(404);
        echo '404 - Page not found';
}

?>