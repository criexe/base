<?php
/**
 * admin.php
 *
 * @author Mustafa Aydemir
 * @date   17.10.15
 */



// Trait - Admin Database
require_once('trait.admin_database.php');



class controller_admin extends controller
{
    use admin_database;





    public function __construct()
    {
        parent::__construct();


        $userLib   = load::library('user:user');
        $userModel = load::model('user:user');


        // Check Authority
        if( !$userLib->get_authority() == 'admin' )
        {
            sys::location('/login');
            error::show_404();
            exit;
        }


        $this->set_title('Admin Panel');


        $this->username      = $userLib->get_username();
        $this->profile_image = $userLib->get_profile_image();
        $this->name_surname  = $userLib->get_name_surname();



        // Menu
        $plugin_list = plugin::list_all();
        $menu_conf   = [];

        foreach($plugin_list as $p)
        {
            $mConf = $this->get_menu($p);
            if($mConf) $menu_conf[$p] = $mConf;
        }

        $this->admin_menu = $menu_conf;
        unset($menu_conf);


        // Logger
        logger::add("[$this->username] {$_SERVER['REQUEST_URI']}", 'admin');
    }








    /**
     * Actions
     * ========================
     */


    public function index()
    {
        $this->set_title('Dashboard');
        $this->_admin_view('dashboard');
    }


    public function plugin_page($plugin = null, $page = null)
    {
        $file = PLUGINS_PATH . DS . $plugin . DS . 'admin' . DS . $page . '.php';

        if(file_exists($file))
        {
            $data = [

                'plugin' => [

                    'file' => $file,
                    'name' => $plugin
                ]
            ];

            $this->_admin_view('plugin_page', $data);
        }
        else
        {
            error::show_404();
        }

        cache::create();
    }




    /**
     * ============================
     * === Functions ==============
     * ============================
     */


    public function _admin_view($name = null, $data = null)
    {
        $this->view($name, $data, 'admin');
    }



    public function get_menu($plugin = null)
    {
        if($plugin == null) return false;
        if(!plugin::exist($plugin)) return false;

        $menu_file = PLUGINS_PATH . DS . $plugin . DS . 'admin' . DS . '_menu.php';

        if(!file_exists($menu_file)) return false;

        $menu = include $menu_file;

        return $menu;
    }


    public function left_menu()
    {
        try
        {
            $rMenu       = null;
            $current_tab = str_replace('/admin/', '', $_SERVER['REQUEST_URI']);

            if(is_array($this->admin_menu))
            {
                foreach($this->admin_menu as $plugin_name => $menu_item)
                {
                    foreach($menu_item as $item)
                    {

                        if(!array_key_exists('type', $item)) $item['type'] = null;
                        if(!array_key_exists('icon', $item)) $item['icon'] = '<i class="fa fa-circle-o"></i>';


                        // Menu Link
                        $menu_link = null;
                        switch($item['type'])
                        {
                            case 'list':

                                $menu_link = URL . "/admin/db_list/{$item['link']}";
                                break;

                            case 'add':

                                $menu_link = URL . "/admin/db_insert_form/{$item['link']}";
                                break;

                            case 'page':

                                $menu_link = URL . "/admin/plugin_page/$plugin_name/{$item['link']}";
                                break;

                            default:

                                $menu_link = null;
                                break;
                        }

                        $rMenu .= '<li class="admin-menu-item">'; // TODO: active class
                        $rMenu .= '<a href="' . $menu_link . '">';
                        $rMenu .= $item['icon'] . strip_tags($item['display']);

                        // Caret Down
//                        if(array_key_exists('sub', $item)) $rMenu .= '<i class="fa fa-circle-thin pull-right caret-icon"></i>';

                        $rMenu .= '</a>';


                        // ==== SubMenu ==========
                        if(array_key_exists('sub', $item))
                        {
                            $rMenu .= '<ul class="submenu">';
                            foreach($item['sub'] as $sub)
                            {

                                if(!array_key_exists('type', $sub)) $sub['type'] = null;


                                // Menu Link
                                $sub_link = null;
                                switch($sub['type'])
                                {
                                    case 'list':

                                        $sub_link = URL . "/admin/db_list/{$sub['link']}";
                                        break;

                                    case 'add':

                                        $sub_link = URL . "/admin/db_insert_form/{$sub['link']}";
                                        break;

                                    case 'page':

                                        $sub_link = URL . "/admin/plugin_page/$plugin_name/{$sub['link']}";
                                        break;

                                    default:

                                        $sub_link = null;
                                        break;
                                }

                                $rMenu .= '<li><a href="' . $sub_link . '">' . $sub['display'] . '</a></li>';
                            }
                            $rMenu .= '</ul>';
                        }
                        $rMenu .= '</li>';
                    }
                }
            }

            return $rMenu;
        }
        catch(Exception $e)
        {
            logger::add($e->getMessage(), 'error');
        }
    }



}

?>