<?php

class Framework_Controller
{
    private static $_instance;
    
    protected $_rootDir;
    
    protected $_appDir;
    
    protected $_redirectUrl;
    
    protected $_view;
    
    protected $_actionName;
    
    protected $_controllerName;
    
    const DB_CONNECT_FILE = 'database.json';
    
    static private $_registry  = array();
    
    public function __construct() 
    {
        $this->_rootDir = dirname(dirname(__FILE__));
        $this->_appDir = dirname($this->_rootDir);        
    }

    public static function run()
    {
        if (!self::singleton()->config()) {
            self::singleton()->startInstaller();
        } else {
            self::singleton()->_redirect(self::singleton()->getBaseUrl());
        }
    }
    
    public static function singleton()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }
        
        return self::$_instance;
    }
    
    public function startInstaller()
    {
        $this->setAction();
        $this->processRequest();
    }
    
    public function setAction($action = null)
    {
        if (!empty($_GET['url'])) {
            $url = $_GET;
            $url = explode('/', $url['url']);
            
            if (isset($url[0]) && !empty($url[0])) {
                $this->_actionName = $url[0] . 'Action';
            } else {
                $this->_actionName = 'indexAction';
            }
        } else {
            $this->_actionName = 'indexAction';
        }
        
        return $this->_actionName;
    }
    
    public function getAction()
    {
        return $this->_actionName;
    }
    
    public function _redirect($url, $isPermanent = false)
    {
        if ($isPermanent) {
            header('HTTP/1.1 301 Moved Permanently');
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
        header('Location: ' . $url);
        exit;
    }
    
    public function view()
    {
        if (!$this->_view) {
            $this->_view = new Framework_View;
        }
        return $this->_view;
    }
    
    public function indexAction()
    {
        $this->view()->set('title', 'Set Configuration');
        $this->view()->set('media_path', $this->getInstallMediaPath());
        $this->view()->template('index.php');
    }
    
    public function processRequest()
    {
        if (method_exists(self::$_instance, $this->_actionName)) {
            call_user_func_array(
                array(
                self::$_instance, 
                $this->_actionName), 
                array()
            );
        }
    }
    
    public function filepath($name = '')
    {
        $ds = DIRECTORY_SEPARATOR;
        return rtrim(
            $this->getRootDir() . $ds . str_replace('/', $ds, $name), $ds
        );
    }
    
    public function getRootDir()
    {
        return $this->_rootDir;
    }
    
    public function getBaseUrl() 
    {
        $currentPath = $this->getServerValues('PHP_SELF'); 

        $pathInfo = pathinfo($currentPath); 

        $hostName = $this->getServerValues('HTTP_HOST'); 
        
        $serverProtocol = $this->getServerValues('SERVER_PROTOCOL');
        
        $protocol = strtolower(
            substr(
                $serverProtocol, 
                0, 
                5
            )
        ) == 'https://' ? 'https://' : 'http://';

        $pro = explode('/', $pathInfo['dirname']);
        
        return $protocol.$hostName . "/" . $pro[1] . "/";
    }
    
    protected function getInstallMediaPath()
    {
        return $this->getBaseUrl() . 'public' . US . 'install' . US;
    }
    
    private function getServerValues($value)
    {
        return filter_input(INPUT_SERVER, $value, FILTER_SANITIZE_STRING);
    }
    
    private function config()
    {
        if (file_exists($this->_appDir . DS . 'app' . DS . 'Config' . DS .self::DB_CONNECT_FILE)) {
            return true;
        } else {
            return false;;
        }
    }
    
    public function setConfigAction()
    {
        $confFile = $this->_appDir . DS . 'app' . DS . 'Config' . DS .self::DB_CONNECT_FILE;
        Framework_Model::setConfig($confFile);
    }
    
    public static function register($key, $value, $graceful = false)
    {
        if (isset(self::$_registry[$key])) {
            if ($graceful) {
                return;
            }
        }
        self::$_registry[$key] = $value;
    }
    
    public static function registry($key)
    {
        if (isset(self::$_registry[$key])) {
            return self::$_registry[$key];
        }
        return null;
    }
    
}
