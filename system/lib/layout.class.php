<?php

class layout
{

    public static function content()
    {
        return cx::data('layout_content');
    }


    public static function set($name = null)
    {
        cx::data('layout', $name);
    }


    public static function get()
    {
        return cx::data('layout');
    }


    public static function name()
    {
        return cx::data('layout');
    }

}