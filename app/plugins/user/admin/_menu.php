<?php

return [

    [
        'icon'    => '<i class="fa fa-user"></i>',
        'display' => 'Users',

        'sub' =>

            [
                [
                    'display' => 'Users',
                    'type'    => 'list',
                    'link'    => 'users'
                ],
                [
                    'display' => 'Follow',
                    'type'    => 'list',
                    'link'    => 'follow'
                ],
                [
                    'display' => 'Traffic',
                    'type'    => 'list',
                    'link'    => 'traffic'
                ]
            ]
    ]

];

?>