<?php

return [
    'name'             => 'From id',
    'db_type'          => 'int',
    'display_function' => function($data = null, $options = null){

        if($data == null) return null;
        if($options == null) return $data;

        $options = strtolower($options);
        $options = str_replace('&gt;', '>', $options);

        if(preg_match('#([a-z0-9_]+)::([a-z0-9_]+)>([a-z0-9_]+)#si', $options, $matches))
        {
            $_table = trim($matches[1]);
            $_from  = trim($matches[2]);
            $_to    = trim($matches[3]);

            $get = db::get(sys::get_config('database')['prefix'] . $_table, [

                'columns' => [$_from, $_to],
                'where'   => "`$_from` = '$data'",
            ]);

            return "<span data-toggle='tooltip' data-placement='top' title='$data'>{$get[$_to]}</span>";
        }
        else
        {
            return $data;
        }
    },

    'html_input'       => function($params = [], $options = []){

        $column = preg_replace('/^column_(.*?)/i', '$1', $params['name']);

        $options = strtolower($options);
        $options = str_replace('&gt;', '>', $options);

        $input_options = [];

        if(preg_match('#([a-z0-9_]+)::([a-z0-9_]+)>([a-z0-9_]+)#si', $options, $matches))
        {
            $_table = trim($matches[1]);
            $_from  = trim($matches[2]);
            $_to    = trim($matches[3]);

            $get = db::get_all(sys::get_config('database')['prefix'] . $_table, [

                'columns' => [$_from, $_to],
            ]);

            foreach($get as $i) $input_options[ $i[$_from] ] = $i[$_to];

            return form::select(array_merge($params, ['options' => $input_options]));
        }
        else
        {
            return form::text($params);
        }

        return form::text($params);
    }
];

?>