<?php 
require_once 'BaseController.php';
require_once 'models/HomeModel.php';
require_once 'views/HomeView.php';

/**
 * The HomeController class is responsible for managing the home page.
 */
class HomeController extends BaseController {

    /**
     * Displays the home page.
     *
     * @return void
     */
    public function index() {
        $model = new HomeModel();
        $providers = $model->getAllProviders();
        $view = new HomeView();
        $view->setData(['providers' => $providers]);
        $view->render();
    }
}

?>