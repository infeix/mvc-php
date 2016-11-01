<?php


class QuestionFactory extends Factory{

    public function __construct() {
        
        $this->default_properties = array(
            'input_type' => $this::get_one_of(['checkbox', 'radio']),
            'multi_select' => $this::get_one_of([0,1]),
            'sort_index' => $this::generate_random_number()
        );
        
    }  
}
