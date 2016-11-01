<?php

class Session {

    public static $current;
    public static $last;
    
    static function init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_SESSION['s']))
        {
            self::$current = unserialize($_SESSION['s']);
            self::$last = unserialize($_SESSION['s']);
            $_SESSION['last_s'] = $_SESSION['s'];
        }
        else {
            self::$current = new Session();
        }
        $_SESSION['s'] = serialize(self::$current);
    }
    
    static function add($key, $value)
    {
        if(!isset(self::$current->$key))
        {
            self::$current->$key = '';
        }
        self::$current->$key .= $value;
        $_SESSION['s'] = serialize(self::$current);
    }
    
    static function set($key, $value)
    {        
        self::$current->$key = $value;
        $_SESSION['s'] = serialize(self::$current);
    }
    
    static function get($key, $old = false)
    {
        if(!$old && isset(self::$current->$key))
        {
           return self::$current->$key;
        }
        if(isset(self::$last->$key))
        {
           return self::$last->$key;
        }
        return NULL;
    }
    
    static function pop($key, $old = false)
    {
        if(!$old && isset(self::$current->$key))
        {
            $result = self::$current->$key;
            unset(self::$current->$key);
            $_SESSION['s'] = serialize(self::$current);
            return $result;
        }
        if(isset(self::$last->$key))
        {
           return self::$last->$key;
        }
        return NULL;
    }
    
    static function has($key)
    {
        if(isset(self::$current->$key))
        {
           return true;
        }
        return false;
    }
    
    static function remove($key)
    {
        $result = self::$current->$key;
        unset(self::$current->$key);
        $_SESSION['s'] = serialize(self::$current);
        return $result;
    }    
}