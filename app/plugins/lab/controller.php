<?php

class controller_lab extends controller
{

    public function __construct(){ parent::__construct(); }


    public function index()
    {
        $deneme = input::get("emoji");

        echo emoji::to_image($deneme);
    }

}

?>