<?php

return [
    'name'             => 'Date',
    'db_type'  => 'int',
    'html_input'       => function($params = []){

        return form::date($params);
    }
];

?>