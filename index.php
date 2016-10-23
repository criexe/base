<?php
/**
 * index.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */


define('CX', true);

// Root Path
$_dir = dirname(__FILE__);
define('ROOT_PATH', dirname(__FILE__));


$config = [];
$router = [];

// Dırectory Seperator
define('DS', DIRECTORY_SEPARATOR);

define('APP_PATH', ROOT_PATH . '/app');

define('SYSTEM_PATH', ROOT_PATH . '/system');

define('VALIDATIONS_PATH', APP_PATH . '/_config/_validations');

define('CACHE_PATH', ROOT_PATH . '/sysdata/cache');

define('PAGES_PATH', ROOT_PATH . '/pages');

define('LOGS_PATH', ROOT_PATH . '/sysdata/logs');

define('TRASH_PATH', ROOT_PATH . '/sysdata/trash');

define('LIBRARIES_PATH', APP_PATH . '/libraries');

// System
define('SYSTEM_LIB_PATH', SYSTEM_PATH . '/lib');
define('SYSTEM_INCLUDES_PATH', SYSTEM_PATH . '/includes');
define('SYSDATA_PATH', ROOT_PATH . '/sysdata');

// Contents
define('CONTENTS_PATH', ROOT_PATH . '/contents');
define('MAPS_PATH', CONTENTS_PATH . '/_maps');

// ASSETS
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('ASSETS_SYS_PATH', ASSETS_PATH . '/sys');


// App Paths
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('VIEWS_PATH',       APP_PATH . '/views');
define('MODELS_PATH',      APP_PATH . '/models');
define('PLUGINS_PATH',     APP_PATH . '/plugins');
define('LAYOUTS_PATH',     APP_PATH . '/views/_layouts');


// Languages
define('LANGUAGES_PATH', APP_PATH . '/languages');


// Admin
define('_ADMIN', '/admin');
define('_ADMIN_DB_LIST', _ADMIN . '/db_list');



try
{
    // Detect Config Path
    $config_folder = $_SERVER['HTTP_HOST'];
    if(strpos($config_folder, ':') !== false) $config_folder = explode(':', $config_folder)[0];

    $config_dir = APP_PATH . '/_config/' . $config_folder;

    if(is_dir($config_dir))
    {
        define('CONFIG_PATH', $config_dir);
    }
    else
    {
        define('CONFIG_PATH', APP_PATH . '/_config/_default');
    }
    unset($config_dir);



    // Autoload Functions
    require SYSTEM_LIB_PATH . '/functions.autoload.php';


    // Includes
    require SYSTEM_LIB_PATH . '/functions.system.php';


    // Display Errors
    if(sys::get_config('errors')['active'] === true)
    {
        ini_set('display_errors', 'on');
        error_reporting(E_ALL);
    }
    else
    {
        ini_set('display_errors', 'off');
        error_reporting(0);
    }


    // PHP Log File
    ini_set('log_errors', 1);
    ini_set('error_log', LOGS_PATH . '/php/error.log');


    // Session Start
    session::start();


    // Router Start
    router::start();


    // Language
    lang::start();
    define('CURRENT_LANGUAGE', lang::current());

    // URL - Links
    define('URL'       , lang::url());
    define('CONTENTS'  , sys::get_config('application')['url'] . '/contents');
    define('ASSETS'    , sys::get_config('application')['url'] . '/assets');
    define('ASSETS_SYS', sys::get_config('application')['url'] . '/assets/sys');


    require_once CONFIG_PATH . '/settings.php';



    // Database Connection
    $db_config = sys::get_config('database');

    if($db_config['active'] === true)
    {
        db::$user    = $db_config['user'];
        db::$pass    = $db_config['pass'];
        db::$name    = $db_config['name'];
        db::$host    = $db_config['host'];
        db::$port    = $db_config['port'];
        db::$charset = $db_config['charset'];
        db::$prefix  = $db_config['prefix'];

        // Remove db_config variable
        unset($db_config);

        db::connect();
    }



    // PLugin Settings
    $_plugin_settings = SYSTEM_INCLUDES_PATH . '/plugin_settings.php';
    if(file_exists($_plugin_settings)) require_once $_plugin_settings;
    unset($_plugin_settings);



    // Browser Caching
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');


    // [File] Load Site
    $_load_site = SYSTEM_INCLUDES_PATH . '/load_site.php';
    if(!file_exists($_load_site))
    {
        error::show_404();
    }




    // User
    $userLib      = load::library('user:user');
    $user_cookie = cookie::get('user');

    if( $user_cookie != null )
    {
        $uCookie = explode('-', $user_cookie);
        if(load::model('user:user')->create_cookie_key( $uCookie[0] ) != $user_cookie)
        {
            $userLib->logout();
            $userLib->set_user_id(null);
            $userLib->set_username(null);
            $userLib->set_authority(null);
        }
        else
        {
            if(!$userLib->get_user_id())
                $userLib->set_user_id( $uCookie[0] );

            if(!$userLib->get_username())
                $userLib->set_username( load::model('user:user')->get_username($uCookie[0]) );

            if(!$userLib->get_authority())
                $userLib->set_authority( load::model('user:user')->get_authority($uCookie[0]) );
        }
    }
    unset($user_cookie, $userLib);




    // Load Site
    if(sys::get_config('cache')['active'] === true && !$_POST)
    {
        if(cache::get())
        {
            cache::load( cache::get() );
        }
        else
        {
            // =======================================================
            require $_load_site;                  // Load Site =======
            // =======================================================
        }
    }
    else
    {
        // =======================================================
        require $_load_site;                  // Load Site =======
        // =======================================================
    }


}
catch(Exception $e)
{
    logger::add('[ERROR] ' . $e->getMessage(), 'error');
    die($e->getMessage());
}
finally
{
    db::close();
    hook::listen('page', 'after');
}

?>