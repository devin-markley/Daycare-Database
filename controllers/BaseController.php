<?php
abstract class BaseController
{
    protected $model;
    protected $view;
    public function __construct()
    {
        // $this->model = $model;
        // $this->view = $view;
    }

    // Default action for the controller
    public function index()
    {
        // Do something by default
    }

    // Set the model for the controller
    public function setModel($model)
    {
        $this->model = $model;
    }

    // Get the model for the controller
    public function getModel()
    {
        return $this->model;
    }

    // Set the view for the controller
    public function setView($view)
    {
        $this->view = $view;
    }

    // Get the view for the controller
    public function getView()
    {
        return $this->view;
    }

}