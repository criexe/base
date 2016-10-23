<?php
/**
 * class.db.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */


class db
{

    public static $user    = null;
    public static $pass    = null;
    public static $name    = null;
    public static $host    = null;
    public static $port    = null;
    public static $charset = null;
    public static $prefix  = null;

    public static $conn    = null;



    /**
     * @param array $params
     *
     * @throws Exception
     */
    public static function connect($params = [])
    {
        if(self::$user == null) throw_exception('[Database] No user.');
        if(self::$name == null) throw_exception('[Database] No database name.');

        if(self::$host == null) throw_exception('[Database] No host.');
        if(self::$charset == null) throw_exception('[Database] No charset.');

        // Connect
        self::$conn = mysqli_connect(self::$host, self::$user, self::$pass, self::$name);
        if(!self::$conn)
        {
            throw_exception('[Database] Connection error.');
        }
        else
        {
            mysqli_set_charset(self::$conn, "UTF8");
            mysqli_query(self::$conn, "SET NAMES '" . self::$charset . "'");
            mysqli_query(self::$conn, "SET character_set_connection = '" . self::$charset . "'");
            mysqli_query(self::$conn, "SET character_set_client = '" . self::$charset . "'");
            mysqli_query(self::$conn, "SET character_set_results = '" . self::$charset . "'");
        }
    }


    public static function close()
    {
        mysqli_close(self::$conn);
    }


    public static function error()
    {
        return mysqli_error(self::$conn);
    }



    /**
     * @param null $query
     *
     * @return bool|mysqli_result
     */
    public static function query($query = null)
    {
        if($query == null) return false;

//        logger::add($query, 'db');

        return mysqli_query(self::$conn, $query);
    }



    /**
     * @param null $query
     *
     * @return array|bool|null
     */
    public static function fetch ($query = null)
    {
        if($query == null) return false;

        return mysqli_fetch_assoc(self::query($query));
    }



    /**
     * @param $q
     *
     * @return array|null
     */
    public static function fetch_array ($q)
    {
        return mysqli_fetch_array($q);
    }



    /**
     * @param $q
     *
     * @return array|null
     */
    public static function fetch_assoc ($q)
    {
        return mysqli_fetch_assoc($q);
    }


    public static function insert_id()
    {
        return mysqli_insert_id(self::$conn);
    }



    /**
     * @param null $table_name
     *
     * @return string
     */
    public static function add_prefix($table_name = null)
    {
        return sys::get_config('database')['prefix'] . $table_name;
    }


    /**
     * @param null  $table_name
     * @param array $params
     *
     * @return array|bool|null
     */
    public static function get ($table_name = null, $params = [])
    {
        if($table_name == null) return false;

        $params['columns']  = array_key_exists('columns', $params)  ? $params['columns'] : null;
        $params['where']    = array_key_exists('where', $params)    ? $params['where'] : null;
        $params['order_by'] = array_key_exists('order_by', $params) ? $params['order_by'] : null;
        $params['limit']    = array_key_exists('limit', $params)    ? $params['limit'] : 1;

        $columns = $params['columns'] == null ? '*' : '`' . implode('`, `', $params['columns']) . '`';

        $sql   = [];
        $sql[] = "SELECT {$columns} FROM `{$table_name}`";
        if($params['where'] != null)    $sql[] = "WHERE {$params['where']}";
        if($params['order_by'] != null) $sql[] = "ORDER BY {$params['order_by']}";
        if($params['limit'] != null)    $sql[] = "LIMIT {$params['limit']}";

        $sql_query = implode(' ', $sql);

        // Get Cache
        $cache_data = self::get_cache($table_name, $params, $sql_query);

        // Return Cache
        if($cache_data) return $cache_data;

        $query = self::query($sql_query);

        $result = self::fetch_assoc($query);

        self::create_cache($table_name, $params, $sql_query, $result);

        return $result;
    }



