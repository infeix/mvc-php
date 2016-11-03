<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class ScopeTest extends TestCase {
    
    
    
    public function setUp()
    {
        Factory::clean_database();
        foreach(Answer::scope()->all() as $answer)
        {
            $answer->delete();
        }
        $this->answer = Factory::create("Answer");
        
        $this->scope = new Scope('Result');
    }

    public function test_initialize()
    {
        $this->scope = new Scope("testing String");
        
        // no user set
        $this->assertEquals($this->scope->models[0], "testing String");
        
    }
    
    public function test_where() {
        $this->user = Factory::create("User");
        $this->model1 = Factory::create('Result');
        $this->model2 = Factory::create('Result', 
                                       array("user" => $this->user));
        $this->model3 = Factory::create('Result', 
                                        array("user" => $this->user,
                                              "answer" => $this->answer));
        // check conditions
        $this->assertEquals(3, count(Result::scope()->all()));
        // prepare new scope
        $this->scope = new Scope('Result');
        
        // act
        $result = $this->scope->where(array("user" => $this->user));
        
        // check
        $this->assertTrue(is_a($result, "Scope"));
        $this->assertEquals(2, count($this->scope->all()));
        $this->assertEquals(" `ResultDB`.`user_id` = '{$this->user->CID}'", $this->scope->where_statement);
        
    }
    
    public function test_where_fk() {
        // prepare new scope
        $this->scope = new Scope('Result');
        
        // act
        $result = $this->scope->where(array("other_table.fk_id" => 5));
        
        // check
        $this->assertTrue(is_a($result, "Scope"));
        $this->assertEquals(" `other_tableDB`.`fk_id` = '5'", $this->scope->where_statement);
        
    }

    public function test_where_none() {
        // prepare new scope
        $this->scope = new Scope('Result');
        $this->expectException(Exception::class,5,"Not a correct use of Scope.");
        // act
        $this->scope->where("test");        
        
    }  
    
    public function test_where_select() {
        // set context conditions
        $this->user = Factory::create("User");
        $this->model1 = Factory::create('Result');
        $this->model2 = Factory::create('Result', 
                                       array("user" => $this->user));
        $this->model3 = Factory::create('Result', 
                                        array("user" => $this->user,
                                              "answer" => $this->answer));
        
        $this->scope = new Scope('Result');
        $this->assertEquals(3, count($this->scope->all()));
        $this->scope->where(array("user" => $this->user));
        
        $this->assertEquals(2, count($this->scope->all()));
        
        $this->scope->where(array("answer" => $this->answer));
        
        $this->assertEquals(1, count($this->scope->all())); // where on queried scope
        
    }    
    
    public function test_all() {
        // set context conditions
        $this->scope = new Scope('Answer');
        
        $this->assertEquals(1, count($this->scope->all()));
        
        $this->model1 = Factory::create('Answer');
        
        $this->assertEquals(1, count($this->scope->all()));
        
        $this->assertEquals(2, count($this->scope->reload()->all()));
    }
    
    public function test_reload() {
        // set context conditions
        $this->scope = new Scope('Result');
        
        $this->assertEquals(0, count($this->scope->all()));
        
        Factory::create('Result');
        
        $this->assertEquals(1, count($this->scope->reload()->all()));
        
    }
    
    public function test_select() {
        
    }
}
