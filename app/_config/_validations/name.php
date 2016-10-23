<?php

return [
    'name' => 'Name',
    'db_type'  => 'varchar(255)',
    'max_len' => 50,
    'min_len' => 2,
    'html_input' => function($params = []){ return form::text($params); }
];

?>