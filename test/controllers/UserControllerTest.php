<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

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
    public function test_login_correct_and_not_finished()
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
    public function test_login_correct_and_finished()
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
    
    /**
     * @runInSeparateProcess
     */
    public function test_login_not_correct()
    {
        Session::set('lang', "DE");
        $_SERVER["HTTP_HOST"] = $this->host;
        
        $this->finished = Factory::create("Finished", array("user" => $this->current_user));
        $_POST["Email"] = $this->current_user->Email;
        $_POST["Password"] = "wrong pass";
        // Arrange
        $a = new UserController();

        // Act
        $result = $a->login();
        
        // Assert
        $this->assertEquals("Login fehlgeschlagen überprüfen Sie Ihre Eingabe.<br/>", Session::get('msg'));
        $this->assertEquals('REDIRECT', $result);
        $this->assertContains(
          "Location: http://{$this->host}/Welcome", xdebug_get_headers()
        );
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_logout()
    {
        Session::set('lang', "DE");
        $_SESSION['current_user'] = $this->current_user->CID;
        $_SERVER["HTTP_HOST"] = $this->host;
        
        $this->finished = Factory::create("Finished", array("user" => $this->current_user));
        $_POST["Email"] = $this->current_user->Email;
        $_POST["Password"] = "wrong pass";
        // Arrange
        $a = new UserController();
        $this->assertTrue($a->power->got_user());
        // Act
        $result = $a->logout();
        $this->assertFalse(isset($_SESSION['current_user']));
        // Assert
        $this->assertEquals('REDIRECT', $result);
        $this->assertContains(
          "Location: http://{$this->host}/Welcome", xdebug_get_headers()
        );
    }
}