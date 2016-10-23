<?php

return [

    'name'             => 'Verified',
    'db_type'  => 'int(1)',
    'display_function' => function($data = null){

        if($data == '1' || $data == 1)
            return '<i class="user-verified-icon text-success fa fa-check-circle" data-toggle="tooltip" data-placement="top" title="Verified"></i>';
        else
            return null;

    },
    'html_input' => function($params = []){

        $opt['options'] = [
            '0' => 'Not Verified',
            '1' => 'Verified'
        ];

        return form::select(array_merge($params, $opt));
    }
];

?>