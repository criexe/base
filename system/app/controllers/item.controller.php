<?php


class controller_item extends controller
{


    function load($url = null, $data = [])
    {
        try
        {
            if($url == null) $url = input::get('url');
            if($url == null) error::show_404();

            cx::title($data['title']);
            cx::description($data['description']);
            cx::keywords($data['keywords']);
            cx::canonical($data['full_url']);

            $cx_type   = cx::type($data['type']);
            $cx_layout = $cx_type['layout'];

            $render = [];

            if      ($data['layout'] != null) $render['layout'] = $data['layout'];
            else if ($cx_layout      != null) $render['layout'] = $cx_layout;
            else                              $render['layout'] = 'item';

            $render['ext'] = $data['type'] != null ? 'type' : 'view';

            $type_file = $data['type'];
            $view_file = $type_file ? $type_file : 'item/index';

            echo cx::render($view_file, ['data' => $data], $render);

            // Views
            $views     = (int)$data['views'];
            $new_views = 0;

            if((int)$views <= 0) $new_views = 1;
            else $new_views = $views + 1;
//            cx::counter("item.views.{$data['id']}", 1);

            item::update(['views' => $new_views], ['where' => "`id` = {$data['id']}"]) or throw_exception('Err');
        }
        catch(Exception $e)
        {
            error::show_404();
        }
    }


}