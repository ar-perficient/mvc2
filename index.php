<?php

error_reporting(E_ALL);
 
define('DS', DIRECTORY_SEPARATOR);

define('US', '/');

define('ROOT', getcwd());

require_once (ROOT . DS . 'Config' . DS . 'bootstrap.php');

$app = new Config_Framework_App();

$app->run();

