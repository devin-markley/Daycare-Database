<?php
require_once 'BaseController.php';
require_once 'models/ProviderModel.php';
require_once 'views/ProviderView.php';

class ProviderController extends BaseController {

    public function index() {
        if (isset($_GET['provider_id'])) {
            $providerId = $_SESSION['provider_id'] = $_GET['provider_id'];
        }
        $providerModel = new ProviderModel();
        $children = $providerModel->getAllChildrenByProvider($providerId);
        
        $view = New ProviderView('ProviderTemplate');
        $view->setData(['children' => $children]);
        $view->render();
    }
}