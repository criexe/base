<?php

$data = [

    'tables' => load::model('db_manager:table')->get_all()

];

echo html::render('db_manager:table_list', $data);

?>