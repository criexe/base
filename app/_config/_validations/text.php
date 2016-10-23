<?php

return [
    'name'     => 'Text',
    'db_type'  => 'text',
    'html_input' => function($params = []){ return form::text($params); }
];

?>