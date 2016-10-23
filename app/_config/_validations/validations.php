<?php

return [

    'name' => 'Validations',
    'db_type' => 'varchar(50)',

    'html_input' => function($params = []){


        $validations = scandir(VALIDATIONS_PATH);

        $options = [];

        foreach($validations as $v)
        {
            if($v == '.' || $v == '..') continue;
            $v = str_replace('.php', '', $v);

            $options[$v] = $v;
        }

        $opt['data-validations'] = null;
        $opt['options'] = $options;

        $r = form::select(array_merge($params, $opt));

        return $r;
    }
];

?>