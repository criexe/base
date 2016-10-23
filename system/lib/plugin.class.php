<?php
/**
 * class.plugin.php
 *
 * @author Mustafa Aydemir
 * @date   30/12/15
 */


class plugin
{

    /**
     * @param null $file
     *
     * @return bool
     */
    public static function folder_name($file = null)
    {
        if(preg_match("#/app/plugins/(.*?)/#i", $file, $matches))
        {
            return $matches[1];
        }
        else
        {
            return false;
        }
    }


    /**
     * @param null $name
     *
     * @return bool
     */
    public static function exist($name = null)
    {
        if($name == null) return false;

        $file = PLUGINS_PATH . '/' . $name . '/_init.php';

        if(file_exists($file)) return true;
        else return false;
    }



    /**
     * @return array
     */
    public static function list_all()
    {
        $plugins        = scandir(PLUGINS_PATH);
        $active_plugins = [];

        foreach($plugins as $k)
        {
            $k = trim( trim($k, '/') );

            if(is_string($k) && $k[0] != '_' && $k != '.' && $k != '..' && !is_file(PLUGINS_PATH . DIRECTORY_SEPARATOR . $k))
            {
                $active_plugins[] = $k;
            }
        }

        return $active_plugins;
    }



    /**
     * @param null $page
     * @param null $plugin_folder
     *
     * @return string
     */
    public static function admin_link($page = null, $plugin_folder = null)
    {
        if($plugin_folder == null) return false;

        return URL . _ADMIN . '/plugin_page/' . $plugin_folder . '/' . $page;
    }

}

?>