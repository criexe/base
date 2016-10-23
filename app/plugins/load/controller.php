<?php


class controller_load extends controller
{

    public function __construct(){ parent::__construct(); }



    public function website()
    {
        $uri = input::get('uri');

        $data = ['uri' => $uri];

        $this->view('website', $data, false);
    }

}


?>