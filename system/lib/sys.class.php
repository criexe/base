<?php
/**
 * class.sys.php
 *
 * @author Mustafa Aydemir
 * @date   12.11.15
 */


class sys
{


    public static $date_format = 'F j, Y, g:i:s a';



    public static function location($where = '/')
    {
        if(strpos($where, '://') === false)
        {
            header('Location: ' . URL . $where);
        }
        else
        {
            header('Location: ' . $where);
        }
    }


    /**
     * @param null $command
     * @param null $func
     *
     * @return string
     */
    public static function exec($command = null, $func = null)
    {
        return shell_exec($command);
    }


    /**
     * @param array $params
     *
     * @return bool|null
     */
    public static function write($params = [])
    {
        try
        {
            if(!array_key_exists('file', $params)) throw_exception('No file name.');
            if(!array_key_exists('mode', $params)) $params['mode'] = 'a';
            if(!array_key_exists('data', $params)) $params['data'] = null;

            if(strlen($params['file']) > 250) throw_exception('File name too long.');

            self::create_folder( dirname($params['file']) );

            $f = fopen($params['file'], $params['mode']);
            fwrite($f, $params['data']);
            fclose($f);

            return $params['data'];
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    public static function read($file = null)
    {
        try
        {
            if( ! file_exists($file)) throw_exception('File is not exist.');

            $f       = fopen($file, 'r') or throw_exception('Error : Opening file (' . $file . ')');
            $content = fread($f, filesize($file) + 1);
            fclose($f);

            return $content;
        }
        catch(Exception $e)
        {
            logger::add('sys::read() - ' . $e->getMessage());
            return false;
        }
    }



    public static function delete($file = null)
    {
        try
        {
            if(file_exists($file))
            {
                unlink($file);
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(Exception $e)
        {
            logger::add('sys::delete() - ' . $e->getMessage());
            return false;
        }
    }



    /**
     * @param null $path
     * @param int  $chmod
     *
     * @return bool
     */
    public static function create_folder($path = null, $chmod = 0777)
    {
        if($path == null) return false;

        if(!is_dir($path)) mkdir($path, $chmod, true);

        return true;
    }



    /**
     * @param null $config_file
     *
     * @return bool|mixed
     */
    public static function get_config($config_file = null)
    {
        if($config_file == null) return false;

        $file_path = CONFIG_PATH . DS . $config_file . '.config.php';
        if(!file_exists($file_path)) return false;

        $config = include $file_path;
        return $config;
    }



    /**
     * @param null $array
     * @param null $key
     * @param null $value
     *
     * @return bool
     */
    public static function array_key_default_value(&$array = null, $key = null, $value = null)
    {
        if($key == null) return false;
        if($array == null) $array = [];

        if(!array_key_exists($key, $array))
        {
            $array[$key] = $value;
        }
    }



    /**
     * @param null  $array
     * @param array $keys
     * @param null  $default_value
     */
    public static function specify_params(&$array = null, $keys = [], $default_value = null)
    {
        if(!is_array($keys)) $keys = [];
        if($array == null) $array = [];

        foreach($keys as $key)
        {
            self::array_key_default_value($array, $key, $default_value);
        }
    }



    /**
     * @param null  $param
     * @param array $values
     */
    public static function allowable_parameter_values(&$param, $values = [])
    {
        if(!is_array($values)) $values = [];

        if(!in_array($param, $values))
        {
            $param = null;
        }
    }



    public static function files($dir = null)
    {
        if($dir == null) $dir = ROOT_PATH;

        $result = [];
        $cdir   = scandir($dir);

        foreach ($cdir as $key => $value)
        {
            if (!in_array($value, [".", ".."]))
            {
                if (is_dir($dir . DS . $value))
                {
                    $result[] = $dir . DS . $value . DS . self::files($dir . DS . $value);
                }
                else
                {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }


    public static function scan_dir($dir = null, array $all_data = [])
    {
        if($dir == null) $dir = ROOT_PATH;

        $invisible_file_names = ['.', '..'];
        $dir_content          = scandir($dir);

        foreach($dir_content as $key => $content)
        {
            $path = $dir . '/' . $content;

            if(!in_array($content, $invisible_file_names))
            {
                if(is_file($path) && is_readable($path))
                {
                    $all_data[] = $path;
                }
                else if(is_dir($path) && is_readable($path))
                {
                    $all_data = self::scan_dir($path, $all_data);
                }
            }
        }
        return $all_data;
    }


    public static function file_ext_replace($filename = null, $old = null, $new = null)
    {
        $filename = preg_replace("#\." . $old . "$#si", ".$new", $filename);
        return $filename;
    }


    public static function get_software_files()
    {
        $all_files = sys::scan_dir('system');
        $all_files = sys::scan_dir('app', $all_files);

        $files = [

            'class'      => preg_grep( "/([0-9a-zA-z_]*?)\.class(?:\.php)?$/si"      , $all_files),
            'controller' => preg_grep( "/([0-9a-zA-z_]*?)\.controller(?:\.php)?$/si" , $all_files),
            'model'      => preg_grep( "/([0-9a-zA-z_]*?)\.model(?:\.php)?$/si"      , $all_files),
            'view'       => preg_grep( "/([0-9a-zA-z_\/]*?)\.view(?:\.php)?$/si"     , $all_files),
            'layout'     => preg_grep( "/([0-9a-zA-z_]*?)\.layout(?:\.php)?$/si"     , $all_files),
            'lang'       => preg_grep( "/([0-9a-zA-z_]*?)\.lang(?:\.php)?$/si"       , $all_files),
            'validation' => preg_grep( "/([0-9a-zA-z_]*?)\.input(?:\.php)?$/si"      , $all_files),
            'config'     => preg_grep( "/([0-9a-zA-z_]*?)\.config(?:\.php)?$/si"     , $all_files),
            'settings'   => preg_grep( "/([0-9a-zA-z_]*?)\.settings(?:\.php)?$/si"   , $all_files),
            'form'       => preg_grep( "/([0-9a-zA-z_]*?)\.form(?:\.php)?$/si"       , $all_files),
            'type'       => preg_grep( "/([0-9a-zA-z_]*?)\.type(?:\.php)?$/si"       , $all_files),
            'timer'      => preg_grep( "/([0-9a-zA-z_]*?)\.timer(?:\.php)?$/si"      , $all_files),
        ];

        return $files;
    }


    public static function find_view($name = null)
    {
        $all_views = cx::$files['view'];
        $found     = preg_grep("%" . $name . "\.view(?:\.php)?$%si", $all_views); // example.view.php
        $found     = array_values($found);

        if(count($found) > 0)
        {
            $view_path = $found[0];
        }
    }


    public static function find_layout($name = null)
    {
        $all   = cx::$files['layout'];
        $found = preg_grep("/" . $name . "\.layout(?:\.php)?$/si", $all);
        $found = array_values($found);

        if(count($found) > 0)
        {
            return $found[0];
        }
        else
        {
            return false;
        }
    }


    public static function find_type($name = null)
    {
        $all_types = cx::$files['type'];
        $found     = preg_grep("%" . $name . "\.type(?:\.php)?$%si", $all_types); // example.type.php
        $found     = array_values($found);

        if(count($found) > 0)
        {
            return $found[0];
        }
        else
        {
            return false;
        }
    }


    public static function os($is = null)
    {
        $os = strtolower(PHP_OS);
        $is = strtolower($is);

        switch($os)
        {
            case 'darwin': $os = 'macos'; break;
        }

        if($is == null)
        {
            return $os;
        }
        else
        {
            if($os == $is)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }


    public static function date($time = null)
    {
        if($time == null)
        {
            return date(self::$date_format);
        }
        else
        {
            return date(self::$date_format, $time);
        }
    }

}


?>