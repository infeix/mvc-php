<?php

class User extends Model {

    function __construct() {
        parent::__construct();
    }
    
    static function get_pk() 
    {
        return 'CID';
    }

}