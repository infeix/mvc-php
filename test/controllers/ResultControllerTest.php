<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';
require_once 'controllers/ResultController.php';

class ResultControllerTest extends TestCase {
    
    
    public function setUp()
    {
        Session::init();
        Factory::clean_database();
        $this->current_user = Factory::create("User");
        $_SESSION['current_user'] = $this->current_user->CID;
        $this->host = "localhost";
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = "de-DE";
        $_SERVER["HTTP_HOST"] = $this->host;
    }

    /**
     * @runInSeparateProcess
     */
    public function test_initialisation()
    {
        // Act
        $a = new ResultController();
        
        // Assert
        $this->assertFalse($a->questions_scope);
        $this->assertEquals("views/Layout/WelcomeTemplate.php", $a->view->current_layout);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_initialisation_with_finished()
    {
        Factory::create("Finished", array('user' => $this->current_user));
        // Act
        $a = new ResultController();
        
        // Assert
        $this->assertEquals("Scope", get_class($a->questions_scope));
        $this->assertEquals("views/Layout/WelcomeTemplate.php", $a->view->current_layout);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_index_without_finished()
    {
        $a = new ResultController();
        
        // Create a stub for the SomeClass class.
        $stub = $this->createMock(View::class);

        // Configure the stub.
        $stub->method('render')
             ->willReturn("NOT_REDIRECT");
        
        $a->view = $stub;
        // Act
        $return = $a->index();
        
        // Assert
        $this->assertEquals('REDIRECT', $return);
        $this->assertContains(
          "Location: http://{$this->host}/Welcome/index", xdebug_get_headers()
        );
        $this->assertEquals('Es ist ein Fehler aufgetreten.<br/>', Session::get('msg'));
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_index()
    {
        Factory::create("Finished", array('user' => $this->current_user));
        $question = Factory::create("Question");
        $question_l = Factory::create("Question_label", array('lang' => 'DE', 'question' => $question));
        $answer = Factory::create("Answer", array("question" => $question));
        $answer_l = Factory::create("Answer_label", array('lang' => 'DE', 'answer' => $answer));
        $result = Factory::create("Result", array('answer'=> $answer));
        // Arrange
        $a = new ResultController();

        // Create a stub for the SomeClass class.
        $stub = $this->createMock(View::class);

        // Configure the stub.
        $stub->method('render')
             ->willReturn("something nice rendered");
        
        $a->view = $stub;
        
        // Act
        $return = $a->index();
        
        // Assert
        $this->assertEquals('something nice rendered', $return);
        $resource_results = Session::get("Results");
        $resource_finished = Session::get("Finished");
        
        $this->assertEquals(1, $resource_finished);
        $this->assertTrue(isset($resource_results[$question->id]));
        $this->assertEquals($question->id, $resource_results[$question->id]->id);
        $this->assertEquals($question_l->label, $resource_results[$question->id]->label);
        $this->assertTrue(isset($resource_results[$question->id]->answer_scope));
        $answers = $resource_results[$question->id]->answer_scope->all();
        $this->assertEquals($answer->id, $answers[$answer->id]->id);
        $this->assertEquals($answer_l->label, $answers[$answer->id]->label);
        $this->assertTrue(isset($answers[$answer->id]->result_scope));
        $results = $answers[$answer->id]->result_scope->all();
        $this->assertTrue(isset($results[$result->id]));
        $this->assertEquals($result->id, $results[$result->id]->id);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_create()
    {
        Factory::delete_all_of("Answer_label");
        Factory::delete_all_of("Answer");
        Factory::delete_all_of("Question_label");
        Factory::delete_all_of("Question");
        $question = Factory::create("Question");
        $question_l = Factory::create("Question_label", array('lang' => 'DE', 'question' => $question));
        $answer1 = Factory::create("Answer", array("question" => $question));
        $answer1_l = Factory::create("Answer_label", array('lang' => 'DE', 'answer' => $answer1));
        $answer2 = Factory::create("Answer", array("question" => $question));
        $answer2_l = Factory::create("Answer_label", array('lang' => 'DE', 'answer' => $answer2));
        $_POST['Results'] = '[{"name":"question-' . $question->id . '","value":"' . $answer1->id . '"}]';

        $a = new ResultController();
        
        // Act
        $return = $a->create();
        
        // Assert
        $this->assertEquals('REDIRECT', $return);
        $results = Result::scope();
        
        // Assert
        $this->assertContains(
          "Location: http://{$this->host}/result/index", xdebug_get_headers()
        );
          
        $this->assertEquals(1, count(Finished::scope()->all()));
        $this->assertEquals(1, count($results->all()));
        $this->assertEquals($answer1->id, $results->first()->answer_id);
        
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_create_with_rollback()
    {
        Factory::delete_all_of("Answer_label");
        Factory::delete_all_of("Answer");
        Factory::delete_all_of("Question_label");
        Factory::delete_all_of("Question");
        $question = Factory::create("Question");
        $question_l = Factory::create("Question_label", array('lang' => 'DE', 'question' => $question));
        $answer1 = Factory::create("Answer", array("question" => $question));
        $answer1_l = Factory::create("Answer_label", array('lang' => 'DE', 'answer' => $answer1));
        $answer2 = Factory::create("Answer", array("question" => $question));
        $answer2_l = Factory::create("Answer_label", array('lang' => 'DE', 'answer' => $answer2));
        $question2 = Factory::create("Question");
        $question2_l = Factory::create("Question_label", array('lang' => 'DE', 'question' => $question2));
        $answer2_1 = Factory::create("Answer", array("question" => $question2));
        $answer2_1_l = Factory::create("Answer_label", array('lang' => 'DE', 'answer' => $answer2_1));
        $answer2_2 = Factory::create("Answer", array("question" => $question2));
        $answer2_2_l = Factory::create("Answer_label", array('lang' => 'DE', 'answer' => $answer2_2));
        $_POST['Results'] = '[{"name":"question-' . $question->id . '","value":"' . $answer1->id . '"}]';

        $a = new ResultController();
        
        // Act
        $return = $a->create();
        
        // Assert
        $this->assertEquals('REDIRECT', $return);
        $results = Result::scope();
        
        // Assert
        $this->assertContains(
          "Location: http://{$this->host}/question/index", xdebug_get_headers()
        );
          
        $this->assertEquals(0, count(Finished::scope()->all()));
        $this->assertEquals(0, count($results->all()));
        $this->assertFalse($results->exists());
        $this->assertEquals('Fehler beim Speichern Ihrer Daten.<br/>', Session::get('msg'));
        
    }
}