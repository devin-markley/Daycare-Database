<?php
/*
The abstract BaseController class is the base class for all controllers.
*/
abstract class BaseController
{
    /*
    The model object used by the controller.
    @var object $model
    */
    protected $model;

    /*
    The view object used by the controller.
    @var object $view
    */
    protected $view;

    /*
    The default action for the controller.
    @return void
    */
    public function index()
    {
        // Do something by default
    }

    /*
    Sets the model for the controller.
    @param object $model The model object to set.
    @return void
    */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /*
    Gets the model for the controller.
    @return object The model object.
    */
    public function getModel()
    {
        return $this->model;
    }

    /*
    Sets the view for the controller.
    @param object $view The view object to set.
    @return void
    */
    public function setView($view)
    {
        $this->view = $view;
    }

    /*
    Gets the view for the controller.
    @return object The view object.
    */
    public function getView()
    {
        return $this->view;
    }
}