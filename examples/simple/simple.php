<?php

require_once dirname(__FILE__) . '/../../ActiveRecord.php';

class Book extends ActiveRecord\Model { }

ActiveRecord\Config::initialize(
    function($cfg) {
                $cfg->set_model_directory('.');
                $cfg->set_connections(
                    array(
                        'development' => 'mysql://root:root@127.0.0.1/mvc'
                    )
                );
    }
);

print_r(Book::all());
