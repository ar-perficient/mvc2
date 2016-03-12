<?php

class Config_Framework_Route extends Config_Framework_App
{
    private $_controllerName;
    private $_actionName;
    private $_request;
    
    private $_parameters = array();


    public function __construct() 
    {
        if (!$this->setRequest()->checkRequest()) {
            $this->splitUrl();
        }
        
        $this->setParam($this->_parameters);
        $this->_controllerName = ucwords($this->_controllerName).'Controller';
        
        $controllerFile = ROOT 
                . DS . 'app' . DS . 'Controllers' 
                . DS . $this->_controllerName . '.php';
        
        if (file_exists($controllerFile)) {
            $controller = new $this->_controllerName;
            if (method_exists($controller, $this->_actionName)) {
                call_user_func_array(
                    array(
                    $controller, 
                    $this->_actionName), 
                    $this->_parameters
                );
            } else {
                $this->_redirect($this->getBaseUrl().'errors/404.html');
            }
        } else {
            $this->_redirect($this->getBaseUrl().'errors/404.html');
        }
    }
    
    private function setRequest()
    {
        $this->_request = $_GET;
        return $this;
    }

    private function checkRequest()
    {
        if (empty($this->_request)) {
            $this->_controllerName = parent::DEFAULY_CONTROLLER;
            $this->_actionName = parent::DEFAULY_ACTION;
            
            return true;
        } else {
            return false;
        }
    }
    
    private function splitUrl()
    {
        $url = trim($this->_request['url'], '/');
        
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        
        // Loop through each pair of elements.
        for ( $i = 2; $i < count($url); $i = $i + 2) {
            $this->_parameters[$url[$i]] = $url[$i + 1];
        }

        // Put URL parts into according properties
        $this->_controllerName = isset($url[0]) ? $url[0] : parent::DEFAULY_CONTROLLER;
        $this->_actionName = isset($url[1]) ? $url[1].'Action' : parent::DEFAULY_ACTION;

        // Remove controller name and action name from the split URL
        unset($url[0], $url[1]);
    }
}