<?php
/**
 * User: mustafa
 * Date: 23.07.2016
 * Time: 17:59
 */


class item
{

    public static $name = 'items';
    public static $meta = 'item_meta';

    public static $stats_type = 'stats';

    /**
     * @param array $data
     *
     * @return bool|int|string
     */
    public static function insert(array $data = [])
    {
        try
        {
            sys::array_key_default_value($data, 'created_at', time());
            sys::array_key_default_value($data, 'status'    , 'active');
            sys::array_key_default_value($data, 'ip'        , $_SERVER['REMOTE_ADDR']);

            $columns = self::columns();
            $others  = [];

            sys::specify_params($data, $columns);

            $cx_type = cx::type($data['type']);

            if($data['released_at'] != null) $data['released_at'] = strtotime($data['released_at']);

            // Generate URL
            if($data['title'] != null && $data['type'] != null && $data['url'] == null)
            {
                $data['url'] = $data['type'] . '/' . filter::slugify($data['title']);
            }

            if(array_key_exists('keywords', $data) && $data['keywords'] != null)
            {
                $_keywords = str_replace("\n", ', ',  $data['keywords']);
            }
            else $_keywords = null;

            foreach($data as $k => $v)
            {
                if(is_array($v)) $v = $v;
                else             $v = filter::request($v);

                if( ! in_array($k, $columns) || $k == 'data')
                {
                    $others[$k] = $v;
                    unset($data[$k]);
                }
                else
                {
                    // Is Array
                    if(is_array($v))
                    {
                        $data[$k] = json::encode($v);

                        // Remove empty json items
                        $data[$k] = str_replace(['"",', ',""', "'',", ",''"], null, $data[$k]);
                    }
                }
            }

            unset($others['data']);

            $data['data'] = json::encode($others);
            $item_id      = db::insert(self::$name, $data) or throw_exception(db::error());

            if($data['type'] != null) cx::counter('item.insert.' . $data['type'], 1);

            // Insert Meta Datas
            foreach($others as $k => $v)
            {
                if($item_id == null || $k == null || $v == null) continue;

                db::insert(self::$meta, [

                    'item_id'    => $item_id,
                    'meta_key'   => $k,
                    'meta_value' => $v

                ]) or throw_exception(db::error());
            }

            if($cx_type)
            {
                sys::array_key_default_value($cx_type, 'notification.insert', false);

                if($cx_type['notification.insert'] === true)
                {
                    $mail_subject = "New {$cx_type['title']}";
                    $mail_content = "Added New {$cx_type['title']}.";

                    mail::send('mustafa@aydemir.im', $mail_subject, $mail_content, 'mail');
                }
            }

            $hook_data            = [];
            $hook_data['item_id'] = $item_id;
            $hook_data['data']    = $data;

            // Adding Tags
            $_tags = explode(',', $_keywords);
            foreach($_tags as $t) self::tag()->insert($item_id, $t);

            hook::listen("item.insert", 'success', $hook_data);
            return $item_id;
        }
        catch(Exception $e)
        {
            logger::add( $e->getMessage() );
            return false;
        }
    }

    /**
     * @param array $data
     * @param array $params
     *
     * @return bool
     */
    public static function update(array $data = [], array $params = [])
    {
        try
        {
            sys::array_key_default_value($data, 'updated_at'    , time());
            sys::array_key_default_value($data, 'ip'            , $_SERVER['REMOTE_ADDR']);
            sys::array_key_default_value($data, 'disable.cache' , true);

            $columns = self::columns();
            $get_all = item::get_all($params, true);

            if($get_all == false) $get_all = [];

            $update_counter = 0;

            // Items Loop
            foreach($get_all as $get)
            {
                $parse_columns = self::parse_columns_from_data($get);

                $curr_parse_data = self::parse_columns_from_data($get);
                $parse_data      = self::parse_columns_from_data($data);
                $meta_data       = $parse_data['meta'];
                $item_data       = $parse_data['item'];

                $meta_data = array_merge($curr_parse_data['meta'], $meta_data);

                $item_data['data'] = json::encode($meta_data);

                $update_params          = [];
                $update_params['where'] = "`id` = {$get['id']}";
                $update_params['limit'] = 1;

                foreach($item_data as $k => $v)
                {
                    // Is Array
                    if(is_array($v))
                    {
                        $item_data[$k] = json::encode($v);

                        // Remove empty json items
                        $item_data[$k] = str_replace(['"",', ',""', "'',", ",''"], null, $item_data[$k]);
                    }
                }

                if(array_key_exists('keywords', $data) && $data['keywords'] != null)
                {
                    $_keywords = str_replace("\n", ', ',  $data['keywords']);
                }
                else $_keywords = null;

                // Adding Tags
                $_tags = explode(',', $_keywords);
                foreach($_tags as $t) self::tag()->insert($get['id'], $t);

                // Update Item Data
                $update_item = db::update(self::$name, $item_data, $update_params);

                // Meta Data Loop
                foreach($meta_data as $meta_key => $meta_value)
                {
                    self::meta($get['id'], $meta_key, $meta_value);
                }

                $update_counter++;
            }

            if($update_counter > 0) return true; else return false;
        }
        catch(Exception $e)
        {
            echo logger::add( $e->getMessage() );
            return false;
        }
    }

