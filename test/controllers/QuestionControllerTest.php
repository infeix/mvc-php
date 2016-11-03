<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';
require_once 'controllers/QuestionController.php';

class QuestionControllerTest extends TestCase {
    
    public function setUp()
    {
        Session::init();
        Factory::clean_database();
        $this->current_user = Factory::create("User");
        $_SESSION['current_user'] = $this->current_user->CID;
        $this->host = "localhost";
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = "en,de-DE";
        $_SERVER["HTTP_HOST"] = $this->host;
    }

    /**
     * @runInSeparateProcess
     */
    public function test_initialisation()
    {
        // Act
        $a = new QuestionController();
        
        // Assert
        $this->assertEquals("Scope", get_class($a->questions_scope));
        $this->assertEquals("views/Layout/WelcomeTemplate.php", $a->view->current_layout);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_initialisation_with_finished()
    {
        Factory::create("Finished", array('user' => $this->current_user));
        
        // Act
        new QuestionController();

        // Assert
        $this->assertContains(
          "Location: http://{$this->host}/result/index", xdebug_get_headers()
        );
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_index()
    {
        $question = Factory::create("Question");
        $question_l = Factory::create("Question_label", array('lang' => 'DE', 'question' => $question));
        $answer = Factory::create("Answer", array("question" => $question));
        $answer_l = Factory::create("Answer_label", array('lang' => 'DE', 'answer' => $answer));
        // Arrange
        $a = new QuestionController();

        // Create a stub for the SomeClass class.
        $stub = $this->createMock(View::class);

        // Configure the stub.
        $stub->method('render')
             ->willReturn("something nice rendered");
        
        $a->view = $stub;
        
        // Act
        $result = $a->index();
        
        // Assert
        $this->assertEquals('something nice rendered', $result);
        $resources = Session::get("Questions");
        
        $this->assertTrue(isset($resources[$question->id]));
        $this->assertEquals($question->id, $resources[$question->id]->id);
        $this->assertTrue(isset($resources[$question->id]->answer_scope));
        $this->assertEquals($answer->id, $resources[$question->id]->answer_scope->elements[$answer->id]->id);
    }    
}