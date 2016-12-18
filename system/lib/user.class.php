<?php


class user
{

    public static function login($user = null, $password = null)
    {
        $r      = [];
        $params = [];

        $params['where'] = "(`username` = '$user' OR `email` = '$user') AND `password` = '$password'";
        $login           = item::get($params);

        return $login;
    }


    public static function register($register_data = [])
    {
        $res            = [];
        $res['status']  = false;
        $res['message'] = 'Error.';

        sys::specify_params($register_data, ['name', 'username', 'email', 'password'], false);

        try
        {
            if(
                $register_data['title']    == false ||
                $register_data['username'] == false ||
                $register_data['email']    == false ||
                $register_data['password'] == false ) throw_exception('Fill out the form.');

            $check_username = item::get(['type' => 'user', 'where' => "`username` = '{$register_data['username']}'"]);
            $check_email    = item::get(['type' => 'user', 'where' => "`email`    = '{$register_data['email']}'"]);

            if($check_username) throw_exception('This username is already using.');
            if($check_email)    throw_exception('This email is already using.');

            $register_data['type']  = 'user';
//            $register_data['title'] = 'user';
            unset($register_data['name']);

            $insert = item::insert($register_data);

            if($insert)
            {
                $res['status'] = true;
                $res['id']     = $insert;
            }
            else
            {
                throw_exception('Error. Please try later.');
            }
        }
        catch(Exception $e)
        {
            $res['status']  = false;
            $res['message'] = $e->getMessage();

            logger::add('Register : ' . $e->getMessage(), 'user');
        }
        finally
        {
            return $res;
        }
    }


    public static function logout()
    {
        session::delete('userid');
        session::delete('username');
        session::delete('user_authority');

        cookie::delete('user');
    }


    public static function logged_in()
    {
        if(session::get('userid') != null && session::get('username') != null)
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public static function must_login($location = '/')
    {
        if( !self::logged_in() )
        {
            sys::location($location);
            exit;
        }
    }


    public static function must_admin($location = '/')
    {
        if( !self::logged_in() || self::authority() != 'admin' )
        {
            sys::location($location);
            exit;
        }
    }



    public static function info($info = null)
    {
        if($info == null)
        {
            return session::get('user_info');
        }
        else
        {
            session::set('user_info', $info);
            return $info;
        }
    }

    public static function id($id = null)
    {
        if($id == null)
        {
            return session::get('userid');
        }
        else
        {
            session::set('userid', $id);
            return $id;
        }
    }

    public static function username($username = null)
    {
        if($username == null)
        {
            return session::get('username');
        }
        else
        {
            session::set('username', $username);
            return $username;
        }
    }

    public static function authority($auth = null)
    {
        if($auth == null)
        {
            return session::get('user_authority');
        }
        else
        {
            session::set('user_authority', $auth);
            return $auth;
        }
    }

    public static function permissions($permis = null)
    {
        if($permis == null)
        {
            return session::get('user_permissions');
        }
        else
        {
            session::set('user_permissions', $permis);
            return $permis;
        }
    }



    public static function name($data = null, $by = 'id')
    {
        if($data == null && $by == 'id') $data = self::id();

        return self::get($data, $by)['title'];
    }


    public static function get($data = null, $by = 'id')
    {
        if($data == null && $by == 'id') $data = self::id();

        $params          = [];
        $params['where'] = "`$by` = '$data'";

        return item::get($params);
    }


    public static function create_password($password = null)
    {
        return ($password);
    }


    public static function create_cookie_key($user_id = null)
    {
        if($user_id == null) return null;

        $ip         = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $cookie_key = "..::..||$user_id||$ip||$user_agent||=||::..";
        $key        = sha1(md5(md5($cookie_key)));
        $r          = $user_id . '-' . $key;

        return $r;
    }


    public static function prepare_permissions($permissions = null)
    {
        $all_types = cx::type();
        $permis    = [];


        // Set All
        if(is_bool($permissions))
        {
            $permis_value = $permissions;

            foreach($all_types as $k => $v)
            {
                $permis[$k]           = [];
                $permis[$k]['list']   = $permis_value;
                $permis[$k]['insert'] = $permis_value;
                $permis[$k]['update'] = $permis_value;
                $permis[$k]['delete'] = $permis_value;
            }
        }

        // No Permissions
        else if( ! is_array($permissions))
        {
            foreach($all_types as $k => $v)
            {
                $permis[$k]           = [];
                $permis[$k]['list']   = false;
                $permis[$k]['insert'] = false;
                $permis[$k]['update'] = false;
                $permis[$k]['delete'] = false;
            }
        }

        // Set User Permissions
        else
        {
            foreach($all_types as $k => $v)
            {
                $_list   = false;
                $_insert = false;
                $_update = false;
                $_delete = false;

                sys::array_key_default_value($permissions, $k, []);
                sys::specify_params($permissions[$k], ['list', 'insert', 'update', 'delete'], false);

                $permis[$k]           = [];
                $permis[$k]['list']   = false;
                $permis[$k]['insert'] = false;
                $permis[$k]['update'] = false;
                $permis[$k]['delete'] = false;

                if( $permissions[$k]['list']   === 'true' || $permissions[$k]['list']   === true ) $permis[$k]['list']   = true;
                if( $permissions[$k]['insert'] === 'true' || $permissions[$k]['insert'] === true ) $permis[$k]['insert'] = true;
                if( $permissions[$k]['update'] === 'true' || $permissions[$k]['update'] === true ) $permis[$k]['update'] = true;
                if( $permissions[$k]['delete'] === 'true' || $permissions[$k]['delete'] === true ) $permis[$k]['delete'] = true;
            }
        }

        return $permis;
    }


    public static function allowed($type = null, $action = null, $user_data = null, $user_by = 'id')
    {
        if($user_data == null) $user = user::info();
        else                   $user = self::get($user_data, $user_by);

        sys::array_key_default_value( $user                       , 'permissions' , []    );
        sys::array_key_default_value( $user['permissions']        ,  $type        , []    );
        sys::array_key_default_value( $user['permissions'][$type] ,  $action      , false );

        return $user['permissions'][$type][$action];
    }


    public static function modal($alias = null, $modal_id = null, $modal_class = null)
    {
        $allowed_alias = ['settings', 'login', 'register'];

        if( ! in_array($alias, $allowed_alias)) return false;
        if($modal_id    == null) $modal_id    = 'user_' . $alias . '_modal';
        if($modal_class == null) $modal_class = 'user_' . $alias . '_modal';

        $modal_file = "system/app/views/user/modal/$alias";

        $data          = [];
        $data['id']    = $modal_id;
        $data['class'] = $modal_class;

        $modal_content = cx::render($modal_file, $data);
        $modal_content = utils::compress_html($modal_content);

        return $modal_content;
    }


}


?>