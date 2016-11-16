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
        try
        {
            if($name == null)
            {
                throw_exception('View file name can\'t empty.');
            }
            else
            {
                sys::array_key_default_value($params, 'ext'        , 'view');
                sys::array_key_default_value($params, 'layout'     ,  false);
                sys::array_key_default_value($params, 'is_content' ,  true);

                // View content is a string
                // Ex : ['VIEW CONTENT']
                $is_view_array = is_array($name) && count($name) == 1;

                if( ! $is_view_array)
                {
                    $all_views = self::$files[$params['ext']];
                    $found     = preg_grep("%" . $name . "\." . $params['ext'] . "(?:\.php)?$%si", $all_views);
                    $found     = array_values($found);
                }
                else
                {
                    // count($found) > 0
                    $found = [true, true];

                }

                if(count($found) > 0)
                {
                    // Data Variables
                    if($datas != null)
                        foreach($datas as $k => $v)
                            $$k = $v;

                    if( ! $is_view_array)
                    {
                        $view_file = $found[0];

                        ob_start();
                        include $view_file;
                        $content = ob_get_contents();
                    }
                    else
                    {
                        $content = $name[0];
                    }

                    // Emoji
                    $content = emoji::to_image($content);

                    if($params['is_content'] === true) cx::data('layout_content', $content);
                    if( ! $is_view_array) ob_end_clean();

                    if(!array_key_exists('layout', $params) || $params['layout'] == false)
                    {
                        return $content;
                    }
                    else
                    {
                        ob_start();
                        $layout_file = sys::find_layout($params['layout']);
                        include $layout_file;

                        $layout_content = ob_get_contents();
                        ob_end_clean();

                        return $layout_content;
                    }
                }
            }
        }
        catch(Exception $e)
        {
            logger::add('view(): ' . $e->getMessage(), 'render');
            return false;
        }
    }


    public static function data($alias = null, $data = null)
    {
        if($alias == null) return false;

        // GET
        if($data == null)
        {
            sys::array_key_default_value(self::$datas, $alias, null);
            return self::$datas[$alias];
        }

        // SET
        else
        {
            self::$datas[$alias] = $data;
            return $data;
        }
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
            sys::specify_params($param, ['alias', 'title', 'name', 'columns', 'layout']);
            sys::array_key_default_value($param, 'form', 'forms/item');

            if($param['title'] == null && $param['name'] != null) $param['title'] = $param['name'];
            else if($param['title'] != null && $param['name'] == null) $param['name']  = $param['title'];

            $curr_types                  = self::data('types');
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

        return self::render('system/app/views/parts/head', null, $render);
    }


    public static function footer()
    {
        $render               = [];
        $render['is_content'] = false;

        return self::render('system/app/views/parts/footer', null, $render);
    }


    public static function title($title = null)
    {
        if($title == null)
        {
            return self::data('html.title');
        }
        else
        {
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
                $developers = sys::get_config('application')['developers'];
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


    // TODO : Config sistemi buraya geçirilecek
    public static function config($alias = null, $data = null)
    {
        if($alias == null) return false;

        // Get
        if($data == null)
        {

        }

        // Set
        else
        {

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

}