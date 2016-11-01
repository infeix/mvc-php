<?php

class Filter {
    
    static $filter = [];
    
    function __construct($arr) {
        $this->arr = $arr;
    }
    
    static function get($name, $arr = NULL) {
        if(!isset(self::$filter[$name]))
        {
            self::$filter[$name] = new Filter($arr);
        }
        elseif(self::$filter[$name]->arr == NULL)
        {
            self::$filter[$name]->arr = $arr;
        }
        return self::$filter[$name];
    }
    
    function validate_string($key, $filter = FILTER_DEFAULT, $options = null)
    {
        if(isset($this->arr[$key]))
        {
            return (string)filter_var($this->arr[$key], $filter, $options);
        }
        else
        {
            return '';
        }
    }

}