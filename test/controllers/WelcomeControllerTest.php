<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';
require_once 'controllers/WelcomeController.php';

class WelcomeControllerTest extends TestCase {
    
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
    public function test_index()
    {
        $_SERVER["HTTP_HOST"] = $this->host;
        $a = new WelcomeController();
        
        // Create a stub for the SomeClass class.
        $stub = $this->createMock(View::class);

        // Configure the stub.
        $stub->method('render')
             ->willReturn("something nice rendered");
        
        $a->view = $stub;
        
        $result = $a->index();
        $this->assertEquals('something nice rendered', $result);
        
    }
}