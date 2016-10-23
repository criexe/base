<?php

return [
    'name'     => 'Number',
    'db_type'  => 'int',
    'pattern'  => '/^[0-9]+$/',
    'html_input' => function($params = []){ return form::text($params); }
];

?>