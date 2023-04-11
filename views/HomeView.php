<?php
require_once 'BaseView.php';
/**
 * The HomeView class represents the home view in the application.
 */
class HomeView extends BaseView
{

    /**
     * Constructor method to create a new instance of HomeView.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('HomeTemplate');
    }
}