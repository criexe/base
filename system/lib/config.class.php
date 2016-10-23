<?php

class config
{

    public static $config = [];


    public static function get($alias = null)
    {
        if($alias == null) return self::$config;
        if( ! array_key_exists($alias, $config)) return false;

        return self::$config[$alias];
    }


    public static function set($alias = null)
    {
        if($alias != null)
        {
            self::$config[$alias];
        }
    }

}