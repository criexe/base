<?php


class controller_sys extends controller
{


    function logout()
    {
        $ref = input::get('ref', ['empty' => '/']);

        user::logout();
        sys::location($ref);
    }


    function login()
    {
        if(is::posted())
        {
            $res = [];

            $user = input::post('user');
            $pass = input::post('pass');
            $ref  = input::post('ref');
            $pass = user::create_password($pass);

            $login = user::login($user, $pass);

            if($login)
            {
                $res['status']   = true;
                $res['message']  = 'Logged in !';
                $res['location'] = URL . urldecode($ref);

                $cookie_key = user::create_cookie_key($login['id']);
                cookie::set('user', $cookie_key);
            }
            else
            {
                $res['status']  = false;
                $res['message'] = 'Failed. Try again.';
            }

            echo response::ajax($res);
        }
        else
        {
            cx::title('Login');
            layout::set('blank');

            $this->render('sys/login');
        }
    }


    function register()
    {
        if(is::posted())
        {
            $res = [];

            $name  = input::post('name');
            $user  = input::post('username');
            $email = input::post('email');
            $pass  = input::post('password');
            $ref   = input::post('ref');
            $pass  = user::create_password($pass);

            $reg_data = [];
            $reg_data['title']    = $name;
            $reg_data['username'] = $user;
            $reg_data['email']    = $email;
            $reg_data['password'] = $pass;

            $register = user::register($reg_data);

            if($register['status'])
            {
                $res['status']   = true;
                $res['message']  = 'Registered !';
                $res['location'] = URL . urldecode($ref);

                $cookie_key = user::create_cookie_key($register['id']);
                cookie::set('user', $cookie_key);
            }
            else
            {
                $res['status']  = false;
                $res['message'] = $register['message'];
            }

            echo response::ajax($res);
        }
        else
        {
            // TODO : Register Form
        }
    }


    function user_settings()
    {
        if(is::posted())
        {
            try
            {
                $res       = [];
                $id        = input::post('user_id')          or throw_exception('Error.');
                $curr_pass = input::post('current_password') or throw_exception('Enter your current password.');
                $tab       = input::post('tab')              or throw_exception('Error.');
                $settings  = $_POST['user_settings'];

                $curr_pass    = user::create_password($curr_pass);
                $current_data = user::get($id);

                if($current_data['password'] != $curr_pass) throw_exception('Wrong password.');

                // Security
                foreach($settings as $k => $v) $settings[$k] = filter::request($v);

                $uparams          = [];
                $uparams['type']  = 'user';
                $uparams['where'] = "`id` = $id";

                switch($tab)
                {
                    case 'general':

                        sys::specify_params($settings,
                            ['username', 'title', 'email', 'current-password',  'new-password', 'image', 'about'], false);

                        if($settings['title'] == false)    throw_exception('Please enter name.');
                        if($settings['username'] == false) throw_exception('Please enter username.');
                        if($settings['email'] == false)    throw_exception('Please enter email.');

                        $udata             = [];
                        $udata['image']    = $settings['image'];
                        $udata['title']    = $settings['title'];
                        $udata['username'] = $settings['username'];
                        $udata['email']    = $settings['email'];
                        $udata['content']  = $settings['content'];

                        $update = item::update($udata, $uparams) or throw_exception('Error. Please try later.');

                        $res['status']  = true;
                        $res['message'] = 'Success.';
                        break;

                    case 'password':

                        if($settings['new_password'] != $settings['new_password_repeat']) throw_exception('Passwords is not same.');

                        $udata             = [];
                        $udata['password'] = user::create_password($settings['new_password']);

                        $update = item::update($udata, $uparams) or throw_exception('Error. Please try later.');

                        $res['status']  = true;
                        $res['message'] = 'Success.';
                        break;

                    default:

                        $res['status']  = false;
                        $res['message'] = 'Error.';
                        break;
                }
            }
            catch(Exception $e)
            {
                $res['status']  = false;
                $res['message'] = $e->getMessage();
            }
            finally
            {
                echo response::ajax($res);
            }
        }
        else
        {
            // TODO : Register Form
        }
    }


    function rss()
    {
        try
        {
            $type      = input::get('type');
            $_title    = cx::option('app.name');
            $all_types = cx::type();

            $iparams          = [];
            $iparams['limit'] = 100;

            if($type)
            {
                $iparams['type'] = $type;

                $_title = $_title . ' - ' . $all_types[$type]['title'];
            }
            else
            {
                $rss_types = [];
                $_where    = [];

                foreach($all_types as $item_type)
                {
                    sys::array_key_default_value($item_type, 'rss', false);

                    if($item_type['rss'] === true)
                    {
                        $rss_types[] = $item_type['alias'];
                    }
                }

                foreach($rss_types as $rtype) $_where[] = "`type` = '$rtype'";
                $_where = implode(' OR ', $_where);

                $iparams['where'] = $_where;
            }

            header('Content-Type: application/rss+xml; charset=utf-8');

            $latest = item::latest($iparams);
            $rss    = cx::render('system/app/views/sys/rss', ['items' => $latest, 'title' => $_title]);
            echo $rss;
        }
        catch(Exception $e)
        {
            sys::location();
        }
    }

}