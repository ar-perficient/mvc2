<?php

function __autoload($class)
{
    $class = str_replace('_', '/', ucwords($class));
    include_once $class.'.php';
}