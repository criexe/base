<?php
/**
 * model.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */

class model
{

    public $name           = null;
    public $displayed_name = null;
    public $prefix         = null;
    public $cache          = true;


    public function __construct()
    {
        $this->prefix = sys::get_config('database')['prefix'];
    }


    /**
     * @param array $params
     *
     * @return array
     */
    public function get($params = [])
    {
        $result = db::get($this->prefix . $this->name, $params);

        return $result;
    }


    /**
     * @param array $params
     *
     * @return array|bool|null
     */
    public function get_all($params = [])
    {
//        $page = (int)input::get('page_no');
//
//        if($page && !array_key_exists('page_no', $params))
//            $params['page_no'] = $page;

        return db::get_all($this->prefix . $this->name, $params);
    }



    /**
     * @param array $params
     *
     * @return array
     */
    public function group($params = [])
    {
        return db::group($this->prefix . $this->name, $params);
    }


    /**
     * @param null $string
     *
     * @return array|null
     */
    public function search_where_query($string = null)
    {
        if($string == null) return null;

        $columns      = $this->get_columns();
        $where_column = [];

        foreach($columns as $column) $where_column[] = "`$column` LIKE '%$string%'";

        return implode(' OR ', $where_column);
    }


    /**
     * @param array $params
     *
     * @return bool
     */
    public function count_rows($params = [])
    {
        return db::count_rows($this->prefix . $this->name, $params);
    }


    /**
     * @param array $data
     *
     * @return bool|int|string
     */
    public function insert($data = [])
    {
        return db::insert($this->prefix . $this->name, $data);
    }


    /**
     * @param array $data
     * @param array $params
     *
     * @return bool
     */
    public function update($data = [], $params = [])
    {
        $newParams = array_key_exists('limit', $params) ? $params : array_merge($params, ['limit' => 1]);

        // Move to Trash
        $info = [
            'time'       => time(),
            'table_name' => $this->prefix . $this->name,
            'params'     => $newParams,
            'datas'      => $data
        ];

        $updated_items = $this->get_all($newParams);
        trash::add(array_merge($info, $updated_items), 'updated.' . $this->prefix . $this->name);


        return db::update($this->prefix . $this->name, $data, $newParams);
    }


    /**
     * @param array $params
     *
     * @return bool
     */
    public function delete($params = [])
    {
        // Move to Trash
        $info = [
            'time'       => time(),
            'table_name' => $this->prefix . $this->name,
            'params'     => $params
        ];

        $deleted_items = $this->get_all($params);
        trash::add(array_merge($info, $deleted_items), 'deleted.' . $this->prefix . $this->name);


        return db::delete($this->prefix . $this->name, $params);
    }



    /**
     * @param null   $data
     * @param string $by
     *
     * @return bool
     */
    public function is($data = null, $by = 'id')
    {
        if($data == null || $by == null) return false;

        $params = [

            'columns' => [$by],
            'where'   => "$by=$data",
            'limit'   => 1
        ];

        if($this->count_rows($params) > 0) return true;
        else                               return false;
    }


    /**
     * @return string
     */
    public function error()
    {
        return db::error();
    }


    /**
     * @return array|null
     */
    public function get_columns()
    {
        return db::get_columns($this->prefix . $this->name);
    }



    public function get_table_name()
    {
        return $this->name;
    }


    public function get_displayed_name()
    {
        return $this->display;
    }



    public function _default_list($params = [])
    {
        $r = [];

        //$columns = $this->_db_columns(); // TODO: Method Exist
        $columns = $this->get_columns();

        unset($columns['created_at'], $columns['updated_at'], $columns['creation_date'], $columns['slug']);

        $r['datas']   = $this->get_all( array_merge(['order_by' => 'id DESC'], $params) );
        $r['columns'] = $columns;

        return $r;
    }



    public function _delete($id = null)
    {
        if($id == null)
        {
            return false;
        }
        else
        {
            $id = (int)$id;
            if($this->delete( ['where' => "id=$id"]) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }


    public function _default_insert($data = [])
    {
        try
        {
            $r           = [];
            $insert_data = [];

            if(!is_array($data)) throw_exception('Datas must be an array.');

            foreach($data as $k => $v) $insert_data[$k] = "'$v'";

            if($this->insert($insert_data))
            {
                $r['status'] = true;
            }
            else
            {
                $r['status']  = false;
                $r['message'] = $this->error();
            }
        }
        catch(Exception $e)
        {
            $r['status']  = false;
            $r['message'] = $e->getMessage();
        }
        finally
        {
            return $r;
        }
    }


    public function _default_update($data = [], $params = [])
    {
        try
        {
            $r           = [];
            $update_data = [];

            if(!is_array($data)) throw_exception('Datas must be an array.');

            foreach($data as $k => $v) $update_data[$k] = "'$v'";

            if($this->update($update_data, $params))
            {
                $r['status'] = true;
            }
            else
            {
                $r['status']  = false;
                $r['message'] = $this->error();
            }
        }
        catch(Exception $e)
        {
            $r['status']  = false;
            $r['message'] = $e->getMessage();
        }
        finally
        {
            return $r;
        }
    }

}

?>