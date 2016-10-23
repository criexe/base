<?php

return [

    [
        'icon'    => '<i class="fa fa-database"></i>',
        'display' => 'Database Manager',

        'sub' =>
            [
                [
                    'display' => 'Tables',
                    'type'    => 'list',
                    'link'    => 'db_tables'
                ],
                [
                    'display' => 'Columns',
                    'type'    => 'list',
                    'link'    => 'db_columns'
                ],
            ]
    ]

];

?>