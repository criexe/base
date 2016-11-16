<?php

class timer
{


    public static $file_ext  = 'timer';
    public static $tasks     = [];
    public static $temp      = [];


    public static function clear_temp()
    {
        self::$temp = [];
    }


    public static function temp_exist($alias = null)
    {
        if(array_key_exists($alias, self::$temp))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public static function add_temp($alias = null)
    {
        $temp = self::$temp;
        unset($temp[$alias]);
        $temp[] = $alias;
    }


    public static function files()
    {
        $cache_id   = 'timer_files';
        $cache_data = cache::get($cache_id);

        if($cache_data) return $cache_data;

        $all_timer = cx::$files[self::$file_ext];
        $found     = preg_grep("%\." . self::$file_ext . "(?:\.php)?$%si", $all_timer);
        $found     = array_values($found);

        cache::create($cache_id, $found);

        return $found;
    }


    public static function task($time = null , $func)
    {
        if($time == null) return false;
        if($func == null) return false;

        self::$tasks[$time][] = $func;
    }


    public static function tasks()
    {
        return self::$tasks;
    }


    // Counter
    public static function get_periods()
    {
        return array_keys(self::tasks());
    }


    public static function counter($alias = null, $data = null)
    {
        $file         = SYSDATA_PATH . DS . 'timer' . DS . 'counter.cx';
        $counter_data = sys::read($file);

        if( ! json::valid($counter_data)) $counter_data = [];
        else                              $counter_data = json::decode($counter_data);

        // Get All Data
        if($alias == null && $data === null)
        {
            return $counter_data;
        }

        // Get Alias Data
        else if($alias != null && $data === null)
        {
            sys::array_key_default_value($counter_data, $alias);
            return $counter_data[$alias];
        }

        // New Data
        else if($alias != null && $data !== null)
        {
            sys::array_key_default_value($counter_data, $alias, 0);

            if( ! is_numeric($counter_data[$alias])) $counter_data[$alias] = 0;

            if($data === 0) $counter_data[$alias] = 1;
            else            $counter_data[$alias] = $counter_data[$alias] + $data;

            $write         = [];
            $write['mode'] = 'w';
            $write['file'] = $file;
            $write['data'] = json::encode($counter_data) . "\n";

            sys::write($write);

            return $counter_data;
        }
    }


    public static function cmd_string()
    {
        $timer_path  = ROOT_PATH . DS . 'timer.cx';
        $os         = sys::os();

        switch($os)
        {
            case 'linux' :
            case 'macos' :
            default      :

                $command = "chmod 755 $timer_path; nohup php $timer_path &";
                break;

            // TODO : Windows

        }

        return $command;
    }



    public static function data($alias = null, $data = null)
    {
        $file         = SYSDATA_PATH . DS . 'timer' . DS . 'data.cx';
        $current_data = sys::read($file);

        if( ! json::valid($current_data)) $current_data = [];
        else                              $current_data = json::decode($current_data);

        // Get All Data
        if($alias == null && $data == null)
        {
            return $current_data;
        }

        // Get Alias Data
        else if($alias != null && $data == null)
        {
            sys::array_key_default_value($current_data, $alias);
            return $current_data[$alias];
        }

        // New Data
        else if($alias != null && $data != null)
        {
            sys::array_key_default_value($current_data, $alias, null);

            $current_data[$alias] = $data;

            $write         = [];
            $write['mode'] = 'w';
            $write['file'] = $file;
            $write['data'] = json::encode($current_data) . "\n";

            sys::write($write);

            return $current_data;
        }
    }


    public static function log($msg = null)
    {
        if($msg == null)
        {
            $log_file = LOGS_PATH . DS . 'timer.log';
            return sys::read($log_file);
        }
        else
        {
            return logger::add($msg, 'timer');
        }
    }


    public static function active()
    {
        $last_runtime = timer::data('last_runtime');

        if($last_runtime < time() - _MINUTE) return false;
        else                                 return true;
    }

}

?>