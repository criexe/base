<?php

return [
    'name'     => 'Username',
    'required' => true,
    'pattern'  => '/^[A-Za-z][A-Za-z0-9]+$/',
    'max_len'  => 50,
    'min_len'  => 3,
    'db_type'  => 'varchar(50)',
    'html_input' => function($params = []){ return form::text($params); }
];

?>