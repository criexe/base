<?php
/**
 * lib.user.php
 *
 * @author Mustafa Aydemir
 * @date   6.11.15
 */


class lib_user
{

    public function login($username = null, $password = null)
    {
        $login = load::model('user:user')->login($username, $password);
        $r     = [];

        if( $login['status'] == true )
        {
            cookie::set('user', load::model('user:user')->create_cookie_key($login['user_id']));

            $r['status'] = true;
        }
        else
        {
            $r['status']  = false;
            $r['message'] = $login['message'];
        }

        return $r;
    }


    public function register($username = null, $password = null, $email, $name, $surname)
    {
        $register = load::model('user:user')->register($username, $password, $email, $name, $surname);
        $r        = [];

        if( $register['status'] == true )
        {
            cookie::set('user', load::model('user:user')->create_cookie_key($register['user_id']));

            $r['status'] = true;
        }
        else
        {
            $r['status']  = false;
            $r['message'] = $register['message'];
        }

        return $r;
    }


    public function logout()
    {
        session::delete('userid');
        session::delete('username');
        session::delete('user_authority');

        cookie::delete('user');
    }


    public function logged_in()
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


    public function must_login($location = '/')
    {
        if( !$this->logged_in() )
        {
            sys::location($location);
            exit;
        }
    }



    // Set Methods =================

    public function set_user_id($id = null)
    {
        session::set('userid', $id);
        return $id;
    }

    public function set_username($username = null)
    {
        session::set('username', $username);
        return $username;
    }


    public function set_authority($auth = null)
    {
        session::set('user_authority', $auth);
        return $auth;
    }



    // Get Methods =================

    public function get_user_id()
    {
        return session::get('userid');
    }

    public function get_username()
    {
        return session::get('username');
    }

    public function get_authority()
    {
        return session::get('user_authority');
    }

    public function get_profile_image()
    {
        $userModel= load::model('user::user');
        return validator::display(
            'image',
            $userModel->get_profile_image( $this->get_user_id() ),
            [
                'size' => '120x',
                'link' => true
            ]
        );
    }

    public function get_name_surname()
    {
        $userModel = load::model('user::user');

        $name    = $userModel->get_info('name', $this->get_user_id());
        $surname = $userModel->get_info('surname', $this->get_user_id());

        return $name . ' ' . $surname;
    }

}

?>