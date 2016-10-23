<?php

hook::add(

    db::add_prefix('db_tables') . ':db.delete',
    'before',
    function($params)
    {
        $table = load::model('db_manager:table')->get($params)['table'];

        db::delete_table($table);
    }

);

?>