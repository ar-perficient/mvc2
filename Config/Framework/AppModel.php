<?php

class Config_Framework_AppModel 
    extends ActiveRecord\Model
{
    public function select($var)
    {
        $table = $this;
        $data = $table::find($var);
        return $data;
    }
}
