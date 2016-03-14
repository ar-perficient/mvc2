<?php 

define('DS', DIRECTORY_SEPARATOR);

define('US', '/');

require_once '../lib/Install/Autoload.php';

Lib_Install_Autoload::register();

Framework_Controller::run();