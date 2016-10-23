<?php
/**
 * class.controller.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */


class controller
{

    private $_default_method = null;
    private $_view_folder    = null;
    private $_current_plugin = null;

    private $_page_title     = null;
    private $_page_desc      = null;

    public function __construct(){}



    /**
     * @param null $name
     * @param null $data
     * @param bool $return_data
     *
     * @return bool
     */
    public function view ($name = null, $data = null, $layout = 'default')
    {
        try
        {
            if($name == null)
            {
                throw_exception('View file name can\'t empty.');
            }
            else
            {
                $_views_path = VIEWS_PATH;
                if($this->get_current_plugin() != null) $_views_path = PLUGINS_PATH . '/' . $this->get_current_plugin() . '/views';


                if($this->_view_folder == null)
                    $view_file = $_views_path . '/' . $name . '.php';
                else
                    $view_file = $_views_path . '/' . $this->_view_folder . '/' . $name . '.php';

                if(!file_exists($view_file))
                {
                    throw_exception("View not found : $name - $view_file");
                }
                else
                {

                    // Data Variables
                    if($data != null)
                        foreach($data as $k => $v)
                            $$k = $v;


                    // Layout
                    if($layout == null || input::get('layout') === 'false')
                    {
                        include $view_file;
                        echo "<script>ga('set', {page: '{$_SERVER['REQUEST_URI']}', title: document.title}); ga('send', 'pageview');</script>";
                    }
                    else
                    {
                        // Save Minified Assets
                        asset::save($this->get_current_plugin());

                        // Layout Actions
                        $layout_path = LAYOUTS_PATH . '/' . $layout;
                        if(!file_exists($layout_path . '/begin.php') || !file_exists($layout_path . '/end.php'))
                        {
                            throw_exception('Layout not found.');
                        }
                        else
                        {
                            // Include View File
                            include $layout_path . '/begin.php';
                            include $view_file;
                            echo sys::get_config('tracker')['google_analytics'];
                            include $layout_path . '/end.php';
                        }
                    }
                }
            }
        }
        catch(Exception $e)
        {
            logger::add('view(): ' . $e->getMessage(), 'controller');
            error::show_404();
        }
    }








    // Set Methods =================

    public function set_view_folder($folder_name = null)
    {
        $this->_view_folder = $folder_name;
    }


    public function set_default_layout($layout_name = null)
    {
        $this->_default_layout = $layout_name;
    }


    public function set_title($title = null)
    {
        $this->_page_title = $title;
    }


    public function set_description($text = null)
    {
        $this->_page_desc = $text;
    }





    // Get Methods =================

    public function get_title()
    {
        return $this->_page_title;
    }

    public function get_description()
    {
        return html::description($this->_page_desc);
    }

    public function get_current_plugin()
    {
        return substr( get_class($this), 11 );
    }

}

?>