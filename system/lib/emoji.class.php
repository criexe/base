<?php

class emoji
{

    public static function to_image($content = null)
    {
        if($content == null) return null;

        require_once( SYSTEM_LIB_PATH . '/Emojione/autoload.php' );
        return Emojione\Emojione::toImage($content);
    }

}