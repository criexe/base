<?php


// New Table
hook::add(

    db::add_prefix('db_tables') . ':db.insert', 'before',

    function($data)
    {
        try
        {
            $table   = $data['table'];
            $display = $data['display'];

            db::create_table($table, []) or throw_exception('Error.');

            echo 'Success';
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

);


// Delete Table
hook::add(

    db::add_prefix('db_tables') . ':db.delete', 'before',

    function($params)
    {
        $table = load::model('db_manager:table')->get($params)['table'];

        db::delete_table($table);
    }

);


// New Column
hook::add('cx_db_columns:db.insert', 'before', function($data){

    $table_name = load::model('db_manager:table')->get(['where' => "id = {$data['table_id']}"]);
    $type       = validator::config($data['validation'])['db_type'];

    db::add_column($table_name['table'], $data['column'], $type) or throw_exception('Could not create column.');
});


// Update Column
hook::add('cx_db_columns:db.update', 'before', function($data){

//    $table_name = load::model('db_manager:table')->get(['where' => "id = {$data['table_id']}"]);
//
//    $type = validator::config($data['validation'])['db_type'];
//
//    db::add_column($table_name['table'], $data['column'], $type) or throw_exception('Could not create column.');

});


?>