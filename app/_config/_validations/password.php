<?php

return [
    'name' => 'Password',
    'db_type'  => 'varchar(100)',
    'max_len' => 255,
    'min_len' => 6,
    'required' => true,
    'html_input' => function($params = []){ return form::password($params); }
];

?>