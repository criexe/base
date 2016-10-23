<?php

return [
    'name'             => 'User',
    'db_type'          => 'int',
    'display_function' => function($id = null){ return $id ? load::model('user:user')->get_username($id) : null; },
    'html_input'       => function($params = []){

        $value = array_key_exists('value', $params) ? [] : ['value' => load::library('user:user')->get_user_id()];

        $params_without_name = $params;
        unset($params_without_name['name']);

        $r  = '';
        $r .= form::text(array_merge($params_without_name, [
            'value'    => load::library('user:user')->get_username(),
            'disabled' => null
        ]));

        unset($params['data-toggle'], $params['data-placement'], $params['title'], $params['class'], $params['placeholder']);

        $r .= form::text(array_merge($params, $value)); // TODO : Hidden olarak değiştirilecek

        // TODO : Autocomplete yapılacak

        return $r;
    }
];

?>