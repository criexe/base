<?php

return [
    'name'             => 'Date',
    'db_type'  => 'int',
    'display_function' => function($time = null){ return date('F j, Y, g:i a', $time); },
    'html_input'       => function($params = []){

        $value      = ['value' => time()];
        $new_params = array_merge($params, $value);

        return form::hidden($new_params);
    }
];

?>