<?php

use PHPUnit\Framework\TestCase;
require 'load_things.php';
require 'test/load_things.php';

class UserControllerTest extends TestCase {
    
    public function setUp()
    {
        Session::init();
        Factory::clean_database();
        $this->current_user = Factory::create("User");
        $this->host = "localhost";
    }

    /**
     * @runInSeparateProcess
     */
    public function test_goto_question_when_login_correct_and_no_answers()
    {
        $_SERVER["HTTP_HOST"] = $this->host;
        $_POST["Email"] = $this->current_user->Email;
        $_POST["Password"] = $this->current_user->Password;
        // Arrange
        $a = new UserController();

        // Act
        $result = $a->login();
        
        // Assert
        $this->assertEquals('REDIRECT', $result);
        $this->assertContains(
          "Location: http://{$this->host}/Question", xdebug_get_headers()
        );
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_goto_result_when_login_correct_and_resuls()
    {
        $_SERVER["HTTP_HOST"] = $this->host;
        
        $this->finished = Factory::create("Finished", array("user" => $this->current_user));
        $_POST["Email"] = $this->current_user->Email;
        $_POST["Password"] = $this->current_user->Password;
        // Arrange
        $a = new UserController();

        // Act
        $result = $a->login();
        
        // Assert
        $this->assertEquals('REDIRECT', $result);
        $this->assertContains(
          "Location: http://{$this->host}/Result", xdebug_get_headers()
        );
    }
    
}