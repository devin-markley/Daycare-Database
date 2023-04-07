<?php
require_once 'BaseView.php';

class HomeView extends BaseView {
    public function __construct() {
        parent::__construct('HomeTemplate');
    }
}