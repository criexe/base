<?php


class controller_developer extends controller
{


    function install()
    {
        try
        {
            hook::listen('install', 'before');

            // Create System Folders
            sys::write(['file' => SYSDATA_PATH . DS . 'backups' . DS . 'index.html']);
            sys::write(['file' => SYSDATA_PATH . DS . 'cache'   . DS . 'index.html']);
            sys::write(['file' => SYSDATA_PATH . DS . 'logs'    . DS . 'index.html']);
            sys::write(['file' => SYSDATA_PATH . DS . 'timer'   . DS . 'index.html']);
            sys::write(['file' => SYSDATA_PATH . DS . 'timer'   . DS . 'counter.cx']);
            sys::write(['file' => SYSDATA_PATH . DS . 'timer'   . DS . 'data.cx']);

            $user = [];
            $user['title'] = 'Super Admin';
            $user['about'] = null;
            $user['url']   = 'super-admin';
            $user['type']  = 'user';

            $user['username']  = 'admin';
            $user['password']  = 'admin';
            $user['email']     = 'admin@criexe.com';
            $user['authority'] = 'developer';

            item::insert($user);

            cache::clear();

            hook::listen('install', 'after');

            echo 'Success';
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    function index()
    {
        cx::title('Developer');

        layout::set('developer');
        $this->render('developer/index', [

            'files' => cx::$files
        ]);
    }


    function logs()
    {
        $logs = sys::read(SYSDATA_PATH . DS . 'logs' . DS . 'logs.log');
        $logs = explode("\n", $logs);

        cx::title('Logs');
        layout::set('developer');
        $this->render('developer/logs', [

            'logs' => $logs
        ]);
    }


    function cache()
    {
        $caches = sys::scan_dir(CACHE_PATH);

        cx::title('Cache');
        layout::set('developer');
        $this->render('developer/caches', [

            'caches' => $caches
        ]);
    }


    function files()
    {
        $files = sys::get_software_files();

        cx::title('Files');
        layout::set('developer');
        $this->render('developer/json', [

            'header' => 'Software Files',
            'data'   => $files,
        ]);
    }


    function types()
    {
        cx::title('Types');

        $data = [];
        $data['types'] = cx::type();

        layout::set('developer');
        $this->render('developer/types', $data);
    }



    // Items ====================

    function item($action = 'latest')
    {
        switch($action)
        {
            case 'latest' : $this->_latest_items(); break;
            case 'new'    : $this->_new_item();     break;
            case 'export' : $this->_export_items(); break;
        }
    }

    function _latest_items()
    {
        $params = [];
        $params['design']['class']['container']   = 'grey darken-3';
        $params['design']['class']['title']       = 'grey-text text-lighten-2';
        $params['design']['class']['action_link'] = 'grey-text text-lighten-1';

        $params['db']['limit'] = 100;

        $list_data = item::dblist($params);

        layout::set('developer');
        $this->render('developer/latest', [

            'data' => $list_data
        ]);
    }

    function _new_item()
    {
        $render           = [];
        $render['ext']    = 'form';
        $render['layout'] = 'developer';

        echo cx::render('forms/new', null, $render);
    }


    function _export_items()
    {
        $data     = db::export();
        $data_len = strlen($data);
        $filename = 'items.cx.export';

        header("Content-Type: text/plain");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . $data_len);

        echo $data;
    }



    // Timer ====================

    function timer()
    {
        $data = [];
        $data['files']        = timer::files();
        $data['last_runtime'] = timer::data('last_runtime');
        $data['counter_data'] = sys::read(SYSDATA_PATH . DS . 'timer' . DS . 'counter.cx');

        cx::title('Timer');
        layout::set('developer');
        $this->render('developer/timer', $data);
    }


    // IDE ====================

    function ide($lang = 'php')
    {
        cx::title('IDE : ' . $lang);
        layout::set('developer');

        $data = [];
        $data['lang'] = $lang;

        $this->render('developer/ide', $data);
    }


    // Actions ===============

    function image_local_to_cdn()
    {
        try
        {
            set_time_limit(0);
            ini_set('memory_limit', '-1');

            if(user::authority() != 'developer') sys::location('/');

            $items = item::get_all(['where' => "`image` IS NOT NULL"]);

            foreach($items as $item)
            {
                $udata   = [];
                $uparams = ['where' => "`id` = {$item['id']}"];


                $image = $item['image'];

                if(file_exists(CONTENTS_PATH . DS . $image))
                {
                    $upload = cdn::upload(CONTENTS . DS . $image);
                    $new_image = $upload['filename'];
                    $udata['image'] = $new_image;
                }

                foreach($item as $k => $v)
                {
                    if($v != null && is_string($v))
                    {
                        if(preg_match_all("#<img.*?src=['\"](http.*?)['\"].*?>#si", $v, $matches))
                        {
                            foreach($matches[1] as $link)
                            {
                                if(strpos($link, cx::option('cdn.base_url'))   === false &&
                                    strpos($link, cx::option('cdn.secure_url')) === false)
                                {
                                    //                                echo $link . "\n\n";
                                    $upload    = cdn::upload($link);
                                    $item[$k]  = str_replace($link, $upload['secure_url'], $item[$k]);
                                    $udata[$k] = $item[$k];
                                }
                            }
                        }
                    }
                }

                $update_items = item::update($udata, $uparams);

                print_r($udata);
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

}