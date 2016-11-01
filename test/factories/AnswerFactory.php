<?php

class AnswerFactory extends Factory{

    public function __construct() {
        
        $this->default_properties = array(
            'question' => Factory::build('Question')
        );
        
    }
    
    function with_parent_answer()
    {
        $this->default_properties['answer'] = Factory::build('Answer');
    }
}

