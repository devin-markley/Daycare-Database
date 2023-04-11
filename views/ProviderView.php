<?php
require_once 'BaseView.php';
/**
 * The ProviderView class represents the provider view in the application.
 */
class ProviderView extends BaseView
{

    /**
     * Constructor method to create a new instance of ProviderView.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('ProviderTemplate');
    }
}