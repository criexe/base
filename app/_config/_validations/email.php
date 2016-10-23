<?php

return [
    'name' => 'Email',
    'db_type'  => 'varchar(255)',
    'max_len' => 255,
    'min_len' => 5,
    'required' => true,
    'filter_var' => FILTER_VALIDATE_EMAIL,
    'html_input' => function($params = []){ return form::email($params); }
];

?>