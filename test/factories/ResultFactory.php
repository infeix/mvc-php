<?php

class ResultFactory extends Factory{

    public function __construct() {
        
        $this->default_properties = array(
            'answer' => Factory::build('Answer')
        );
    }
}
