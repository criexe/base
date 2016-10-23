<?php

$table_id = (int)input::get('table');

$data = [

    'columns' => load::model('db_manager:column')->get_all([

        'where' => "table_id=$table_id"
    ])

];

echo html::render('db_manager:column_list', $data);

?>