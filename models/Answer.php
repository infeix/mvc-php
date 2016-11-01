<?php

class Answer extends Model {

    function __construct() {
        
    }
    
    function validate() {
        $this->validate_existence('question');
    }

}