<?php

return [

    'defaults' => [
        'controller' => 'home',
        'method'     => 'index'
    ],




    // Routers
    'router' => [

        '#^home$#i'     => 'home',
        '#^login$#i'    => 'user/login_form',
        '#^register$#i' => 'user/register_form',
        '#^logout$#i'   => 'user/logout',

        '#^hashtag/(\w+)#' => 'hashtag/index/$1'
    ]

];

?>