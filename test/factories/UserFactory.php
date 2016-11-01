<?php

class UserFactory extends Factory{

    public function __construct() {
        
        $this->default_properties = array(
            'Firstname' => $this::generate_random_string(8),
            'Lastname' => $this::generate_random_string(8),
            'Email' => $this::generate_random_mail(),
            'Password' => md5($this::generate_random_string(8))
        );
        
    }  
}