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
        $this->test_mail = Factory::generate_random_mail();
        $this->test_name = Factory::generate_random_string();
        $this->user = Factory::create("User", 
                                      array('Firstname' => $this->test_name, 
                                            "Email" => $this->test_mail));      
        
        $this->finished = new Finished();
        $this->answer = new Answer();
        $this->user_factory = Factory::build("User");
    }
    
    public function test_get_pk_value() {
        $property = array("Firstname" => "Peter");
        $this->assertEquals($this->test_mail, $this->user->Email);
        
        $this->user2 = Factory::create("User", $property);
        $this->user2_ = User::scope()->find_by($property);
        $this->assertEquals($this->user2_->Firstname, $this->user2->Firstname);
        $this->assertEquals(Model::get_pk_value($this->user2_), Model::get_pk_value($this->user2));
        
    }
    
    
    public function test_write_object_property() {
        $this->finished->user_id = 12;
        $model_property = array("user" => $this->user);
        $factory_property = array("user" => $this->user_factory);
        
        
        $this->finished->write_properties($factory_property);
        
        $this->assertEquals($this->user_factory, $this->finished->user);
        $this->assertFalse(isset($this->finished->user_id));
        
        $this->finished->write_properties($model_property);
        
        $this->assertTrue(isset($this->finished->user));
        $this->assertEquals($this->user, $this->finished->user);
        
    }
    
    public function test_write_properties() {
        $properties = array("Email" => "info@ultra.de", "Firstname" => "Peter");
        
        $this->assertEquals($this->test_mail, $this->user->Email);
        $this->assertEquals($this->test_name, $this->user->Firstname);
        
        $this->user->write_properties($properties);
        
        $this->assertEquals("info@ultra.de", $this->user->Email);
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
    
    
    public function test_save_scope() {
        // prepare
        $this->question = Factory::create("Question");
        
        $answer_scope = Answer::scope();
        $this->assertEquals(0, count($answer_scope->all()));
        
        $question_scope = Question::scope()->find(Model::get_pk_value($this->question), true);
        $this->answer->question = $question_scope;
        $this->setExpectedException(Exception::class);
        $this->answer->save();
        
        $this->assertEquals(0, count($answer_scope->reload()->all()));
    }
    
    public function test_validate_existence()
    {
        $this->question = new Question();
        $this->setExpectedException(Exception::class);
        $this->question->validate_existence('sort_index');
        Session::get('msg', "Fehler beim Speichern. (Question.sort_index fehlt)");
        
        $this->question->sort_index = 5;
        $this->assertTrue($this->question->validate_existence('sort_index'));
    }
    
    public function test_to_fk()
    {
        $user_id1 = Model::to_fk(new User());       
        $this->assertEquals("user_id", $user_id1);
        $user_id2 = Model::to_fk("user");   
        $this->assertEquals("user_id", $user_id2);
        $user_id3 = Model::to_fk("userk");
        $this->assertEquals("userk", $user_id3);
        $user_id4 = Model::to_fk(4);
        $this->assertEquals(4, $user_id4);
        $user_id5 = Model::to_fk("userk", "somethong else");
        $this->assertEquals("somethong else", $user_id5);
    }
    
    public function test_get_parent_without_any()
    {
        $answer = new Model();
        $this->setExpectedException(Exception::class);
        $answer->get_parent("question");
    }
    
    
    public function test_get_parent_with_wrong()
    {
        $answer = new Model();
        $answer->question_id = 42;
        $this->setExpectedException(Exception::class);
        $answer->get_parent("question");        
    }
    
    public function test_get_parent_with_model()
    {
        $question = Factory::create('Question', array('input_type' => 'fancy'));
        $answer = new Model();
        $answer->question = $question;
        $this->assertEquals($question, $answer->get_parent("question"));
    }
    
    public function test_get_parent_with_id()
    {
        $question = Factory::create('Question', array('input_type' => 'fancy'));
        $answer = new Model();
        
        $answer->question_id = $question->id;
        $parent = $answer->get_parent("question");
        $this->assertEquals($question->id, $parent->id);
        $this->assertEquals($question->input_type, $parent->input_type);
        $this->assertFalse(isset($answer->question_id));        
    }
}
