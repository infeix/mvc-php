<?php

class ResultFactory extends Factory{

    public function __construct() {
        
        $this->default_properties = array(
            'answer' => Factory::build('Answer'),
            'user' => Factory::build('User'),
        );
    }
    
    public function after_factories_build() {
        $this->creation->question_id = $this->creation->get_parent('answer')->get_parent_pk('question');
    }
}