    /**
     * @param null $item_id
     * @param null $meta_key
     * @param null $meta_value
     *
     * @return array|bool|int|null|string
     */
    public static function meta($item_id = null, $meta_key = null, $meta_value = null)
    {
        if($item_id == null)  return false;
        if($meta_key == null) return false;

        $params          = [];
        $params['where'] = "`item_id` = $item_id AND `meta_key` = '$meta_key'";
        $params['limit'] = 1;

        $get = db::get(self::$meta, $params);

        if(is_array($meta_value)) $meta_value = json::encode($meta_value);

        if($meta_value == null)
        {
            return $get;
        }
        else
        {
            if($get)
            {
                $udata = [];
                $udata['meta_value'] = $meta_value;

                return db::update(self::$meta, $udata, $params);
            }
            else
            {
                $idata               = [];
                $idata['item_id']    = $item_id;
                $idata['meta_key']   = $meta_key;
                $idata['meta_value'] = $meta_value;

                return db::insert(self::$meta, $idata);
            }
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public static function parse_columns_from_data(array $data = [])
    {
        $columns = self::columns();
        $item    = [];
        $meta    = [];

        foreach($data as $k => $v)
        {
            if(in_array($k, $columns))
            {
                $item[$k] = $v;
            }
            else
            {
                $meta[$k] = $v;
            }
        }

        unset($meta['item_id']);

        return [

            'item' => $item,
            'meta' => $meta
        ];
    }

    /**
     * @param null $type
     *
     * @return array|bool|mixed|null
     */
    public static function columns($type = null)
    {
        $cx_columns = cx::data('item_columns');
        $r_columns  = [];

        if($cx_columns == null)
        {
            $columns   = db::get_columns(self::$name);
            $r_columns = $columns;
        }
        else
        {
            $r_columns = $cx_columns;
        }

        $i_columns = $r_columns;

        if($type != null)
        {
            $type_columns = cx::type($type)['columns'];
            if(is_array($type_columns)) $i_columns = array_merge($r_columns, $type_columns);
        }

        return $i_columns;
    }

    /**
     * @param null $column_name
     *
     * @return bool|mysqli_result
     */
    public static function add_column($column_name = null)
    {
        $columns = self::columns();

        if(in_array($column_name, $columns))
        {
            return true;
        }
        else
        {
            return db::add_column(self::$name, $column_name, 'LONGTEXT');
        }
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public static function prepare($data = [], $disable_cache = false)
    {
        if( ! is_array($data)) return false;

        $type = $data['type'];
        $id   = $data['id'];

        $cache_id      = "item.$id.prepare";
        $cache_content = cache::get($cache_id);

//        if($cache_content && $disable_cache === false) return $cache_content;

        $data['created_at'] = [

            'time'   => $data['created_at'],
            'format' => sys::get_config('application')['date_pattern'],
            'date'   => date(sys::get_config('application')['date_pattern'], $data['created_at'])
        ];

        if($data['updated_at'] != null)
        {
            $data['updated_at'] = [

                'time'   => $data['updated_at'],
                'format' => sys::get_config('application')['date_pattern'],
                'date'   => date(sys::get_config('application')['date_pattern'], $data['updated_at'])
            ];
        }

        if($data['parent'] != null)
        {
            $parent_params = [];
            $parent_params['where'] = "`id` = {$data['parent']}";

            $data['parent'] = self::get($parent_params);
        }

        if($data['user'] != null)
        {
            $user_params = [];
            $user_params['where'] = "`id` = {$data['user']}";

            $data['user'] = self::get($user_params);
        }

        if($data['image'] != null)
        {
            $data['image_url']   = html::image_link($data['image']);
            $data['image_thumb'] = html::image_link($data['image'], 400);
        }
        else
        {
            $data['image_url']   = null;
            $data['image_thumb'] = null;
        }

        if($data['url'] != null) $data['full_url'] = URL . '/' . $data['url'];

        if(json::valid($data['category'])) $data['category'] = json::decode($data['category']);

        $data['content'] = htmlspecialchars_decode($data['content']);
        $data['content'] = str_replace('&#039;', '"', $data['content']);

        $data['content'] = preg_replace('/font-size:.*?px/si', null, $data['content']);

        if($data['content'] != null && $data['description'] == null)
        {
            $data['description'] = trim(strip_tags($data['content']));
            $data['description'] = utils::limit_text($data['description'], 150);
        }

        // JSON to Array
        if(json::valid($data['parents']))
        {
            $new_parents     = [];
            $data['parents'] = json::decode($data['parents']);

            foreach($data['parents'] as $ps) $new_parents[$ps] = self::get(['where' => "`id` = $ps"]);
            $data['parents.data'] = $new_parents;
        }

        // Default Views
        if($data['views'] == null) $data['views'] = 1;

        // Tags
        $data['tags'] = [];
        $tags_arr     = explode(',', $data['keywords']);
        foreach($tags_arr as $keyword)
        {
            $data['tags'][trim($keyword)] = URL . '/tag/' . filter::slugify($keyword);
        }

        $columns = $data;

        if(array_key_exists('data', $data) && json::valid($data['data']))
        {
            $extracted_data = json::decode($data['data']);

            foreach($extracted_data as $k => $v)
            {
                $v = str_replace('&quot;', '"', $v);
                $v = str_replace('&#039;', "'", $v);

                if(is_string($v)) $v = htmlspecialchars_decode($v);

                if( ! array_key_exists($k, $columns)) $columns[$k] = $v;
            }

            if($type != null)
            {
                $type_columns = self::columns($type);
                sys::specify_params($columns, $type_columns);
            }
        }


        // User Permissions
        if($type === 'user')
        {
            sys::array_key_default_value($columns , 'permissions' , []);
            sys::array_key_default_value($columns , 'authority'   , 'user');

            if($columns['authority'] === 'admin' || $columns['authority'] === 'developer')
            {
                $columns['permissions'] = user::prepare_permissions(true);
            }
            else
            {
                $columns['permissions'] = user::prepare_permissions($columns['permissions']);
            }
        }

        if( ! array_key_exists('name', $data)) $data['name'] = $data['title'];

        unset($columns['data']);
//        cache::create($cache_id, $columns);
        return $columns;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function select_string($params = [])
    {
        sys::array_key_default_value($params, 'where', null);
        sys::array_key_default_value($params, 'order_by', 'id DESC');
        sys::array_key_default_value($params, 'limit', false);
        sys::array_key_default_value($params, 'meta', []);
        sys::array_key_default_value($params, 'type', null);
        sys::array_key_default_value($params, 'only_active', false);
        sys::array_key_default_value($params, 'show.all', false);

        $columns       = self::columns();
        $sql_columns   = [];
        $name          = self::$name;
        $meta          = self::$meta;
        $joins         = [];
        $where         = [];
        $meta_column   = $params['meta'];
        $where_matches = [];
        $time          = time();

        preg_match_all('#`([A-Za-z0-9].*?)`#si', $params['where'], $where_matches);

        // Meta Columns
        foreach($where_matches[1] as $mc)
        {
            if( ! in_array($mc, $columns) && ! in_array($mc, $meta_column) && $mc != 'id')
            {
                $meta_column[] = $mc;
            }
        }

        // Remove Item ID Column
        unset($columns['id']);

        // Set Item ID
        $sql_columns[] = "\t`$name`.`id` AS `item_id`";

        // Set Items Columns
        foreach($columns as $i) $sql_columns[] = "\t`$name`.`$i` AS `$i`";

        // Set Meta Columns
        foreach($meta_column as $m) $sql_columns[] = "\t`$m`.`meta_value` AS `$m`";

        // Implode Columns
        $sql_columns = implode(",\n", $sql_columns);

        foreach($meta_column as $m) $joins[] = "\tRIGHT JOIN `$meta` `$m` ON ( `$name`.`id` = `$m`.`item_id` ) AND ( `$m`.`meta_key` = '$m' )";
        $joins = implode("\n", $joins);


        foreach($where_matches[1] as $cn)
        {
            if( ! in_array($cn, $columns) && $cn != 'id')
                $params['where'] = str_replace("`$cn`", "`$cn`.`meta_value`", $params['where']);
        }

        $where[] = "\t( `$name`.`id` IS NOT NULL ) AND ( `$name`.`released_at` IS NULL OR `$name`.`released_at` < $time )";

        if($params['show.all']    != true) $where[] = "\t( `$name`.`status` = 'active' )";
        if($params['type']        != null) $where[] = "\t( `$name`.`type` = '{$params['type']}' )";
        if($params['where']       != null) $where[] = "\t( {$params['where']} )";
        $where = implode(" AND \n", $where);


        // SQL String
        $sql = "SELECT \n\n$sql_columns \n\nFROM `$name` \n\n$joins \n\nWHERE \n\n$where \n\nORDER BY {$params['order_by']}";

        if($params['limit'] != false) $sql .= "\n\nLIMIT {$params['limit']}";

        return $sql;
    }

    /**
     * @param array $udata
     * @param array $params
     *
     * @return string
     */
    public static function update_string($udata = [], $params = [])
    {
        sys::array_key_default_value($params, 'where', null);
        sys::array_key_default_value($params, 'meta', []);

        $columns         = self::columns();
        $sql_columns     = [];
        $name            = self::$name;
        $meta            = self::$meta;
        $joins           = [];
        $where           = [];
        $meta_column     = $params['meta'];
        $where_matches   = [];
        $update_data_arr = [];

        preg_match_all('#`([A-Za-z0-9].*?)`#si', $params['where'], $where_matches);
        preg_match_all('#`([A-Za-z0-9].*?)`#si', $params['where'], $where_matches);

        // Meta Columns
        foreach($where_matches[1] as $mc)
        {
            if( ! in_array($mc, $columns) && ! in_array($mc, $meta_column) && $mc != 'id')
            {
                $meta_column[] = $mc;
            }
        }

        // Set Datas
        foreach($udata as $k => $v)
        {
            if( ! in_array($k, $columns) && ! in_array($k, $meta_column) && $k != 'id')
            {
                $meta_column[] = $k;
            }

            if($v == null)
            {
                $update_data_arr[] = "\t`{$k}` = NULL";
            }
            else
            {
                $v = trim($v, "'");

                if(is_int($v))
                {
                    $update_data_arr[] = "\t`{$k}` = {$v}";
                }
                else
                {
                    $update_data_arr[] = "\t`{$k}` = '{$v}'";
                }
            }
        }

        $update_data = implode(",\n", $update_data_arr);

        // Remove Item ID Column
        unset($columns['id']);

        foreach($meta_column as $m) $joins[] = "\tRIGHT JOIN `$meta` `$m` ON ( `$name`.`id` = `$m`.`item_id` ) AND ( `$m`.`meta_key` = '$m' )";
        $joins = implode("\n", $joins);

        foreach($where_matches[1] as $cn)
        {
            if( ! in_array($cn, $columns) && $cn != 'id')
                $params['where'] = str_replace("`$cn`", "`$cn`.`meta_value`", $params['where']);
        }

        $where[] = "\t( `$name`.`id` IS NOT NULL ) AND ( `$name`.`status` <> 'trash' )";
        if($params['where'] != null) $where[] = "\t( {$params['where']} )";
        $where = implode(" AND \n", $where);


        // SQL String
        $sql = "UPDATE `$name` \n\n$joins \n\nSET \n\n$update_data \n\nWHERE \n\n$where \n\n";

        return $sql;
    }

    /**
     * @param array $params
     *
     * @return array|bool|null
     */
    public static function get($params = [], $show_all = false)
    {
        sys::array_key_default_value($params, 'show.all', $show_all);
        sys::array_key_default_value($params, 'cache.disable', false);

        $params['limit'] = 1;

        $sql    = self::select_string($params);
        $query  = db::query($sql);
        $result = db::fetch_assoc($query);

        if(count($result) <= 0) return false;

        $result = self::prepare($result, $params['cache.disable']);

        return $result;
    }

    /**
     * @param array $params
     *
     * @return array|bool
     */
    public static function get_all($params = [], $show_all = false)
    {
        sys::array_key_default_value($params, 'show.all', $show_all);
        sys::array_key_default_value($params, 'cache.disable', false);

        $sql    = self::select_string($params);
        $query  = db::query($sql);
        $result = [];

        while($ql = db::fetch_assoc($query)) $result[] = self::prepare($ql, $params['cache.disable']);

        if(count($result) <= 0) return false;
        return $result;
    }

    /**
     * @param array $params
     *
     * @return array|bool
     */
    public static function latest($params = [], $show_all = false)
    {
        sys::array_key_default_value($params, 'show.all', $show_all);

        return self::get_all($params);
    }

    /**
     * @param array $params
     *
     * @return bool|string
     */
    public static function dblist(array $params = [])
    {
        sys::specify_params($params, ['db', 'design']);

        $datas = self::get_all($params['db'], true);

        $render           = [];
        $render['layout'] = false;

        return cx::render('item/list', [

            'datas'  => $datas,
            'params' => $params
        ], $render);
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public static function delete(array $params = [])
    {
        sys::array_key_default_value($params, 'limit', 10);

        $update_data           = [];
        $update_data['status'] = 'trash';

        $update = item::update($update_data, $params);

        return $update;
    }


    public static function options()
    {
        $cache_id   = 'options';
        $cache_data = cache::get($cache_id);

        if($cache_data) return $cache_data;

        $params         = [];
        $params['type'] = 'option';

        $options = self::get_all($params);
        $rdata   = [];

        foreach($options as $option) $rdata[$option['title']] = $option['content'];

        cache::create($cache_id, $rdata);

        return $rdata;
    }


    public static function tag()
    {
        return new tag();
    }


}