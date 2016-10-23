<?php
/**
 * class.sys.php
 *
 * @author Mustafa Aydemir
 * @date   12.11.15
 */


class sys
{

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

        $file_path = CONFIG_PATH . DS . $config_file . '.php';
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


    public static function create_map_name($name = null)
    {
        if($name == null) return null;

        return filter::slugify($name, [], '_') . '.map';
    }


    public static function create_map_file($file = null, $data = [])
    {
        self::write([

            'file' => MAPS_PATH . DS . self::create_map_name($file),
            'data' => json_encode($data)
        ]);
    }

    public static function map_exist($file = null)
    {
        if(file_exists(MAPS_PATH . DS . self::create_map_name($file))) return true; else return false;
    }


    public static function get_map($file = null)
    {
        if( ! self::map_exist($file)) return false;

        $content = file_get_contents(MAPS_PATH . DS . self::create_map_name($file));

        return json_decode($content, true);
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

}


?>