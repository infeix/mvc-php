<?php


class Answer_labelFactory extends Factory{

    public function __construct() {
        
        $this->default_properties = array(
            'lang' => $this::get_one_of(['DE', 'EN']),
            'label' => $this::generate_random_string(),
            'answer' => Factory::build("Answer")
        );
        
    }  
}

