<?php

class emoji
{

    public function to_image($content = null)
    {
        if($content == null) return null;

        // Include
        require_once( SYSTEM_LIB_PATH . '/Emojione/autoload.php' );

        \Emojione\Emojione::$imagePathSVGSprites = './../../assets/sprites/emojione.sprites.svg';
        \Emojione\Emojione::$imagePathPNG        = SYS_ASSETS . '/cx/plugins/emojione/png/';
        \Emojione\Emojione::$imagePathSVG        = SYS_ASSETS . '/cx/plugins/emojione/svg/';

        return Emojione\Emojione::toImage($content);
    }

}