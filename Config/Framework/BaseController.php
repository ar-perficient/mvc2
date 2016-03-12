<?php

class Config_Framework_BaseController extends Config_Framework_App
{
    protected $model;
    /**
     * class constructor
     * loads the controller model and
     * ORM for further transaction
     */
    public function __construct()
    {
        $this->loadModel();
    }

    /**
     * Load the currennt view
     * @param type $controller
     * @param type $action
     * @param type $data
     * @return type
     */
    protected function loadView($controller, $action, $data = '')
    {
        return $this->setView($controller, $action, $data);
    }
    
    /**
     * Load the current Model
     * @param type $name
     * @return type
     */
    protected function loadModel()
    {
        $modelClass = str_replace('Controller', '', get_class($this));
        $this->model = new $modelClass();
        return $this;
    }
}