    /**
     * @param null  $table_name
     * @param array $params
     *
     * @return array|bool
     */
    public static function get_all ($table_name = null, $params = [])
    {
        if($table_name == null) return false;

        $params['columns']  = array_key_exists('columns', $params)  ? $params['columns'] : null;
        $params['where']    = array_key_exists('where', $params)    ? $params['where'] : null;
        $params['order_by'] = array_key_exists('order_by', $params) ? $params['order_by'] : null;
        $params['group_by'] = array_key_exists('group_by', $params) ? $params['group_by'] : null;
        $params['limit']    = array_key_exists('limit', $params)    ? $params['limit'] : null;

        $params['page_no']  = array_key_exists('page_no', $params)  ? $params['page_no'] : 1;
        $params['page']     = array_key_exists('page', $params)     ? $params['page'] : false;

        if($params['page'])
        {
            $page = (int)input::get($params['page']);
            if($page) $params['page_no'] = $page;
        }

        $limit     = ($params['page_no'] - 1) * $params['limit'];
        $sql_limit = $limit . ', ' . $params['limit'];

        $columns = $params['columns'] == null ? '*' : '`' . implode('`, `', $params['columns']) . '`';

        $sql   = [];
        $sql[] = "SELECT {$columns} FROM `{$table_name}`";
        if($params['where'] != null)    $sql[] = "WHERE {$params['where']}";
        if($params['group_by'] != null) $sql[] = "GROUP BY {$params['group_by']}";
        if($params['order_by'] != null) $sql[] = "ORDER BY {$params['order_by']}";
        if($params['limit'] != null)    $sql[] = "LIMIT {$sql_limit}";

        $sql_query = implode(' ', $sql);

        // Get Cache
        $cache_data = self::get_cache($table_name, $params, $sql_query);

        // Return Cache
        if($cache_data) return $cache_data;

        $r = [];
        $q = self::query($sql_query);

        while($ql = self::fetch_assoc($q))
            $r[] = $ql;

        self::create_cache($table_name, $params, $sql_query, $r);

        return $r;
    }



    /**
     * @param null  $table_name
     * @param array $params
     *
     * @return array|bool
     */
    public static function group ($table_name = null, $params = [])
    {
        if($table_name == null) return false;

        $params['columns']  = array_key_exists('columns', $params)  ? $params['columns'] : null;
        $params['where']    = array_key_exists('where', $params)    ? $params['where'] : null;
        $params['order_by'] = array_key_exists('order_by', $params) ? $params['order_by'] : null;
        $params['group_by'] = array_key_exists('group_by', $params) ? $params['group_by'] : null;
        $params['limit']    = array_key_exists('limit', $params)    ? $params['limit'] : 100;
        $params['page_no']  = array_key_exists('page_no', $params)  ? $params['page_no'] : 1;

        $limit     = ($params['page_no'] - 1) * $params['limit'];
        $sql_limit = $limit . ', ' . $params['limit'];

        $columns = $params['columns'] == null ? '*' : '`' . implode('`, `', $params['columns']) . '`';

        $sql   = [];
        $sql[] = "SELECT {$columns}, COUNT(*)  FROM `{$table_name}`";
        if($params['where'] != null)    $sql[] = "WHERE {$params['where']}";
        if($params['group_by'] != null) $sql[] = "GROUP BY {$params['group_by']}";
        if($params['order_by'] != null) $sql[] = "ORDER BY {$params['order_by']}";
        if($params['limit'] != null)    $sql[] = "LIMIT {$sql_limit}";

        $sql_query = implode(' ', $sql);

        // Get Cache
        $cache_data = self::get_cache($table_name, $params, $sql_query);

        // Return Cache
        if($cache_data) return $cache_data;

        $r = [];
        $q = self::query($sql_query);

        while($ql = self::fetch_assoc($q))
            $r[] = $ql;

        self::create_cache($table_name, $params, $sql_query, $r);

        return $r;
    }


