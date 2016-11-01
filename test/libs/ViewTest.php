<?php

use PHPUnit\Framework\TestCase;
require_once 'load_things.php';
require_once 'test/load_things.php';

class ViewTest extends TestCase {

    public function setUp()
    {
        $this->view = new View();
    }
    
    public function test_view()
    {        
        $this->assertEquals('views/Layout/',$this->view->path_to_layouts);
        $this->assertEquals('views/',$this->view->path_to_views);
    }
    
    public function test_set_layout_missing_layout()
    {
        $this->view->set_layout(Factory::generate_random_string());
        
        $this->assertFalse($this->view->layout());
    }
    
    public function test_set_layout_correct()
    {
        $this->view->set_layout("WelcomeTemplate");
        
        $this->assertTrue(file_exists($this->view->layout()));
        
    }
    
    public function test_set_view_missing_view()
    {
        $this->view->set_view(Factory::generate_random_string());
        
        $this->assertFalse($this->view->view());
    }
    
    public function test_view_correct()
    {
        $test_string = Factory::generate_random_string();
        $this->view->current_view = $test_string;
        
        $this->assertEquals($test_string, $this->view->view());
        
    }
    
    
    public function test_view_missing_view()
    {
        $this->view->current_view = NULL;
        
        $this->assertFalse($this->view->view());
    }
    
    public function test_layout_correct()
    {
        $test_string = Factory::generate_random_string();
        $this->view->current_layout = $test_string;
        
        $this->assertEquals($test_string, $this->view->layout());
        
    }
    
   
    public function test_layout_missing_layout()
    {        
        $this->assertFalse($this->view->layout());
    }
    
    public function test_set_view_correct()
    {        
        $this->view->set_view("Error/index");
        
        $this->assertTrue(file_exists($this->view->view()));
        
    }
    
    public function test_render_view_with_layout() {
        $this->expectOutputString('LayoutView');
        $this->view->current_view = 'test/factories/fixtures/TestView.php';
        $this->view->current_layout = 'test/factories/fixtures/TestLayout.php';
        
        $this->view->render_view_with_layout();
    }
    
    public function test_render_view_without_layout() {
        $this->expectOutputString('View');
        $this->view->current_view = 'test/factories/fixtures/TestView.php';
        
        $this->view->render_view_with_layout();
    }
    
    
    public function test_render() {
        $this->expectOutputString('LayoutView');
        $this->view->path_to_views = 'test/factories/fixtures/';
        $this->view->path_to_layouts = 'test/factories/fixtures/';
        $this->view->current_layout = 'test/factories/fixtures/TestLayout.php';
        
        $this->view->render("TestView");
    }
    
    
    public function test_render_missing_view() {
        $this->view->path_to_views = 'test/factories/fixtures/';
        $this->view->path_to_layouts = 'test/factories/fixtures/';
        $this->view->current_layout = 'test/factories/fixtures/TestLayout.php';
        
        $result = $this->view->render(Factory::generate_random_string());
        $this->assertEquals($result, "MISSING_VIEW");
    }
    
}
