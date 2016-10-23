<?php
/**
 * load_site.php
 *
 * @author Mustafa Aydemir
 * @date   17.10.15
 */


$route            = router::parse();
$controller_name  = $route[0];
$controller_class = 'controller_' . $controller_name;
$method_name      = isset($route[1]) ? $route[1] : sys::get_config('router')['defaults']['method'];
$method_name      = trim($method_name, '_');

define('CONTROLLER', $controller_name);

// Request Log
$request_log = json_encode([

    'info' => $_SERVER,
    'data' => [

        'post' => $_POST,
        'get'  => $_GET
    ],
    'time' => time()

]);

load::model('user:traffic')->insert([

    'user_id'  => user::id(),
    'ip'       => $_SERVER['REMOTE_ADDR'],
    'location' => $_SERVER['REQUEST_URI'],
    'info'     => json_encode($_SERVER),
    'data'     => json_encode([

        'get'  => $_GET,
        'post' => $_POST
    ]),
    'created_at' => time()
]);

// Load Controller
$controller_file        = CONTROLLERS_PATH . '/' . $controller_name . '.php';
$plugin_controller_file = PLUGINS_PATH . '/' . $controller_name . '/controller.php';

if(file_exists($controller_file))
{
    require_once($controller_file);
}
else if(file_exists($plugin_controller_file))
{
    require_once($plugin_controller_file);
}
else
{
    error::show_404();
}

$load = new $controller_class();

// $load->$method_name();

$_params = array();

if(method_exists($load, $method_name))
{
    call_user_func_array([$load, $method_name], router::others());
}
else
{
    error::show_404();
}

?>