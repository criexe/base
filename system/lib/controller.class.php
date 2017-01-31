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
    public function render ($name = null, $data = null)
    {
        $layout  = layout::get();
        $content = _render($name, $data);

        cx::data('layout_content', $content);

        if($layout == null)
        {
            echo $content;
        }
        else
        {
            $layout_file = sys::find_layout($layout);
            include $layout_file;
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