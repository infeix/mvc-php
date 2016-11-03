<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';
require_once 'controllers/ErrorController.php';

class ErrorControllerTest extends TestCase {
    
    public function setUp()
    {
        Session::init();
        $this->host = "peterpan.de";
        $this->test_string = Factory::generate_random_string();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_index()
    {
        $_SERVER["HTTP_HOST"] = $this->host;
        
        // Arrange
        $a = new ErrorController();

        // Act
        $result = $a->index($this->test_string);
        
        // Assert
        $this->assertEquals('REDIRECT', $result);
        $this->assertContains(
          "Location: http://{$this->host}/Welcome/index", xdebug_get_headers()
        );
        $this->assertEquals($this->test_string."<br/>", Session::get('msg'));
    }    
}