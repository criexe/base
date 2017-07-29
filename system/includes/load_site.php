<?php
/**
 * load_site.php
 *
 * @author Mustafa Aydemir
 * @date   17.10.15
 */


try
{

    $route            = router::parse();
    $controller_name  = $route[0];
    $controller_class = 'controller_' . $controller_name;
    $method_name      = isset($route[1]) ? $route[1] : _config('router.default')['method'];
    $method_name      = trim($method_name, '_');

    define('CONTROLLER' , $controller_name);
    define('METHOD'     , $method_name);

    // Get Item
    $_item_url = explode('/', url::path());
    $_item = url::path() != null ? item::get(['where' => "`url` = '" . $_item_url[0] . "'"]) : false;

    if($_item)
    {
        $controller_name  = 'item';
        $controller_class = 'controller_item';
        $method_name      = 'load';
    }

    // Get Controller File From Software Files
    $all_controllers   = $files['controller'];
    $found_controllers = preg_grep("/" . $controller_name . "\.controller(?:\.php)?$/si", $all_controllers);
    $found_controllers = array_values($found_controllers);

    if(count($found_controllers) > 0)
    {
        cx::$info = [

            'controller' => $controller_name,
            'method'     => $method_name,
        ];

        $controller_file = $found_controllers[0];
        require_once($controller_file);
    }
    else
    {
        _log('Controller is not exist : ' . $controller_name . "\n" . url::get());
//        error::show_404();
    }

    // Load Item
    if($_item)
    {
        cx::data('item.data', $_item);

        _data('item.url.parameters', $_item_url);

        $item_controller = new controller_item();
        $item_controller->load(url::path(), $_item);
    }

    // Load Controller
    else
    {
        if(!class_exists($controller_class)) _404();

        $load = new $controller_class();

        // $load->$method_name();

        $_params = array();

        if(method_exists($load, $method_name))
        {
            call_user_func_array([$load, $method_name], router::others());
        }
        else
        {
            _404();
        }
    }


    //    // Add Traffic Log
    //    item::insert([
    //
    //        'user'     => user::id(),
    //        'url'      => 'traffic/' . time() . '.' . rand(10000000000, 99999999999),
    //        'title'    => $_SERVER['REQUEST_URI'],
    //        'content'  => 'Traffic - ' . date('F j, Y, g:i a'),
    //        'status'   => 'passive',
    //
    //        'location' => $_SERVER['REQUEST_URI'],
    //        'info'     => $_SERVER,
    //        'datas'     => [
    //
    //            'get'  => $_GET,
    //            'post' => $_POST
    //        ]
    //    ]);

}
catch(Exception $e)
{
    _404();
}