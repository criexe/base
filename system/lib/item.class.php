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


    public static function check_field($name = null)
    {
        try
        {
            if($name == null) throw_exception('No name.');

            $columns = self::columns();

            if(in_array($name, $columns))
            {
                return true;
            }
            else
            {
                db::query("ALTER TABLE " . self::$name . " ADD $name TEXT DEFAULT NULL NULL;") or throw_exception(db::error());

                return true;
            }
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    public static function insert(array $data = [])
    {
        try
        {
            sys::array_key_default_value($data, 'created_at' , time());
            sys::array_key_default_value($data, 'released_at', null);
            sys::array_key_default_value($data, 'status'     , 'active');
            sys::array_key_default_value($data, 'ip'         , $_SERVER['REMOTE_ADDR']);
            sys::array_key_default_value($data, 'url'        , null);
            sys::array_key_default_value($data, 'title'      , null);

            $columns = self::columns();
            $others  = [];

            // Check Database Fields
            foreach($data as $column => $value) self::check_field($column);

            $cx_type = cx::type($data['type']);

            if($data['released_at'] != null) $data['released_at'] = strtotime($data['released_at']);

            // Generate URL
            if($data['url'] != null) $data['url'] = self::find_url($data['url']);
            if($data['title'] != null && $data['type'] != null && $data['url'] == null) $data['url'] = self::find_url($data['title']);

            if(array_key_exists('keywords', $data) && $data['keywords'] != null)
            {
                $_keywords = str_replace("\n", ', ',  $data['keywords']);
            }
            else $_keywords = null;

            foreach($data as $k => $v)
            {
                if(is_array($v))
                {
                    $data[$k] = json::encode($v);

                    // Remove empty json items
                    $data[$k] = str_replace(['"",', ',""', "'',", ",''"], null, $data[$k]);
                }
            }

            //            $data['data'] = json::encode($others);
            $item_id = db::insert(self::$name, $data) or throw_exception(db::error());

            if($cx_type)
            {
                sys::array_key_default_value($cx_type, 'notification.insert', false);

                if($cx_type['notification.insert'] === true)
                {
                    $get_item = item::get(['where' => "id=$item_id"]);
                    $owner    = user::get();
                    _queue('_slack', [

                        'type'       => 'new',
                        'item.type'  => $cx_type['title'],
                        'user.name'  => $owner['title'],
                        'user.link'  => $owner['full_url'],
                        'text'       => $get_item['description'],
                        'link'       => $get_item['full_url'],
                        'title'      => $get_item['title']
                    ]);
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
     *
     * @return bool|int|string
     */
    public static function _insert(array $data = [])
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
            if($data['url'] != null) self::find_url($data['url']);
            if($data['title'] != null && $data['type'] != null && $data['url'] == null) $data['url'] = self::find_url($data['title']);

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

                    $mail = new mailer();
                    $mail->send('mustafa@aydemir.im', $mail_subject, $mail_content, 'mail');
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



    public static function find_url($title = null)
    {
        $slug    = _slugify($title);
        $counter = 2;
        $finded  = false;

        $exist = self::count([

            'where' => "`url` = '$slug'",
            'limit' => 1
        ]);

        if($exist > 0)
        {
            while(true)
            {
                $exist = self::count([

                    'where' => "`url` = '$slug.$counter'",
                    'limit' => 1
                ]);

                if($exist <= 0)
                {
                    $finded = "$slug.$counter";
                    break;
                }

                $counter++;
            }
        }
        else
        {
            $finded = $slug;
        }

        return $finded;
    }



    public static function update(array $data = [], $params = [])
    {
        try
        {
            // Check Database Fields
            foreach($data as $column => $value) self::check_field($column);

            // Generate URL
            if(array_key_exists('url', $data) && $data['url'] != null) self::find_url($data['url']);
            //            if($data['title'] != null && $data['type'] != null && $data['url'] == null) $data['url'] = self::find_url($data['title']);

            if(array_key_exists('keywords', $data) && $data['keywords'] != null)
            {
                $_keywords = str_replace("\n", ', ',  $data['keywords']);
            }
            else $_keywords = null;

            foreach($data as $k => $v)
            {
                if(is_array($v))
                {
                    $data[$k] = json::encode($v);

                    // Remove empty json items
                    $data[$k] = str_replace(['"",', ',""', "'',", ",''"], null, $data[$k]);
                }
            }

            //            $data['data'] = json::encode($others);
            $_update = db::update(self::$name, $data, $params) or throw_exception(db::error());

            $hook_data            = [];
            $hook_data['data']    = $data;
            $hook_data['params']  = $params;

            // Adding Tags
            $_tags_get_all   = self::get_all($params);
            if($_tags_get_all) foreach($_tags_get_all as $row)
            {
                $_tags = explode(',', $_keywords);
                foreach($_tags as $t) self::tag()->insert($row['id'], $t);
            }

            hook::listen("item.update", 'success', $hook_data);
            return $_update;
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
    public static function _update(array $data = [], array $params = [])
    {
        try
        {
            sys::array_key_default_value($data, 'created_at' , time());
            sys::array_key_default_value($data, 'released_at', null);
            sys::array_key_default_value($data, 'status'     , 'active');
            sys::array_key_default_value($data, 'ip'         , $_SERVER['REMOTE_ADDR']);
            sys::array_key_default_value($data, 'url'        , null);

            $columns = self::columns();
            $get_all = item::get_all($params, true);

            if($get_all == false) $get_all = [];

            $update_counter = 0;
            $moved_url_data = false;

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

                if(array_key_exists('url', $data) && $data['url'] != $get['url'] && $data['url'] != null)
                {
                    $moved_url_data = [

                        'type'     => 'redirect',
                        'title'    =>  $get['url'],
                        'url'      =>  $get['url'],
                        'moved_to' =>  $data['url']
                    ];
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

            if($update_counter > 0)
            {
                if($moved_url_data !== false)
                {
                    item::delete([

                        'type'  => 'redirect',
                        'where' => "`moved_to` = '{$moved_url_data['url']}' AND `url` = '{$moved_url_data['moved_to']}'",
                        'limit' => 1
                    ]);

                    item::insert($moved_url_data);
                }
                return true;
            }
            else
            {
                return false;
            }
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
            'format' => _config('date.pattern'),
            'date'   => _date(_config('date.pattern'), $data['created_at'])
        ];

        if($data['updated_at'] != null)
        {
            $data['updated_at'] = [

                'time'   => $data['updated_at'],
                'format' => _config('date.pattern'),
                'date'   => _date(_config('date.pattern'), $data['updated_at'])
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
            $_image_path = '/contents/images/' . $data['image'];
            if(_config('item.prepare.image.path')) $_image_path = trim(_config('item.prepare.image.path'), '/') . '/' . $data['image'];

            $data['image_url']   = html::image_link($data['image']);
            $data['image_thumb'] = html::image_link($data['image'], 400);
            $data['image_path']  = $_image_path;
        }
        else
        {
            $data['image_url']   = null;
            $data['image_thumb'] = null;
            $data['image_path']  = null;
        }

        if($data['url'] != null) $data['full_url'] = URL . '/' . $data['url'];

        $data['category'] = str_replace('[""]', null, $data['category']);
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
        if(array_key_exists('parents', $data) && json::valid($data['parents']))
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
        //
        //        if(array_key_exists('data', $data) && json::valid($data['data']))
        //        {
        //            $extracted_data = json::decode($data['data']);
        //
        //            foreach($extracted_data as $k => $v)
        //            {
        //                $v = str_replace('&quot;', '"', $v);
        //                $v = str_replace('&#039;', "'", $v);
        //
        //                if(is_string($v)) $v = htmlspecialchars_decode($v);
        //
        //                if( ! array_key_exists($k, $columns)) $columns[$k] = $v;
        //            }
        //
        //            if($type != null)
        //            {
        //                $type_columns = self::columns($type);
        //                sys::specify_params($columns, $type_columns);
        //            }
        //        }


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
    public static function select_params($params = [])
    {
        sys::array_key_default_value($params, 'where', null);
        sys::array_key_default_value($params, 'order_by', 'id DESC');
        sys::array_key_default_value($params, 'group_by', false);
        sys::array_key_default_value($params, 'limit', false);
        sys::array_key_default_value($params, 'meta', []);
        sys::array_key_default_value($params, 'type', null);
        sys::array_key_default_value($params, 'only_active', false);
        sys::array_key_default_value($params, 'show.all', false);
        sys::array_key_default_value($params, 'page', false);

        $sql_columns   = [];
        $name          = self::$name;
        $joins         = [];
        $where         = [];
        $time          = time();

        $where[] = "\t( `$name`.`id` IS NOT NULL ) AND ( `$name`.`released_at` IS NULL OR `$name`.`released_at` < $time ) AND (`$name`.`status` <> 'trash')";

        if($params['show.all']    != true) $where[] = "\t( `$name`.`status` = 'active' )";
        if($params['type']        != null) $where[] = "\t( `$name`.`type` = '{$params['type']}' )";

        foreach($params as $k => $v)
        {
            if(preg_match("%^by\.(.*?)$%si", $k, $by_matches))
            {
                if(array_key_exists('by.' . $by_matches[1], $params)) $where[] = "\t( `$name`.`{$by_matches[1]}` = '{$params['by.' . $by_matches[1]]}' )";
                unset($params['by.' . $by_matches[1]]);
            }
        }

        if($params['where']       != null) $where[] = "\t( {$params['where']} )";
        $params['where'] = implode(" AND \n", $where);

        $limit_start = 0;
        $limit_end   = $params['limit'];

        if($params['page'] != false)
        {
            $page_no = input::get($params['page']);
            $limit_start = $page_no * $params['limit'];
        }

        return $params;
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
        sys::array_key_default_value($params, 'prepare', true);

        $params['limit'] = 1;

        $params = self::select_params($params);
        $result = db::get(self::$name, $params);

        if(count($result) <= 0) return false;

        if($params['prepare'] === true) $result = self::prepare($result, $params['cache.disable']);

        return $result;
    }

    /**
     * @param array $params
     *
     * @return array|bool
     */
    public static function get_all($params = [], $show_all = false)
    {
        try
        {
            sys::array_key_default_value($params, 'show.all', $show_all);
            sys::array_key_default_value($params, 'cache.disable', false);
            sys::array_key_default_value($params, 'prepare', true);

            $params = self::select_params($params);

            $result = [];
            $items  = db::get_all(self::$name, $params);

            if($items) foreach($items as $item)
            {

                if($params['prepare'] === true) $result[] = self::prepare($item, $params['cache.disable']);
                else                            $result[] = $item;
            }

            if(count($result) <= 0) return false;
            return $result;
        }
        catch(Exception $e)
        {
            return false;
        }
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

        return _render('item/list', [

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


    public static function count($params)
    {
        return db::count_rows(self::$name, $params);
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

        if($options) foreach($options as $option) $rdata[$option['title']] = $option['content'];

        cache::create($cache_id, $rdata);

        return $rdata;
    }


    public static function tag()
    {
        return new tag();
    }


    public static function next($type = null, $ref_id = null)
    {
        try
        {
            if($type == null)   throw_exception('No type.');
            if($ref_id == null) throw_exception('No reference ID.');

            $params          = [];
            $params['type']  = $type;
            $params['where'] = "`id` > $ref_id";

            $next_item = item::get($params);
            if($next_item) return $next_item;
            else           return false;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public static function prev($type = null, $ref_id = null)
    {
        try
        {
            if($type == null)   throw_exception('No type.');
            if($ref_id == null) throw_exception('No reference ID.');

            $params          = [];
            $params['type']  = $type;
            $params['where'] = "`id` < $ref_id";

            $prev_item = item::get($params);
            if($prev_item) return $prev_item;
            else           return false;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public static function error()
    {
        return db::error();
    }


    public static function search($s = null, $limit = 20)
    {
        if($s == null) return false;
        if(_config('search.allowed_types'))
        {
            $types = _config('search.allowed_types');
        }
        else
        {
            $types = ['user'];
        }

        $type_where = [];
        foreach($types as $type) $type_where[] = "(`type` = '$type')";
        $type_where = implode(' OR ', $type_where);

        $ignored = ['type', 'password', 'user', 'layout', 'permissions', 'authority'];
        $where   = db::search_where_query(self::$name, $s, $ignored);
        $where  .= " AND (`title` IS NOT NULL) AND ($type_where)";

        $params = [];
        $params['limit'] = $limit;
        $params['where'] = $where;

        return item::latest($params);
    }

}