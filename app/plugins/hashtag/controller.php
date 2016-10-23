<?php


class controller_hashtag extends controller
{

    public function __construct(){ parent::__construct(); }


    public function index($hashtag = null)
    {
        if($hashtag == null) sys::location('/');

        $data = [

            'hashtag' => $hashtag
        ];

        $this->view('post_area', $data);
    }

}


?>