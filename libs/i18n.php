<?php

class i18n
{

    static $translation = array();

    static function get($str, $argument = [])
    {
        try {
            if(Session::has('lang') === false)
            {
                $lang = self::lang();
                Session::set('lang', $lang);
            }
            $lang = Session::get('lang');
            $file = "config/i18n/{$lang}.php";
            if(!file_exists($file))
            {
                throw new Exception();
            }            
            if(!isset(self::$translation[$str]))
            {
                self::$translation = include_once ($file);
                if(!isset(self::$translation[$str]))
                {
                    throw new Exception();
                }
            }
            $str = self::$translation[$str];
        } catch (Exception $exc) {
            $str = "Translation not found for label {$str} language: {$lang}";
        }

        foreach($argument as $key => $value)
        {
            $str = str_replace("{:{$key}:}", $value, $str);
        }
        return $str; // TODO
    }

    static function lang()
    {
        if($_SERVER['HTTP_ACCEPT_LANGUAGE'])
        {
            $lang = preg_split( '/;|,|=/', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            
            if(strlen($lang[0] > 2))
            {
                return  strtoupper(substr($lang[0], 0, 2));
            }
            if(strlen($lang[1] > 2))
            {
                return  strtoupper(substr($lang[1], 0, 2));
            }
        }
        return  "DE";
    }
}
