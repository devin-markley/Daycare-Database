<?php 
require_once 'BaseController.php';
require_once 'models/HomeModel.php';
require_once 'views/HomeView.php';

class HomeController extends BaseController {

    public function index() {
        $model = new HomeModel();
        $providers = $model->getAllProviders();
        $view = new HomeView();
        $view->setData(['providers' => $providers]);
        $view->render();
    }
}

?>