    /**
     * @param null $table_name
     * @param null $where
     * @param null $others
     *
     * @return bool
     * @throws Exception
     */
    public static function count_rows ($table_name = null, $params = [])
    {
        if($table_name == null) return false;

        $params['where']    = array_key_exists('where', $params) ? $params['where'] : null;
        $params['order_by'] = array_key_exists('order_by', $params) ? $params['order_by'] : null;
        $params['group_by'] = array_key_exists('group_by', $params) ? $params['group_by'] : null;
        $params['limit']    = array_key_exists('limit', $params) ? $params['limit'] : null;

        $sql   = [];
        $sql[] = "SELECT COUNT(*) FROM `{$table_name}`";
        if($params['where'] != null)    $sql[] = "WHERE {$params['where']}";
        if($params['group_by'] != null) $sql[] = "GROUP BY {$params['group_by']}";
        if($params['order_by'] != null) $sql[] = "ORDER BY {$params['order_by']}";
        if($params['limit'] != null)    $sql[] = "LIMIT {$params['limit']}";

        $sql_query = implode(' ', $sql);

        // Get Cache
        $cache_data = self::get_cache($table_name, $params, $sql_query);

        // Return Cache
        if($cache_data) return $cache_data;

        $num = self::fetch_array(self::query($sql_query));

        self::create_cache($table_name, $params, $sql_query, $num[0]);
        return $num[0];
    }


    /**
     * @param null  $table_name
     * @param array $data
     *
     * @return bool|int|string
     */
    public static function insert($table_name = null, $data = [])
    {
        hook::listen("$table_name:db.insert", 'before', $data);

        if($table_name == null) return false;

        $insert_data_arr = [];

        foreach($data as $k => $v)
        {
            if($v == null)
            {
                $insert_data_arr[] = "`{$k}`=NULL";
            }
            else
            {
                $v = trim($v, "'");

                if(is_int($v))
                {
                    $insert_data_arr[] = "`{$k}`={$v}";
                }
                else
                {
                    $insert_data_arr[] = "`{$k}`='{$v}'";
                }
            }
        }

        $insert_data = implode(', ', $insert_data_arr);
        $sql_query   = "INSERT INTO `{$table_name}` SET {$insert_data}";

        $insert      = self::query($sql_query);

        hook::listen("$table_name:db.insert", 'after', $data);

        if($insert)
        {
            self::clear_cache();
            return self::insert_id();
        }
        else
        {
            logger::add("[Insert Data] {$sql_query}", 'db');
            return false;
        }
    }



    /**
     * @param null  $table_name
     * @param array $data
     * @param array $params
     *
     * @return bool
     */
    public static function update($table_name = null, $data = [], $params = [])
    {
        hook::listen("$table_name:db.update", 'before', $data);

        if($table_name == null) return false;

        $update_data_arr = [];
        $sql             = [];

        $params['where']    = array_key_exists('where', $params) ? $params['where'] : null;
        $params['order_by'] = array_key_exists('order_by', $params) ? $params['order_by'] : null;
        $params['limit']    = array_key_exists('limit', $params) ? $params['limit'] : null;

        foreach($data as $k => $v)
        {
            if($v == null)
            {
                $update_data_arr[] = "`{$k}`=NULL";
            }
            else
            {
                $v = trim($v, "'");

                if(is_int($v))
                {
                    $update_data_arr[] = "`{$k}`={$v}";
                }
                else
                {
                    $update_data_arr[] = "`{$k}`='{$v}'";
                }
            }
        }

        $update_data = implode(', ', $update_data_arr);
        $sql[]       = "UPDATE `{$table_name}` SET {$update_data}";
        if($params['where'] != null)    $sql[] = "WHERE {$params['where']}";
        if($params['order_by'] != null) $sql[] = "ORDER BY {$params['order_by']}";
        if($params['limit'] != null)    $sql[] = "LIMIT {$params['limit']}";

        $sql_query = implode(' ', $sql);

        $update = self::query($sql_query);

        hook::listen("$table_name:db.update", 'after', $data);

        if($update)
        {
            self::clear_cache($table_name);
            return self::affected_rows();
        }
        else
        {
            logger::add("[Updated Data] {$sql_query}", 'db');
            return false;
        }
    }



