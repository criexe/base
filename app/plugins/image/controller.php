<?php


class controller_image extends controller
{

    public function __construct() { parent::__construct(); }


    public function upload()
    {
        $file = validator::input('image', 'file') or die('error');

        echo $file;
    }

}


?>