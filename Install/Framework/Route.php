<?php

Class Framework_Route
{
    protected $_controllerName;
    
    protected $_actionName;


    public function __construct()
    {
        if (!empty($_GET)) {
            $this->processRequest($_GET);
        }
        
    }
    
    public function processRequest($url)
    {
        if (empty($url)) {
            $this->_controllerName = 'Controller';
            $this->_actionName = 'indexAction';
        } else {
            $url = explode('/', $url['url']);
            if (isset($url[0])) {
                $this->_controllerName = $url[0];
            } else {
                $this->_controllerName = 'Controller';
            }
            
            if (isset($url[1]) && !empty($url[1])) {
                $this->_actionName = $url[1].'Action';
            } else {
                $this->_actionName = 'indexAction';
            }
        }
        
        $this->_queryString = array();
        
        $this->_controllerName = 'Framework_'.ucwords($this->_controllerName);
        
        $controller = new $this->_controllerName;
        
        if (method_exists($controller, $this->_actionName)) {
            call_user_func_array(
                array(
                $controller, 
                $this->_actionName), 
                $this->_queryString
            );
        }
    }
    
    public function sendResponce()
    {
        
    }
}
