<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class i18nTest extends TestCase {

    public function test_get_wrong_lang()
    {
        $test = new Session();
        $test->lang = 'FX';
        Session::$current = $test;
        $str = Factory::generate_random_string();        
        $result = i18n::get($str);
        $this->assertEquals("Translation not found for label {$str}. (FX)", $result); 
    }
    
    
    public function test_get_wrong_key()
    {
        $test = new Session();
        $test->lang = 'DE';
        Session::$current = $test;
        $str = Factory::generate_random_string();        
        $result = i18n::get($str);
        $this->assertEquals("Translation not found for label {$str}. (DE)", $result); 
    }
    
}
