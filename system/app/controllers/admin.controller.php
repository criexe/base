<?php


class controller_admin extends controller
{

    function __construct()
    {
        if( ! user::logged_in() && ! (user::authority() == 'developer' || user::authority() == 'admin') && (METHOD != 'login') && (METHOD != 'logout') )
        {
            sys::location('/sys/login');
            exit;
        }
    }

    function index()
    {
        cx::title('Control Panel');

        layout::set('admin');
        $this->render('admin/index');
    }


    function settings()
    {
        if(is::posted())
        {
            $options = $_POST['option'];

            if(is_array($options))
            {
                foreach($options as $k => $v)
                {
                    $v = filter::request($v);
                    cx::option($k, $v);
                }
            }

            echo response::ajax([

                'status'  => true,
                'message' => 'Success.'
            ]);
        }
        else
        {
            cx::title('Settings');

            layout::set('admin');
            $this->render('admin/settings/index');
        }
    }


    function latest($type = null)
    {
        $data           = [];
        $data['params'] = [];

        if($type != null)
        {
            cx::title('Latest : ' . cx::type($type)['name']);
            $data['params']['db']['where'] = "`type` = '$type'";
        }
        else
        {
            cx::title('Latest');
        }

        layout::set('admin');
        $this->render('admin/latest', $data);
    }


    function add($type = null)
    {
        $type    = strip_tags($type);
        $cx_type = cx::type($type);

        $data                 = [];
        $data['item_type']    = $type;
        $data['type_title']   = $cx_type['title'];
        $data['data']         = false;
        $data['form_action']  = URL . '/admin/insert';
        $form_content         = cx::render($cx_type['form'], $data, ['ext' => 'form']);
        $data['form_content'] = $form_content;


        layout::set('admin');
        $this->render('admin/add', $data);
    }


    function edit($id = null)
    {
        try
        {
            if($id == null || !is_numeric((int)$id)) return false;

            // Get Item
            $item_params          = [];
            $item_params['where'] = "`id` = $id";
            $item_data            = item::get($item_params, true);

            $type    = $item_data['type'];
            $cx_type = cx::type($type);

            $columns      = item::columns($type);
            $input_values = $item_data;

            $data                 = [];
            $data['item_type']    = $type;
            $data['type_title']   = $cx_type['title'];
            $data['data']         = $input_values;
            $data['form_action']  = URL . '/admin/update?id=' . $id;
            $form_content         = cx::render($cx_type['form'], $data, ['ext' => 'form']);
            $data['form_content'] = $form_content;

            cx::title('Editing : ' . $item_data['title']);
            layout::set('admin');
            $this->render('admin/edit', $data);
        }
        catch(Exception $e)
        {
            echo logger::add($e->getMessage());
            return false;
        }
    }


    function insert()
    {
        try
        {
            $insert_data = [];

            if(array_key_exists('db', $_POST))
            {
                foreach($_POST['db'] as $k => $v)
                {
                    if(is_array($v))
                    {
                        $insert_data[$k] = $v;
                    }
                    else
                    {
                        $insert_data[$k] = filter::request($v);
                    }
                }

                $insert = item::insert($insert_data);

                if(!$insert) echo db::error();

                echo response::ajax(['status' => true, 'location' => '/admin/edit/' . $insert]);

            }
            else
            {
                echo response::ajax(['status' => false]);
                return false;
            }
        }
        catch(Exception $e)
        {
            logger::add('Item Insert : ' . $e->getMessage());
            echo response::ajax(['status' => false]);
            return false;
        }
    }


    function update()
    {
        try
        {
            $id            = input::get('id') or throw_exception('No ID.');
            $update_data   = [];
            $update_params = [];

            $update_params['where'] = "`id` = $id";
            $update_params['limit'] = 1;

            if(array_key_exists('db', $_POST))
            {
                foreach($_POST['db'] as $k => $v)
                {
                    if(is_array($v))
                    {
                        $update_data[$k] = $v;
                    }
                    else
                    {
                        $update_data[$k] = filter::request($v);
                    }
                }

                $update = item::update($update_data, $update_params);

                if(!$update) echo db::error();

                echo response::ajax(['status' => true]);
            }
            else
            {
                echo response::ajax(['status' => false]);
                return false;
            }
        }
        catch(Exception $e)
        {
            logger::add('Item Update : ' . $e->getMessage());
            echo response::ajax(['status' => false]);
            return false;
        }
    }


    function delete()
    {
        try
        {
            $id = input::get('id');

            $params = [];
            $params['where'] = "`id` = $id";

            if(item::delete($params) != false)
            {
                echo response::ajax(['status' => true]);
            }
            else
            {
                echo response::ajax(['status' => false]);
            }
        }
        catch(Exception $e)
        {
            logger::add('Item Delete : ' . $e->getMessage());
        }
    }


    function change_post_status()
    {
        $r = [];

        try
        {
            $id  = input::post('id');
            $val = input::post('val');

            if($id && $val)
            {
                $udata['status']  = $val;
                $uparams['where'] = "`id` = $id";
                $uparams['limit'] = 1;

                item::update($udata, $uparams) or throw_exception('Error');

                $r['status']  = true;
                $r['message'] = 'Post status was successfully changed.';
            }
        }
        catch(Exception $e)
        {
            $r['status']  = false;
            $r['message'] = 'Error';
            $r['message'] = $e->getMessage();
        }
        finally
        {
            echo response::ajax($r);
        }
    }

}