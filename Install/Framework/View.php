<?php

class Framework_View
{
    private static $_data = array();
    
    public function __construct() 
    {
        $this->clearSession();
    }

    public function controller()
    {
        return Framework_Controller::singleton();
    }
    
    public function template($name)
    {
        include $this->controller()->filepath('template/'.$name);
    }
    
    public function set($key, $value)
    {
        Framework_Controller::register($key, $value);
    }
    
    public function get($key)
    {
        return Framework_Controller::registry($key);
    }   
    
    public function getBaseUrl()
    {
        return $this->controller()->getBaseUrl();
    }
    
    public function __call($name, $arguments) 
    {
        switch (substr($name, 0, 3)) {
            case 'get' :
                $data = $this->get(substr($name, 3));
                return $data;
                break;
            case 'set' :
                $this->set(substr($name, 3), $arguments[0]);
                break;
        }
    }
    
    public function setSessionMessage($key, $message)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        switch ($key) {
            case 'error':
                $_SESSION['error'] = $message;
                break;
            case 'success':
                $_SESSION['success'] = $message;
                break;
            case 'warning':
                $_SESSION['warning'] = $message;
                break;
            case 'notice':
                $_SESSION['notice'] = $message;
                break;
            default :
                $_SESSION[$key] = $message;
                break;
        }
    }
    
    public function getSessionMessage($key = 'all')
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        if (array_key_exists($key, $_SESSION)) {
            switch ($key) {
                case 'error':
                    return $_SESSION['error'];
                    break;
                case 'success':
                    return $_SESSION['success'];
                    break;
                case 'warning':
                    return $_SESSION['warning'];
                    break;
                case 'notice':
                    return $_SESSION['notice'];
                    break;
                default :
                    return null;
                    break;
            }
        } else {
            return null;
        }
    }
    
    public function clearSession()
    {
        session_unset();
        unset($_SESSION);
    }
    
    public function __destruct() 
    {
       // $this->clearSession();
    }
    
}
