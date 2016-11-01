<?php

class DatabaseConnection {
    
    private $dbc = NULL;
    
    private function get_connection()
    {
        if(!isset($this->dbc))
        {
            $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die('Database Conection Failure' . mysqli_connect_error());

            mysqli_set_charset($this->dbc, 'utf8');
        }
        return $this->dbc;
    }
    
    public function send_query($query)
    {
        if(!empty($query))
        {
            return mysqli_query($this->get_connection(), $query);            
        }
    }
    
    public function inserted_id()
    {
        return mysqli_insert_id($this->get_connection());
    }
    
    public function check_str($str)
    {
        return mysqli_real_escape_string($this->get_connection(), $str);
    }
    
    public function close_connection() 
    {        
        mysqli_close($this->dbc);
        unset($this->dbc);
        unset($this);
    }
    
    public function error()
    {
        return mysqli_error($this->get_connection());
    }
}