<?php


class controller_db_manager extends controller
{

    public function __construct()
    {
        parent::__construct();

        user::must_admin();
    }

    public function create_table()
    {
        try
        {
            $table   = validator::input('text', 'db-table-name');
            $display = validator::input('text', 'db-display-name');

            $m_title = load::model('db_manager:table');

            db::create_table($table, []) or throw_exception('Error.');

            $m_title->insert([

                'display' => $display,
                'table'   => $table,
                'created_at' => time(),
                'updated_at' => null

            ]) or throw_exception($m_title->error());

            echo 'Success';
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

}


?>