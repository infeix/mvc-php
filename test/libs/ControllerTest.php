<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class ControllerTest extends TestCase {
 
    public function setUp()
    {
        Factory::clean_database();
    }

    function test_initialize() {
        // prepare
        $_POST['test_string1'] = 'test_string2';
        // act
        $controller = new Controller();
        // check
        $this->assertTrue(isset($controller->params['test_string1']));
        $this->assertEquals('test_string2', $controller->params['test_string1']);
        $this->assertTrue(isset($controller->view));
        $this->assertTrue(is_a($controller->view, "View"));
        $this->assertTrue(isset($controller->view->resource));
        $this->assertTrue(is_array($controller->view->resource));
        $this->assertTrue(isset($controller->power));
        $this->assertTrue(is_a($controller->power, "Power"));
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_set_resource() {
        // prepare
        Session::init();
        $controller = new Controller();
        // act
        $result = $controller->set_resource('test_string3', 'test_string4');
        // check
        $this->assertTrue(isset($result->view->resource['test_string3']));
        $this->assertEquals('test_string4', $result->view->resource['test_string3']);
        $this->assertEquals('test_string4', Session::get('test_string3'));
    }
    
    
    /**
     * @runInSeparateProcess
     */
    function test_add_to_resource() {
        // prepare
        Session::init();
        $controller = new Controller();
        // act
        $result = $controller->add_to_resource('test_string3', 'test_string4');        
        // check
        $this->assertTrue(isset($result->view->resource['test_string3']));
        $this->assertEquals('test_string4', $result->view->resource['test_string3']);
        $this->assertEquals('test_string4', Session::get('test_string3'));
        // act again
        $controller->add_to_resource('test_string3', 'test_string6');
        // check
        $this->assertTrue(isset($result->view->resource['test_string3']));
        $this->assertEquals('test_string4test_string6', $result->view->resource['test_string3']);
        $this->assertEquals('test_string4test_string6', Session::get('test_string3'));
    }
    
    function test_get_resource() {
        // prepare
        $controller = new Controller();
        $controller->view->resource = array('test_string3' => 'test_string6');
        // act
        $this->assertEquals('test_string6', $controller->get_resource('test_string3'));
        $this->assertTrue(is_array($controller->get_resource()));
        $this->assertEquals('test_string6', $controller->get_resource()['test_string3']);     
    }
    
    function test_get_param()
    {
        // prepare
        $_POST['test_string1'] = 'test_string2';
        // act
        $controller = new Controller();
        // check
        $this->assertEquals('test_string2', $controller->get_param('test_string1'));
        $this->assertEquals(NULL, $controller->get_param('sefdfasf'));
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_render()
    {
        // prepare
        Session::init();
        // Create a stub for the SomeClass class.
        $stub = $this->createMock(View::class);

        // Configure the stub.
        $stub->method('render')
             ->willReturn('test_string7');
        $stub->resource = array();
        
        $controller = new Controller();
        $controller->view = $stub;
        // act
        $result = $controller->render('test_string8');
        // check
        $this->assertEquals('test_string8', $controller->get_resource('view'));
        $this->assertEquals('test_string7', $result);
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_redirect_to() {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = "test_string10";
        $controller = new Controller();
        
        // act
        $result = $controller->redirect_to("test_string9");

         // Assert
        $this->assertEquals('REDIRECT',$result);
        $this->assertContains(
          "Location: https://test_string10/test_string9", xdebug_get_headers()
        );
    }
    
    /**
     * @runInSeparateProcess
     */
    function test_show_msg() {
        // prepare
        Session::init();
        $controller = new Controller();
        // act
        $result = $controller->show_msg('test_string4');        
        // check
        $this->assertTrue(isset($result->view->resource['msg']));
        $this->assertEquals('test_string4', $result->view->resource['msg']);
        $this->assertEquals('test_string4', Session::get('msg'));
        // act again
        $controller->show_msg('test_string6'); 
        // check
        $this->assertEquals('test_string4test_string6', $result->view->resource['msg']);
        $this->assertEquals('test_string4test_string6', Session::get('msg'));
    }
    
    
    function test_add_params() {
        // prepare
        $controller = new Controller();
        $_POST['test_string1'] = 'test_string2';
        $this->assertFalse(isset($controller->params['test_string1']));
        // act
        $controller->add_params();
        // check
        $this->assertTrue(isset($controller->params['test_string1']));
        $this->assertEquals('test_string2', $controller->params['test_string1']);
    }
}

