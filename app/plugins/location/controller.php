<?php


class controller_location extends controller
{

    public function __construct(){ parent::__construct(); }


    function index()
    {
        $url = input::get('url');
        header('Location: ' . $url);
    }

}


?>