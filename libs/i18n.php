<?php

class i18n
{

    static $translation = array();

    static function get($str, $argument = [])
    {
        try {
            $lang = i18n::lang();
            
            $file = "config/i18n/{$lang}.php";
            if(!file_exists($file))
            {
                throw new Exception($lang);
            }            
            if(!isset(self::$translation[$str]))
            {
                self::$translation = include ($file);
            }
            
            if(!isset(self::$translation[$str])) {
                throw new Exception($lang);
            } else {
                $str = self::$translation[$str];
            }
        } catch (Exception $exc) {
            $str = "Translation not found for label {$str}. ({$exc->getMessage()})";
        }

        foreach($argument as $key => $value)
        {
            $str = str_replace("{:{$key}:}", $value, $str);
        }
        return $str;
    }

    static function lang()
    {
        if(Session::has('lang') === false)
        {
            self::lang_to_session();
        }        
        return Session::get('lang');
    }
    
    static function lang_to_session() {
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            $lang = preg_split( '/;|,|=/', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

            if(strlen($lang[0]) > 2)
            {
                Session::set('lang', strtoupper(substr($lang[0], 0, 2)));
            }
            elseif(strlen($lang[1]) > 2)
            {
                Session::set('lang', strtoupper(substr($lang[1], 0, 2)));
            }
        }
        else
        {
            Session::set('lang', "DE");            
        }
    }
}
