<?php

class Config_Framework_App
{
    static private $_registry  = array();    
    static protected $_instance;    
    static protected $_data = array();
    protected $view;
    protected $_modelClass;
    private $_activeRecord = 'ActiveRecord';

    const DEFAULY_CONTROLLER = 'Front';
    const DEFAULY_ACTION = 'FrontAction';

    /**
     * Run the application
     */
    public function run()
    {
        spl_autoload_extensions('.php, .class.php, .lib.php');
        spl_autoload_register(array(self::instance(), '_autoload'));
       
        $this->_loadDir();
        $con = Config_Framework_Database::getDbConn();
        new Config_Framework_Route();
    }

    /**
     * singleton function
     * @return type
     */
    static public function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new Config_Framework_App();
        }
        return self::$_instance;
    }
    
    /**
     * Get the constructor
     * model class
     * @param string $class
     * @return \class
     */
    public function getModel($class)
    {
        $className = ucwords($class). 'Model.php';
        $class = ucwords($class).'Model';
        
        if (file_exists($this->getMediaDir() . $className)) {
            return new $class;
        } 
    }
    
    /**
     * Get the view of the current
     * class
     * @param type $controller
     * @param type $action
     * @param type $data
     * @return \Config_Framework_Template
     */
    public function setView($controller, $action, $data = '')
    {   
        $this->view = new Config_Framework_Template();
        return $this->view->setView($controller, $action, $data);        
    }
    
    /**
     * Get the directory paths
     * for all child class
     * @param type $name
     * @return type
     * @throws Exception
     */
    public function getDirectoryPath($name)
    {
        $name = ucwords($name);
        
        switch ($name)
        {
            case 'Controllers':
                return $this->getControllersDir();
                break;
            
            case 'Models':
                return $this->getModelDir();
                break;
            
            case 'Views':
                return $this->getViewDir();
                break;
            
            case 'Appconfig':
                return $this->getAppDir() . DS . 'Config'. DS;
                break;
        }
        
        throw new Exception('Directory not found');
    }
    
    /**
     * Load the class files
     * @param type $class
     * @return type
     */
    public function _autoload($class)
    {       
        if (file_exists($this->getControllersDir() . $class . '.php')) {
            return include_once $this->getControllersDir() . $class . '.php'; 
        } else if (file_exists($this->getConfDir() . $class . '.php')) {
            return include_once $this->getConfDir() . $class . '.php';
        } else if (file_exists($this->getModelDir() . ucwords($class). '.php')) {
            return include_once $this->getModelDir() . ucwords($class). '.php';
        } else if ($class == 'ActiveRecord\Model') {
            $this->requireDirs();
            ActiveRecord\Config::initialize(
                function($cfg) {
                            $cfg->set_model_directory($this->getModelDir());
                            $cfg->set_connections(
                                array(
                                    'development' => 'mysql://root:root@127.0.0.1/mvc'
                                )
                            );
                }
            );
            $autoloadFuncs = spl_autoload_functions();
            foreach ($autoloadFuncs as $unregisterFunc) {
                spl_autoload_unregister($unregisterFunc);
            }
        } else {
            $class = str_replace('_', '/', ucwords($class));
            return include_once $class.'.php';
        }
        spl_autoload_register(array(self::instance(), '_autoload'));
    }
    
    /**
     * set the global directory
     * paths
     */
    protected function _loadDir()
    {
        $appRoot = ROOT . DS . 'app';
        $root = ROOT;
        
        $this->set('app_dir', $appRoot);
        
        $this->set('base_dir', $root);
        $this->set('conf_dir', $root . DS . 'Config' . DS);
        $this->set('controllers_dir', $appRoot . DS . 'Controllers' . DS);
        $this->set('model_dir', $appRoot . DS . 'Models' . DS);
        $this->set('view_dir', $appRoot . DS . 'Views' . DS);
        $this->set('lib_dir', $root . DS . 'lib' . DS);
        $this->set('config_lib', $this->getConfDir() . 'Lib' . DS);
        $this->set('media_dir', $root . DS . 'public' . DS);       
        $this->set('css_dir', $this->getBaseUrl() . 'public' . US . 'css' . US);       
        $this->set('js_dir', $this->getBaseUrl() . 'public' . US . 'js' . US);       
        $this->set('image_dir', $this->getBaseUrl() . 'public' . US . 'images' . US);       
        $this->set('install_dir', $this->getBaseUrl() . 'public' . US . 'install' . US);     
    }

    /**
     * Registry to load data
     * @param type $key
     * @param type $value
     * @param type $graceful
     * @return type
     */
    public static function register($key, $value, $graceful = false)
    {
        if (isset(self::$_registry[$key])) {
            if ($graceful) {
                return;
            }
        }
        self::$_registry[$key] = $value;
    }
    
    /**
     * Registry function to 
     * fetch loaded data
     * @param type $key
     * @return type
     */
    public static function registry($key)
    {
        if (isset(self::$_registry[$key])) {
            return self::$_registry[$key];
        }
        return null;
    }
    
    /**
     * Get the base url
     * @return type
     */
    public function url()
    {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Redirect to specific path
     * @param type $url
     * @param type $isPermanent
     */
    protected function _redirect($url, $isPermanent = false)
    {
        if ($isPermanent) {
            header('HTTP/1.1 301 Moved Permanently');
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Get the application
     * base path
     * @return type
     */
    public function getBaseUrl() 
    {
        $currentPath = $_SERVER['PHP_SELF'];
        $pathInfo = pathinfo($currentPath);
        $hostName = $_SERVER['HTTP_HOST'];
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5)) == 'https://'?'https://':'http://';
        $pro = explode('/', $pathInfo['dirname']);
        
        return $protocol.$hostName."/".$pro[1]."/";
    }
    
    /**
     * Native function 
     * for getter and setter
     * @param type $name
     * @param type $arguments
     * @return type
     */
    public function __call($name, $arguments) 
    {
        switch (substr($name, 0, 3)) {
            case 'get' :
                $data = $this->get(substr($name, 3));
                return $data;
            case 'set' :
                $this->set(substr($name, 3), $arguments[0]);
                break;
        }
    }
    
    /**
     * Custom setter function
     * @param type $name
     * @param type $value
     */
    public function set($name, $value)
    {
        self::$_data[$name] = $value;
    }
    
    /**
     * Custom getter function
     * @param type $name
     * @return string
     */
    public function get($name)
    {
        $key = strtolower(preg_replace('/\B([A-Z])/', '_$1', $name));
        if (array_key_exists($key, self::$_data)) {
            return self::$_data[$key];
        } else {
            return '';
        }
    }
    
    public function setParam($param = array()) 
    {
        self::$_data['url_params'] = $param;
    }
    
    public function getParam($param) 
    {
        if (isset(self::$_data['url_params'][$param]) && !empty(self::$_data['url_params'][$param])) {
            return self::$_data['url_params'][$param];
        }
        
        return null;
    }
    
    protected function requireDirs()
    {
        require $this->getConfigLib() . $this->_activeRecord . DS . 'ActiveRecord' .'.php';        
        return $this;
    }
}