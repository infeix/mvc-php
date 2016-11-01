<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class RouterTest extends TestCase {

    public function setUp()
    {
        Session::init();
        $_GET["url"] = '';
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_initiate_without_url() {
        $router = new Router();
        $this->assertEquals("welcome/index", $router->controller_url);
        $this->assertEquals("WelcomeController", $router->controller_class);
        $this->assertEquals("index", $router->controller_function);
        $this->assertEquals(NULL, $router->controller_argument);
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_initiate_not_correct_controller() {
        // prepare
        $url = Factory::generate_random_string();
        $lower_url = strtolower($url);
        $_GET["url"] = $url;
        
        // act
        $router = new Router();
        
        // check
        $this->assertEquals("Translation not found for label .{$lower_url}/index.controller_not_found<br/>", Session::get('msg'));
        $this->assertEquals("error/index", $router->controller_url);
        $this->assertEquals("ErrorController", $router->controller_class);
        $this->assertEquals("index", $router->controller_function);
        
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_initiate_not_correct_function() {
        // prepare
        $_GET["url"] = "User/";
        
        // act
        $router = new Router();
        
        // check
        $this->assertEquals("Translation not found for label .user/index.controller_not_found<br/>", 
                            Session::get('msg'));
        $this->assertEquals("error/index", $router->controller_url);
        $this->assertEquals("ErrorController", $router->controller_class);
        $this->assertEquals("index", $router->controller_function);
        
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_initiate_correct_controller_and_function() {
        // prepare
        $url = "user/login";
        $_GET["url"] = $url;
        
        // act
        $router = new Router();
        
        // check
        $this->assertEquals($url, $router->controller_url);
        $this->assertEquals("UserController", $router->controller_class);
        $this->assertEquals("login", $router->controller_function);       
        $this->assertTrue(isset($router->controller));         
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_execute_controller() {
        // prepare
        $url = "user/login";
        $_GET["url"] = $url;
        $test_string = 'Somthing total crazy';
        $router = new Router();
        // Create a stub for the SomeClass class.
        $stub = $this->createMock(UserController::class);

        // Configure the stub.
        $stub->method('login')
             ->willReturn($test_string);
        
        $router->controller = $stub;
        
        // act & check
        $this->assertEquals($test_string, $router->execute_controller());
    }
    
}