    /**
     * @param null  $table_name
     * @param array $params
     *
     * @return bool
     */
    public static function delete ($table_name = null, $params = [])
    {
        hook::listen("$table_name:db.delete", 'before', $params);

        if($table_name == null) return false;

        $params['where']    = array_key_exists('where', $params) ? $params['where'] : null;
        $params['limit']    = array_key_exists('limit', $params) ? $params['limit'] : null;

        $sql   = [];
        $sql[] = "DELETE FROM `{$table_name}`";
        if($params['where'] != null)    $sql[] = "WHERE {$params['where']}";
        if($params['limit'] != null)    $sql[] = "LIMIT {$params['limit']}";

        $sql_query = implode(' ', $sql);

        if( self::query($sql_query) )
        {
            hook::listen("$table_name:db.delete", 'after', $params);

            self::clear_cache($table_name);
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * @param null $table_name
     *
     * @return array|null
     */
    public static function get_columns($table_name = null)
    {
        if($table_name == null) return null;

        $db_name = self::$name;
        $columns = [];

        $query = self::query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '{$db_name}' AND TABLE_NAME = '{$table_name}'");

        while($ql = self::fetch_array($query)) $columns[] = $ql['COLUMN_NAME'];

        return $columns;
    }



    /**
     * @param null $table_name
     * @param null $columns
     *
     * @return bool
     *
     * $columns = [
     *
     *   [
     *     'name' => 'column_name',
     *     'type' => 'int(11)' // int(11), varchar(255), float, text
     *   ],
     *   [
     *     'name' => 'column_name',
     *     'type' => 'int(11)' // int(11), varchar(255), float, text
     *   ]
     * ]
     */
    public static function create_table($table_name = null, $columns = null)
    {
        if($table_name == null) return false;

        $table_name = sys::get_config('database')['prefix'] . $table_name;

        if($columns != null)
        {
            $columns_sql = [];

            foreach($columns as $column)
            {
                if($column['name'] == 'id') continue;
                $columns_sql[] = "`{$column['name']}` {$column['type']} DEFAULT NULL";
            }

            $cols = implode(',', $columns_sql);
        }


        $sql = "

            CREATE TABLE IF NOT EXISTS `$table_name` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `created_at` int DEFAULT NULL,
              `updated_at` int DEFAULT NULL,

              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        ";

        return self::query($sql);
    }


    public static function rename_table($table_name = null, $new_name = null)
    {
        $table_name = sys::get_config('database')['prefix'] . $table_name;

        return self::query("ALTER TABLE $table_name RENAME TO $new_name;");
    }


    public static function add_column($table_name = null, $column_name = null, $type = null)
    {
        $table_name = sys::get_config('database')['prefix'] . $table_name;

        return self::query("ALTER TABLE $table_name ADD $column_name $type;");
    }


    public static function delete_column($table_name = null, $column_name = null)
    {
        $table_name = sys::get_config('database')['prefix'] . $table_name;

        return self::query("ALTER TABLE $table_name DROP COLUMN $column_name;");
    }


    public static function delete_table($table_name = null)
    {
        $table_name = sys::get_config('database')['prefix'] . $table_name;

        return self::query("DROP TABLE IF EXISTS $table_name;");
    }


    public static function edit_column($table_name = null, $column_name = null, $new_column_name = false, $type = 'text')
    {
        if($column_name == $new_column_name)

        $table_name = sys::get_config('database')['prefix'] . $table_name;

        $new_column_name = $new_column_name ? $new_column_name : $column_name;
        return self::query("ALTER TABLE $table_name CHANGE $column_name $new_column_name $type;");
    }


    public static function search_where_query($table_name = null, $string = null)
    {
        if($string == null) return null;

        $columns      = self::get_columns(sys::get_config('database')['prefix'] . $table_name);
        $where_column = [];

        foreach($columns as $column) $where_column[] = "`$column` LIKE '%$string%'";

        return implode(' OR ', $where_column);
    }


    public static function get_info()
    {
        $info = [];
        $info['user']    = self::$user;
        $info['pass']    = self::$pass;
        $info['name']    = self::$name;
        $info['host']    = self::$host;
        $info['port']    = self::$port;
        $info['charset'] = self::$charset;
        $info['prefix']  = self::$prefix;

        return $info;
    }



    /**
     * @param null $table_name
     * @param null $params
     *
     * @return bool|string
     */
    public static function cache_id($table_name = null, $params = null)
    {
        if($table_name == null || $params == null) return false;

        $id = $table_name . '__' . filter::slugify(json_encode($params), [], '_');
        return $id;
    }



    /**
     * @param $table_name
     * @param $sql_params
     * @param $query
     * @param $data
     */
    public static function create_cache($table_name, $sql_params, $query, $data)
    {
        $path = CACHE_PATH . DS . 'db';

        $file_name = self::cache_id($table_name, $sql_params) . '.cache';

        $file_data = [

            'keys' => [

                'table'  => $table_name,
                'query'  => $query,
                'params' => $sql_params,
            ],

            'value' => [

                'created_at' => time(),
                'data'       => $data
            ]
        ];

        sys::write([

            'file' => $path . DS . $file_name,
            'data' => json_encode($file_data),
            'mode' => 'w'
        ]);

        // logger::add('[DB] Cache is stored : ' . $file_name, 'cache');
    }



    /**
     * @param $table_name
     * @param $sql_params
     * @param $query
     *
     * @return bool
     */
    public static function get_cache($table_name, $sql_params, $query)
    {
        if(sys::get_config('cache')['active'] == false) return false;

        $cache_file = $path = CACHE_PATH . DS . 'db' . DS . self::cache_id($table_name, $sql_params) . '.cache';

        if(!file_exists($cache_file)) return false;

        $file_content = file_get_contents($cache_file);

        $file_data = json_decode($file_content, true);
        $keys      = $file_data['keys'];

        if( ! ($keys['table'] === $table_name && $keys['query'] === $query && $keys['params'] === $sql_params) )
        {
            return false;
        }
        else
        {
            // logger::add('[DB] Loaded From Cache : ' . $cache_file, 'cache');
            return $file_data['value']['data'];
        }
    }



    /**
     * @param null $table_name
     */
    public static function clear_cache($table_name = null)
    {
        $path  = CACHE_PATH . DS . 'db';

        if($table_name != null)
        {
            $files = glob($path . DS . $table_name . '__*.cache');
        }
        else
        {
            $files = glob($path . DS . '*.cache');
        }

        if($files && is_array($files))
        {
            foreach($files as $file)
            {
                unlink($file);
            }
        }
    }



    public static function dump()
    {
        $user = self::$user;
        $pass = self::$pass;
        $host = self::$host;
        $name = self::$name;

        $path = SYSDATA_PATH . DS . 'backups' . DS . 'backup_' . date('d-m-Y_H-i') . '.sql';

        sys::exec("mysqldump --user=$user --password=$pass --host=$host $name > $path");
    }


    public static function affected_rows()
    {
        return mysqli_affected_rows(self::$conn);
    }


    public static function export()
    {
        $file  = BACKUPS_PATH . DS . date('d-m-Y_H-i-s') . '.backup';
        $datas = item::get_all();
        $datas = json::encode($datas);

        $write         = [];
        $write['file'] = $file;
        $write['data'] = $datas;

        return sys::write($write);
    }


}
?>