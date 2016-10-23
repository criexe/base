<?php

return [

    'name'             => 'Verify',
    'db_type'  => 'int(1)',
    'display_function' => function($data = null){

        if($data == '1' || $data === 1)
        {
            return '<i class="text-success fa fa-circle" data-toggle="tooltip" data-placement="top" title="Verified"></i>';
        }
        else
        {
            return '<i class="text-danger fa fa-circle"></i>';
        }

    },
    'html_input'       => function($params = []){}
];

?>