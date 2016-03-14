<?php

class Framework_Model
{
    private static $_instance;
    
    private $_host;
    
    private $_userName;
    
    private $_password = '';
    
    private $_databaseName;
    
    private $_appPath;
    
    private $_dbConn;


    public function __construct() 
    {
        $this->controller()->view()->clearSession();
    }
    
    public function controller()
    {
        return Framework_Controller::singleton();
    }
    
    public static function singleton()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }
        
        return self::$_instance;
    }

    public static function setConfig($confFile)
    {
        $db = self::singleton();
        $db->setPostConfig();
        
        if ($db->_isConnect()) {
            $db->_writeConfig($confFile);
            $db->controller()->_redirect($db->_appPath);
        }
    }
    
    public function setPostConfig()
    { 
        $p = filter_input(INPUT_POST, 'appConfig', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        
        $this->_host = $p['host'];
        $this->_userName = $p['username'];
        $this->_password = $p['dbpass'];
        $this->_databaseName = $p['dbname'];
        
        $this->_appPath = $p['appPath'];
    }
    
    protected function _isConnect()
    {
        try{
            $this->_dbConn = new PDO(
                "mysql:host="
                .$this->_host.";dbname="
                .$this->_databaseName."", ""
                .$this->_userName."", ""
                .$this->_password.""
            );
            
            return true;
        } catch (Exception $ex) {
            $this->controller()
                ->view()
                ->setSessionMessage('error', $ex->getMessage());
            $this->controller()->_redirect($this->controller()->getBaseUrl());
        }
        
        return false;
    }
    
    protected function _writeConfig($confFile)
    {
        $configData = array(
                'defaultsetup' => array(
                    'connection' => array(
                        "host" => $this->_host,
                        "username" => $this->_userName,
                        "password" => $this->_password,
                        "dbname" => $this->_databaseName,
                        "initStatements" => "SET NAMES utf8",
                        "model" => "mysql4",
                        "type" => "pdo_mysql",
                        "active" => "1"
                    )
                )
            );
            
        file_put_contents($confFile, json_encode($configData, JSON_PRETTY_PRINT));
        $this->controller()
            ->view()
            ->setSessionMessage('success', 'Database connection establish');
        $this->controller()->view()->clearSession();
        
        return $this;
    }
}
