<?php

class View {
    
    function __construct() {
        $this->path_to_layouts = 'views/Layout/';
        $this->path_to_views = 'views/';
    }
    
    public function set_layout($str)
    {
        if(file_exists($this->path_to_layouts . $str . '.php'))
        {
            $this->current_layout = $this->path_to_layouts . $str . '.php';
            return true;
        }
        else 
        {
            $this->current_layout = NULL;
            return false;
        }
    }
    
    public function set_view($str)
    {
        if(file_exists($this->path_to_views . $str . '.php'))
        {
            $this->current_view = $this->path_to_views . $str . '.php';
            return true;
        }
        else 
        {
            $this->current_view = NULL;
            return false;
        }
    }
    
    function layout()
    {
        if(isset($this->current_layout) && $this->current_layout != NULL)
        {
            return $this->current_layout;
        }
        return false;
    }
    
    function view()
    {
        if(isset($this->current_view) && $this->current_view != NULL)
        {
            return $this->current_view;
        }
        return false;
    }
    
    
    public function render_view_with_layout()
    {
        if($this->layout())
        {
            require $this->layout();
        }
        else 
        {
            if($this->view())
            {
                require $this->view();
            }
        }
    }
    
    public function render($str)
    {
        if($this->set_view($str))
        {
            $this->render_view_with_layout();
            return "{$str} rendered";
        }
        return "MISSING_VIEW";
    }

}