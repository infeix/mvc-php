<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class FilterTest extends TestCase {

    public function setUp()
    {
    }
    
    function test_initiate_without_url() {
        // act
        $filter = new Filter(array("test_string1" => "test_string2",
                                    'test_string3' => "test_;'\\string4"));
        // check
        $this->assertTrue(isset($filter->arr['test_string1']));
        $this->assertEquals("test_string2", $filter->arr['test_string1']);
        $this->assertTrue(isset($filter->arr['test_string3']));
        $this->assertEquals("test_;'\\string4", $filter->arr['test_string3']);
    }
        
    function test_get() {
        // act
        $filter1 = Filter::get("test_filter", 
                               array("test_string1" => "test_string2",
                                     'test_string3' => "test_;'\\string4"));
        $this->assertTrue(isset($filter1->arr['test_string1']));
        $this->assertEquals("test_string2", $filter1->arr['test_string1']);
        $this->assertTrue(isset($filter1->arr['test_string3']));
        $this->assertEquals("test_;'\\string4", $filter1->arr['test_string3']);
        
        $filter2 = Filter::get("test_filter");
        // check
        $this->assertEquals($filter1, $filter2);
        
        $filter3 = Filter::get("sdgfsdbvgs");
        $this->assertTrue(is_a($filter3, 'Filter'));
        $this->assertEquals(NULL, $filter3->arr);
        
    }
    
    
    function test_validate_string()
    {
        $filter1 = Filter::get("test_filter", 
                               array("test_string1" => "test_string2",
                                     'test_string3' => "test_;'\\string4"));
        
        
        $this->assertEquals('', $filter1->validate_string('sdgfsdbvgs'));
        $this->assertEquals("test_;'\\string4", $filter1->validate_string('test_string3'));
        
    }
    
    
}
