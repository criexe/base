<?php

return [
    'name'       => 'Checkbox',
    'db_type'    => 'int',
    'html_input' => function($params = []){ return form::checkbox($params); }
];

?>