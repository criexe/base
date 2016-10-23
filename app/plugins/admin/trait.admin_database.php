<?php
/**
 * trait.admin.php
 *
 * @author Mustafa Aydemir
 * @date   14.11.15
 */


trait admin_database
{


//    public function db_list($model_name = null)
//    {
//        $model     = load::model($model_name);
//
//        $page      = (int)input::get('page_no', ['empty' => 1]);
//        $search    = trim( input::get('search') );
//
//        // List Data
//        $parse_name = explode(':', $model_name);
//        $class_name = end($parse_name);
//
//
//        // List Query
//        $list_query = [
//            'page_no' => $page,
//            'limit'   => 100
//        ];
//
//
//        // Searh Query
//        if($search) $list_query['where'] = $model->search_where_query($search);
//
//
//        if(method_exists('model_' . $class_name, '_list'))
//        {
//            $list_data = $model->_list($list_query);
//        }
//        else
//        {
//            $list_data = $model->_default_list($list_query);
//        }
//
//        $data = [
//            'model'      => $model_name,
//            'display'    => $model->get_displayed_name(),
//            'columns'    => $list_data['columns'],
//            'datas'      => $list_data['datas'],
//            'total_data' => $model->count_rows(),
//            'page_no'    => $page
//        ];
//
//        $this->set_title( $data['display'] . ' List' );
//        $this->_admin_view('_db/db_list', $data);
//
//        cache::create();
//    }




    public function db_list($table_name = null)
    {
        $table_name = strip_tags($table_name);

        $page      = (int)input::get('page_no', ['empty' => 1]);
        $search    = trim( input::get('search') );

        $list_query  = [];
        $count_query = [];

        $prefix = sys::get_config('database')['prefix'];

        $m_table = load::model('db_manager:table');

        $table = $m_table->get(['where' => "`table` = '$table_name'"]);

        $list_query = ['limit' => 100, 'order_by' => 'id DESC', 'page' => 'page_no'];

        if($table['group_by'] != '')
        {
            $list_query['group_by']  = $table['group_by'];
            $count_query['group_by'] = $table['group_by'];
        }

        // Searh Query
        if($search) $list_query['where'] = db::search_where_query($table_name, $search);

        $datas = db::get_all($prefix . $table_name, $list_query);


        $data = [
            'table'      => $table_name,
            'display'    => $table['display'],
            'columns'    => $this->db_columns($table_name, true),
            'datas'      => $datas,
            'total_data' => db::count_rows($prefix . $table_name, $count_query),
            'page_no'    => $page
        ];

        $this->set_title( $data['display'] . ' List' );
        $this->_admin_view('_db/db_list', $data);

        cache::create();
    }


    public function db_insert_form($table_name = null)
    {
        $table_name = strip_tags($table_name);

        $prefix  = sys::get_config('database')['prefix'];
        $m_table = load::model('db_manager:table');
        $table   = $m_table->get(['where' => "`table` = '$table_name'"]);

        $columns = $this->db_columns($table_name);
        $clone   = input::get('clone');

        unset($columns['id']);

        if($clone)
        {
            $cloneDatas = json_decode(htmlspecialchars_decode($clone));
        }
        else
        {
            $cloneDatas = null;
        }

        $data = [
            'table'      => $table_name,
            'display'    => $table['display'],
            'columns'    => $columns,
            'cloneDatas' => $cloneDatas
        ];


        $this->set_title( $data['display'] . ' - Add New' );
        $this->_admin_view('_db/db_insert_form', $data);

        cache::create();
    }



    public function db_insert($table_name = null)
    {
        $insert_data = [];
        $r           = [];

        try
        {
            $table_name = strip_tags($table_name);

            $prefix  = sys::get_config('database')['prefix'];
            $m_table = load::model('db_manager:table');
            $table   = $m_table->get(['where' => "`table` = '$table_name'"]);
            $columns = $this->db_columns($table_name);

            $insert_data = [];

            foreach($columns as $k)
            {
                $uData = validator::input(
                    $k['validation'],
                    'column_' . $k['column']
                    //array_key_exists('options', $k['options']) ? $k['options'] : null
                    // TODO : Option Eklenecek
                );

                if(!$uData) continue;

                $insert_data[$k['column']] = $uData;
            }

            if(db::insert($prefix . $table_name, $insert_data))
                $r = ['status' => true];

            else
                $r = ['status' => false, 'message' => db::error()];

        }
        catch(Exception $e)
        {
            $r['status']  = 'error';
            $r['message'] = $e->getMessage();
        }
        finally
        {
            echo json_encode($r);
            //sys::location('/admin/db_list/' . $model_name);
        }
    }


    public function db_update_form($table_name = null)
    {
        $table_name = strip_tags($table_name);

        $prefix  = sys::get_config('database')['prefix'];
        $m_table = load::model('db_manager:table');
        $table   = $m_table->get(['where' => "`table` = '$table_name'"]);
        $columns = $this->db_columns($table_name);

        $id      = (int)input::get('id');

        unset($columns['id']);

        $data = [
            'table'    => $table_name,
            'display'  => $table['display'],
            'columns'  => $columns,
            'datas'    => db::get($prefix . $table_name, ['where' => "id={$id}"]),
            'id'       => $id
        ];


        $this->set_title( $data['display'] . ' - Update' );
        $this->_admin_view('_db/db_update_form', $data);

        cache::create();
    }


    public function db_update($table_name = null)
    {
        $insert_data = [];
        $r           = [];

        try
        {
            $prefix  = sys::get_config('database')['prefix'];
            $m_table = load::model('db_manager:table');
            $table   = $m_table->get(['where' => "`table` = '$table_name'"]);
            $columns = $this->db_columns($table_name);

            $id = (int)input::get('id');

            unset($columns['id'], $columns['creation_date'], $columns['update_date']);

            foreach($columns as $k)
            {
//                if(validator::get_config($v['validation'])['type'] == 'image')
//                    if(!input::file('column_' . $k)) continue;

                $uData = validator::input(
                    $k['validation'],
                    'column_' . $k['column'],
                    array_key_exists('options', $k) ? $k['options'] : null
                );

                if(!$uData) continue;

                $update_data[$k['column']] = $uData;
            }

            $upParams = [
                'where' => "id={$id}",
                'limit' => 1
            ];

            if(db::update($prefix . $table_name, $update_data, $upParams))
            {
                $r = ['status' => true];
            }

        }
        catch(Exception $e)
        {
            $r['status']  = false;
            $r['message'] = $e->getMessage();
        }
        finally
        {
            echo json_encode($r);
            //sys::location('/admin/db_list/' . $model_name);
        }
    }



    public function db_delete($table_name = null)
    {
        $table_name = strip_tags($table_name);

        $prefix  = sys::get_config('database')['prefix'];

        $r  = [];
        $id = input::get('id');
        $id = (int)$id;

        $del = db::delete($prefix . $table_name, [

            'where' => "id=$id",
            'limit' => 1
        ]);

        if($del)
        {
            $r['status'] = true;
        }
        else
        {
            $r['status']  = false;
            $r['message'] = db::error();
        }

        echo json_encode($r);
    }


    public function db_columns($table_name = null, $only_visible = false)
    {

        $m_table  = load::model('db_manager:table');
        $m_column = load::model('db_manager:column');

        $table = $m_table->get(['where' => "`table` = '$table_name'"]); // TODO : table to table_name

        if($only_visible == true)
            $params = ['where' => "table_id = {$table['id']} AND `visible` = 1"];
        else
            $params = ['where' => "table_id = {$table['id']}"];

        $columns = $m_column->get_all($params);

        return $columns;
    }

}

?>