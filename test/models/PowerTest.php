<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class PowerTest extends TestCase {
    
    public function setUp()
    {
        Factory::clean_database();
        $this->current_user = Factory::create("User");
        $this->question = Factory::create("Question");
    }

    public function test_initialize_with_user()
    {
        $_SESSION['current_user'] = $this->current_user->CID;
        // Act
        $a = new Power();

        // Assert
        $this->assertEquals($this->current_user->CID, $a->current_user->CID);
    }
    
    public function test_initialize_without_user()
    {
        // Act
        $a = new Power();

        // no user set
        $this->assertFalse(isset($a->current_user));
    }   
    
    public function test_set_user()
    {
        // Act
        $a = new Power();

        // no user set
        $this->assertFalse($a->got_user());
        
        $a->set_user($this->current_user);
        
        // now the user is set
        $this->assertEquals($this->current_user->CID, $a->current_user->CID);
    }
    
    public function test_got_user()
    {
        // Act
        $a = new Power();

        // no user set
        $this->assertFalse(isset($a->current_user));
        $this->assertFalse($a->got_user());
        
        $a->set_user($this->current_user);
        
        // now user is set
        $this->assertTrue(isset($a->current_user));
        $this->assertTrue($a->got_user());
    }
    
    
    public function test_questions_for_showing_results_not_finished()
    {
        // Act
        $a = new Power();

        // no user set
        $this->assertFalse($a->questions_for_showing_results());
        
        $a->set_user($this->current_user);
        
        // now user is set
        $this->assertTrue($a->got_user());
        $this->assertFalse($a->questions_for_showing_results());
    }
    
    
    public function test_questions_for_showing_results_finished()
    {
        $this->finished = Factory::create("Finished", array("user" => $this->current_user));
        // Act
        $a = new Power();

        // no user set
        $this->assertFalse($a->questions_for_showing_results());
        
        $a->set_user($this->current_user);
        
        // now user is set
        $this->assertTrue($a->got_user());
        $this->assertEquals("Scope", get_class($a->questions_for_showing_results()));
    }
    
    public function test_questions_for_creating_a_result_not_finished()
    {
        // Act
        $a = new Power();

        // no user set
        $this->assertFalse($a->questions_for_creating_a_result());
        
        $a->set_user($this->current_user);
        
        // now user is set
        $this->assertTrue($a->got_user());
        $this->assertEquals("Scope", get_class($a->questions_for_creating_a_result()));
    }
    
    
    public function test_questions_for_creating_a_result_finished()
    {
        $this->finished = Factory::create("Finished", array("user" => $this->current_user));
        // Act
        $a = new Power();

        // no user set
        $this->assertFalse($a->questions_for_creating_a_result());
        
        $a->set_user($this->current_user);
        
        // now user is set
        $this->assertTrue($a->got_user());
        $this->assertFalse($a->questions_for_creating_a_result());
    }
    
}

