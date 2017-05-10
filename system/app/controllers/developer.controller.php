<?php


class controller_developer extends controller
{

    function __construct()
    {
        $installed = cx::option('app.installed');
        if($installed)
        {
            if(user::authority() !== 'developer')
            {
                sys::location();
                exit;
            }
        }
        else
        {
            if(METHOD !== 'install')
            {
                sys::location();
                exit;
            }
        }
    }

    
    function install()
    {
        try
        {
            hook::listen('install', 'before');

            db::query('
            SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
            SET time_zone = "+00:00";
            
            /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
            /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
            /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
            /*!40101 SET NAMES utf8mb4 */;
            
            CREATE TABLE `items` (
              `id` int(11) NOT NULL,
              `created_at` bigint(11) DEFAULT NULL,
              `released_at` bigint(20) DEFAULT NULL,
              `updated_at` bigint(11) DEFAULT NULL,
              `user` text,
              `parent` int(11) DEFAULT NULL,
              `type` varchar(100) DEFAULT NULL,
              `title` longtext,
              `content` longtext,
              `url` longtext,
              `category` longtext,
              `status` varchar(100) DEFAULT NULL,
              `locked` varchar(100) DEFAULT NULL,
              `data` longtext,
              `layout` varchar(100) DEFAULT NULL,
              `ip` varchar(20) DEFAULT NULL,
              `views` bigint(20) DEFAULT NULL,
              `keywords` longtext,
              `description` longtext,
              `visitor_ip` longtext,
              `image` varchar(255) DEFAULT NULL,
              `video` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            CREATE TABLE `item_meta` (
              `id` int(11) NOT NULL,
              `item_id` bigint(20) NOT NULL,
              `meta_key` varchar(255) NOT NULL,
              `meta_value` longtext NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            ALTER TABLE `items`
              ADD PRIMARY KEY (`id`);
            
            ALTER TABLE `item_meta`
              ADD PRIMARY KEY (`id`);
            
            ALTER TABLE `items`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;
            
            ALTER TABLE `item_meta`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=576;
            /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
            /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
            /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

            ');

            // Create System Folders
            sys::write(['file' => SYSDATA_PATH . DS . 'sessions' . DS . 'index.html']);
            sys::write(['file' => SYSDATA_PATH . DS . 'backups'  . DS . 'index.html']);
            sys::write(['file' => SYSDATA_PATH . DS . 'cache'    . DS . 'index.html']);
            sys::write(['file' => SYSDATA_PATH . DS . 'logs'     . DS . 'index.html']);
            sys::write(['file' => SYSDATA_PATH . DS . 'timer'    . DS . 'index.html']);
            sys::write(['file' => SYSDATA_PATH . DS . 'timer'    . DS . 'counter.cx']);
            sys::write(['file' => SYSDATA_PATH . DS . 'timer'    . DS . 'data.cx']);

            $user = [];
            $user['title'] = 'Super Admin';
            $user['about'] = null;
            $user['url']   = 'user/super-admin';

            $user['username']  = 'admin';
            $user['password']  = 'admin';
            $user['email']     = 'admin@criexe.com';
            $user['authority'] = 'developer';

            if(user::register($user)['status'] == true) echo 'User Added.'; else echo 'User Error.';

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
        global $files;

        cx::title('Developer');

        layout::set('developer');
        $this->render('developer/index', [

            'files' => $files
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

        echo _render('forms/new', null, $render);
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
        try
        {
            $data = [];
            $data['timer_files']  = timer::files();
            $data['last_runtime'] = timer::data('last_runtime');
            $data['counter_data'] = sys::read(SYSDATA_PATH . DS . 'timer' . DS . 'counter.cx');
            
            cx::title('Timer');
            layout::set('developer');
            $this->render('developer/timer', $data);
        }
        catch(Exception $e)
        {
            echo logger::add($e->getMessage());
        }
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

    function _image_local_to_cdn()
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



    function _image_cdn_to_local()
    {
        try
        {
            $items = item::get_all(['limit' => 10000000]);

            foreach($items as $item)
            {
                $v = $item['content'];

                if(is_string($v) && preg_match_all("#<img.*?src=[\"'](https://res.cloudinary.com/criexe/image/upload/v.*?/(.*?))[\"'].*?>#si", $v, $m))
                {
                    echo "\n\n<br><br>";
//                        print_r($m[1]);
                    $xi = 0;
                    foreach($m[2] as $image)
                    {
//                        copy($m[1][$xi], CONTENTS_PATH . "/images/$image");
                        echo "$image : {$m[1][$xi]} : " . _config('image.url') . "/$image <br>";
                        $xi ++;

                    }
                }

                $udata['content'] = preg_replace("#[\"']https://res.cloudinary.com/criexe/image/upload/v.*?/(.*?)[\"']#si", "\"https://kizlar.online/contents/images/$1\"", $v);
                item::update($udata, ["where" => "`id` = {$item['id']}"]);

            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    function generate_sitemap()
    {
        $sitemap = new sitemap();
        $sitemap->generate();
    }


    function info()
    {
        echo phpinfo();
    }

}