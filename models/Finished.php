<?php

class Finished extends Model {

    function __construct() {
        parent::__construct();
        
    }

    
    function validate() {
        $this->validate_existence('user');
    }
}