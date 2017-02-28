<?php
/**
 * User: mustafa
 * Date: 23.07.2016
 * Time: 17:55
 */

class cx
{

    // All Files
    public static $files = [

        'class'      => [],
        'controller' => [],
        'model'      => [],
        'view'       => [],
        'layout'     => [],
        'lang'       => [],
        'validation' => [],
        'config'     => [],
        'settings'   => []
    ];


    public static $info = [

        'controller' => false,
        'method'     => false,
        'view'       => false,
    ];


    public static $datas   = [];
    public static $counter = [];


    public static function render($name = null, $datas = null, array $params = [])
    {
        return _render($name, $datas, $params);
    }


    public static function data($alias = null, $data = null)
    {
        return _data($alias, $data);
    }


    public static function all_data()
    {
        return self::$datas;
    }


    public static function type($param = null)
    {
        // Add New Type
        if(is_array($param))
        {
            $curr_types = self::data('types');

            // Merge
            if(is_array($curr_types) && array_key_exists($param['alias'], $curr_types)) $param = array_merge($curr_types[$param['alias']], $param);

            sys::specify_params($param, ['alias', 'title', 'name', 'columns', 'layout', 'amp']);
            sys::array_key_default_value($param, 'form', 'forms/item');

            if($param['title'] == null && $param['name'] != null) $param['title'] = $param['name'];
            else if($param['title'] != null && $param['name'] == null) $param['name']  = $param['title'];

            $curr_types[$param['alias']] = $param;

            self::data('types', $curr_types);
            return true;
        }

        // Get Type
        else if(is_string($param))
        {
            $all_types = self::data('types');

            if(array_key_exists($param, $all_types))
            {
                return $all_types[$param];
            }
            else
            {
                return false;
            }
        }

        // All Types
        else if($param == null)
        {
            return cx::data('types');
        }

        // ELse
        else
        {
            return false;
        }
    }


    public static function head()
    {
        $render               = [];
        $render['is_content'] = false;

        return _render('system/app/views/parts/head', null, $render);
    }


    public static function body()
    {
        $render               = [];
        $render['is_content'] = false;

        return _render('system/app/views/parts/body', null, $render);
    }


    public static function footer()
    {
        $render               = [];
        $render['is_content'] = false;

        return _render('system/app/views/parts/footer', null, $render);
    }


    public static function title($title = null, $app_name = true)
    {
        if($title == null)
        {
            return _config('html.title.emoji') . ' ' . self::data('html.title');
        }
        else
        {
            if($app_name == true) $title = $title . ' - ' . cx::option('app.name');
            return self::data('html.title', $title);
        }
    }


    public static function description($desc = null)
    {
        if($desc == null)
        {
            $result = self::data('html.description');
            if($result == null) $result = cx::option('app.description');

            return $result;
        }
        else
        {
            return self::data('html.description', $desc);
        }
    }


    public static function keywords($keywords = null)
    {
        if($keywords == null)
        {
            $result = self::data('html.keywords');
            if($result == null) $result = cx::option('app.keywords');

            return $result;
        }
        else
        {
            return self::data('html.keywords', $keywords);
        }
    }


    public static function author($author = null)
    {
        if($author == null)
        {
            $author = self::data('html.author');

            if($author == null)
            {
                $author     = [];
                $developers = _config('developers');
                foreach($developers as $name => $email) $author[] = $name;
                $author = implode(', ', $author);
            }
            return $author;
        }
        else
        {
            return self::data('html.author', $author);
        }
    }


    public static function canonical($uri = null)
    {
        if($uri == null)
        {
            return self::data('html.canonical') ? self::data('html.canonical') : url::get();
        }
        else
        {
            return self::data('html.canonical', $uri);
        }
    }


    public static function option($alias = null, $data = null)
    {
        $options = item::options();

        if(!$options) $options = [];

        // All Options
        if($alias == null)
        {
            return $options;
        }

        // Get By Alias
        else if($alias != null && $data == null)
        {
            sys::array_key_default_value($options, $alias, null);
            $result = $options[$alias];

            if(json::valid($result)) return json::decode($result);
            else                     return $result;
        }

        // Set Option
        else if($alias != null && $data != null)
        {
            $uparams          = [];
            $uparams['type']  = 'option';
            $uparams['where'] = "`title` = '$alias'";
            $uparams['limit'] = 1;

            $udata            = [];
            $udata['type']    = 'option';
            $udata['title']   = $alias;
            $udata['content'] = $data;

            if(array_key_exists($alias, $options)) $result = item::update($udata, $uparams);
            else                                   $result = item::insert($udata);

            cache::delete('options');

            return $result;
        }

        // Other
        else
        {
            return false;
        }
    }


    public static function counter($alias = null, $data = null)
    {
        return true;
        $current_data = item::get(['type' => 'counter']);

        if(!$current_data)
        {
            $current_data = [];
            item::insert(['type' => 'counter']);
        }

        if($alias == null)
        {
            return $current_data;
        }
        else if($alias != null && $data == null)
        {
            sys::array_key_default_value($current_data, $alias);
            return $current_data[$alias];
        }
        else if($alias != null && $data != null)
        {
            if(!is_numeric($data)) $data = 1;

            if(array_key_exists($alias, $current_data))
            {
                $new_data = $current_data[$alias] + $data;
            }
            else
            {
                $new_data = 1 + $data;
            }

            self::$counter[$alias] = $new_data;
        }
        else
        {
            return false;
        }
    }


    public static function save()
    {
        return true;
        $save = [];

        // Counter
        $save['counter'] = function(){

            if(count(self::$counter) > 0)
            {
                $uparams          = [];
                $uparams['type']  = 'counter';
                $uparams['where'] = "`type` = 'counter'";
                $uparams['limit'] = 1;

                if(item::update(self::$counter, $uparams)) cache::clear('cx.counter*');
            }
        };

        // Save
        $save['counter']();
    }


    public static function date($format = null, $time = null)
    {
        $lang = strtolower(lang::current());
        $date = date($format, $time);
        $old  = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $new  = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        if($lang == 'tr' || $lang == 'tr_tr')
        {
            $new = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
        }

        return str_replace($old, $new, $date);
    }

}