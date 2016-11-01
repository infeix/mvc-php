<?php

class FinishedFactory extends Factory{

    public function __construct() {
        
        $this->default_properties = array(
            'user' => Factory::build('User')
        );
        
    }
}

