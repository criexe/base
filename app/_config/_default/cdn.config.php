<?php


return [


    'active' => 'base.criexe.net',


    'base.criexe.net' => [

        'upload' => function($file){

            $upload = net::connect([

                'url' => 'http://base.criexe.net/image/upload',

                'post' => true,
                'safe_upload' => true,
                'data' => [

                    'file' => new CurlFile($file)
                ]

            ]);
        },

        'display' => function($path){

            return 'http://base.criexe.net/' . $path;
        }
    ]


];


?>