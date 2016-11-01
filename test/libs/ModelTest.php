<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class ModelTest extends TestCase {
    
    
    
    public function setUp()
    {
        Factory::clean_database();
        foreach(Answer::scope()->all() as $answer)
        {
            $answer->delete();
        }
        
        $this->user = Factory::create("User", 
                                      array('Firstname' => "some name", 
                                            "CID" => 1337));        
//        $this->user->Firstname = "some name";
//        $this->user->CID = 1337;
        
        $this->finished = new Finished();
        $this->answer = new Answer();
        $this->user_factory = Factory::build("User");
    }
    
    public function test_get_pk_value() {
        $property = array("Firstname" => "Peter");
        $this->assertEquals(1337, Model::get_pk_value($this->user));
        
        $this->user2 = Factory::create("User", $property);
        $this->user2_ = User::scope()->find_by($property);
        $this->assertEquals($this->user2_->Firstname, $this->user2->Firstname);
        $this->assertEquals(Model::get_pk_value($this->user2_), Model::get_pk_value($this->user2));
        
    }
    
    
    public function test_write_object_property() {
        $model_property = array("user" => $this->user);
        $factory_property = array("user" => $this->user_factory);
        
        
        $this->finished->write_properties($factory_property);
        
        $this->assertEquals($this->user_factory, $this->finished->user);
        $this->assertFalse(isset($this->finished->user_id));
        
        $this->finished->write_properties($model_property);
        
        $this->assertFalse(isset($this->finished->user));
        $this->assertEquals($this->finished->user_id, $this->user->CID);
        
    }
    
    public function test_write_properties() {
        $properties = array("CID" => 42, "Firstname" => "Peter");
        
        $this->assertEquals(1337, Model::get_pk_value($this->user));
        $this->assertEquals("some name", $this->user->Firstname);
        
        $this->user->write_properties($properties);
        
        $this->assertEquals(42, Model::get_pk_value($this->user));
        $this->assertEquals("Peter", $this->user->Firstname);
        
    }
    
    public function test_scope() {
        
        $scope1 = Model::scope();
        
        $this->assertEquals("Model", $scope1->models[0]);
        $this->assertEquals("Scope", get_class($scope1));
        
        $scope2 = User::scope();
        
        $this->assertEquals("User", $scope2->models[0]);
        $this->assertEquals("Scope", get_class($scope2));
        
    }
    
    public function test_save() {
        // prepare
        $this->question = Factory::create("Question");
        
        $answer_scope = Answer::scope();
        $this->assertEquals(0, count($answer_scope->all()));
        
        $this->answer->write_properties(array("question" => $this->question));
        $this->answer->save();
        
        $this->assertTrue(isset($this->answer->id));
        
        $this->assertEquals(1, count($answer_scope->reload()->all()));
        
    }
    
    
    public function test_get_parent_pk() {
        // prepare
        $this->question = new Question();
        $this->question->input_type = 'checkbox';
        $this->question->multi_select = 1;
        $this->question->sort_index = 12;
        $this->question->save();
        $this->assertTrue(isset($this->question->id));
        
        $answer_scope = Answer::scope();
        $this->assertEquals(0, count($answer_scope->all()));
        
        $this->answer = new Answer();
        $this->answer->question_id = $this->question->id;
        $this->answer->save();        
        $this->assertTrue(isset($this->answer->id));        
        $this->assertEquals(1, count($answer_scope->reload()->all()));
        
        $this->assertEquals($this->question->id, $this->answer->get_parent_pk('question'));
    }
    
    
    public function test_get_parent_pk2() {
        // prepare
        $this->question = new Question();
        $this->question->input_type = 'checkbox';
        $this->question->multi_select = 1;
        $this->question->sort_index = 12;
        $this->question->save();
        $this->assertTrue(isset($this->question->id));
        
        $answer_scope = Answer::scope();
        $this->assertEquals(0, count($answer_scope->all()));
        
        $this->answer = new Answer();
        $this->answer->question = $this->question;
        $this->answer->save();        
        $this->assertTrue(isset($this->answer->id));        
        $this->assertEquals(1, count($answer_scope->reload()->all()));
        
        $this->assertEquals($this->question->id, $this->answer->get_parent_pk('question'));
    }
}
