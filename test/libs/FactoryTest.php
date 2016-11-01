<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class FactoryTest extends TestCase {
    
    
    
    public function setUp()
    {
        Factory::clean_database();
        foreach(Answer::scope()->all() as $answer)
        {
            $answer->delete();
        }
    }
    
    public function test_create_with_properties() {
        $this->assertEquals(0, count(Finished::scope()->all()));
        $users = User::scope();
        $this->assertEquals(2, count($users->all()));
        $user = $users->first();
        
        Factory::create("Finished", array("user" => $user));
        $users = User::scope();
        $this->assertEquals(2, count($users->all()));
        
        $this->assertEquals(1, count(Finished::scope()->all()));
    }
    
    
    public function test_create() {
        $this->assertEquals(0, count(Answer::scope()->all()));
        $this->answer = Factory::create("Answer");
        
        $this->assertEquals(1, count(Answer::scope()->all()));
    }
    
    public function test_clean_database() {
        $this->assertEquals(6, count(Question::scope()->all()));
        Factory::create("Question");
        $this->assertEquals(7, count(Question::scope()->all()));
        Factory::clean_database();
        $this->assertEquals(6, count(Question::scope()->all()));
        $this->assertEquals(6, count(Question_label::scope()->where(array('lang' => 'DE'))->all()));
    }
}
