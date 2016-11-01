<?php

class Router {
    
    function __construct() {
        Filter::get('GET', $_GET);
        $this->set_controller();        
        
        if(file_exists($this->controller_path))
        {
            if(file_exists($this->model_path))
            {
                require_once $this->model_path;
            }
            require_once $this->controller_path;
        }
        else
        {      
            $this->controller_not_found();
        }
        try 
        {
            $this->controller = new $this->controller_class();
        
            if(!method_exists($this->controller, $this->controller_function))
            {
                $this->controller_not_found();
            }
        } 
        catch (Exception $ex) 
        {
            $this->show_error($ex->getMessage());
            $this->controller = new $this->controller_class();
        }
    }
    
    public function execute_controller() {
        Session::set('controller', $this->controller_url);
        return $this->controller->{$this->controller_function}($this->controller_argument);
    }
    
    public function show_error($str)
    {
        Session::add('msg', $str.  '<br/>');
        $this->set_controller('Error'); 
        require_once $this->controller_path;
    }
    
    public function controller_not_found()
    {
        $this->show_error(i18n::get('.{:path:}.controller_not_found', array('path' => $this->controller_url)));
    }
    
    private function generate_controller_array($route)
    {
        $controller_arr = explode('/', rtrim($route, '/'));
        $controller_arr[0] = empty($controller_arr[0]) ? 'welcome' : $controller_arr[0];
        $controller_arr[1] = empty($controller_arr[1]) ? 'index' : $controller_arr[1];
        return $controller_arr;
    }
    
    private function set_controller($route = '')
    {
        if(empty($route))
        {
            $route = Filter::get('GET')->validate_string("url");
        }
        $controller = $this->generate_controller_array(strtolower($route));
        $cls = ucfirst(strtolower($controller[0]));
        $this->controller_class = $cls.'Controller';
        $this->controller_function = $controller[1];
        $this->controller_argument = isset($controller[2]) ? $controller[2] : NULL;
        $this->controller_url = implode("/", $controller);
        $this->controller_path = 'controllers/' . $this->controller_class . '.php';
        $this->model_path = 'models/' . $cls . '.php';
    }
}