<?php

return [

    'name'     => 'Status',
    'db_type'  => 'int(1)',
    'display_function' => function($data = null){

        if($data == '1' || $data === 1)
            return '<strong class="text-success">Incoming</strong>';
        else
            return '<strong class="text-danger">Outgoing</strong>';

    },
    'html_input' => function($params = []){

        $opt['options'] = [
            '0' => 'Outgoing',
            '1' => 'Incoming'
        ];

        return form::select(array_merge($params, $opt));
    }
];

?>