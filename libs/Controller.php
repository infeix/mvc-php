<?php

class Controller {

    function __construct() {
        $this->add_params();
        $this->view = new View();
        $this->view->resource = array();
        $this->power = new Power();
    }
    
    function set_resource($key, $value)
    {
        Session::set($key, $value);
        $this->view->resource[$key] = $value;
        return $this;
    }
    
    function add_to_resource($key, $value)
    {
        Session::add($key, $value);
        if(!isset($this->view->resource[$key]))
        {
            $this->view->resource[$key] = '';
        }
        $this->view->resource[$key] .= $value;
        return $this;
    }
    
    function get_resource($key = '')
    {
        if(empty($key))
        {
            return $this->view->resource;
        }
        return $this->view->resource[$key];
    }
    
    function get_param($key)
    {
        if(isset($this->params[$key]))
        {
            return $this->params[$key];
        }
        return NULL;
    }
    
    function render($view) {
        $this->set_resource ('view', $view);
        return $this->view->render($view);
    }
    
    function redirect_to($controller) {
        $ssl      = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' );
        $protocol = 'http' . ( ( $ssl ) ? 's' : '' );
        $url = "Location: {$protocol}://{$_SERVER['HTTP_HOST']}/{$controller}";
        
        header($url);
        return 'REDIRECT';
    }
    
    function show_msg($msg) {
        $this->add_to_resource ('msg', $msg);
        return $this;
    }
    
    function add_params() {
        if(is_array($_POST))
        {
            $this->params = array();
            foreach($_POST as $key => $value)
            {
                $this->params[$key] = $value;
            }
        }
    }
    
}