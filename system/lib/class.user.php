<?php


class user
{

    public static function login($username = null, $password = null)
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



    // Set Methods =================

    public static function set_user_id($id = null)
    {
        session::set('userid', $id);
        return $id;
    }

    public static function set_username($username = null)
    {
        session::set('username', $username);
        return $username;
    }


    public static function set_authority($auth = null)
    {
        session::set('user_authority', $auth);
        return $auth;
    }



    // Get Methods =================

    public static function id()
    {
        return session::get('userid');
    }

    public static function username()
    {
        return session::get('username');
    }

    public static function authority()
    {
        return session::get('user_authority');
    }

    public static function profile_image($id = null)
    {
        if($id == null)
        {
            return load::model('user:user')->get_random_anon_image();
        }
        else
        {
            $userModel = load::model('user::user');
            return validator::display(
                'image',
                $userModel->get_profile_image( $id ),
                [
                    'size' => '60x',
                    'link' => true
                ]
            );
        }
    }

    public static function name_surname($id = null)
    {
        if($id == null)
        {
            return load::model('user:user')->get_random_anon_name();
        }
        else
        {
            $userModel = load::model('user::user');

            $name    = $userModel->get_info('name', $id);
            $surname = $userModel->get_info('surname', $id);

            return $name . ' ' . $surname;
        }
    }


    public static function print_image($src = null, $name = false, $width = 30, $height = 30, $show_name = true)
    {
        $name = strip_tags($name);

        $html_name    = $name && $show_name ? "<i class='fa fa-caret-left'></i><span class='user-name'>$name</span>" : null;
        $first_letter = $name ? strtoupper($name[0]) : 'X';
        $html_letter  = $name && $src == null ? "<span class='user-letter'>{$first_letter}</span>" : null;

        $letters = [

            'a' => 'bgc-E7B91B',
            'b' => 'bgc-E7B91B',
            'c' => 'bgc-E7B91B',
            'ç' => 'bgc-E7B91B',
            'd' => 'bgc-E7B91B',
            'e' => 'bgc-855085',
            'f' => 'bgc-855085',
            'g' => 'bgc-855085',
            'ğ' => 'bgc-855085',
            'h' => 'bgc-855085',
            'ı' => 'bgc-855085',
            'i' => 'bgc-A9C737',
            'j' => 'bgc-A9C737',
            'k' => 'bgc-A9C737',
            'l' => 'bgc-A9C737',
            'm' => 'bgc-3498db',
            'n' => 'bgc-A9C737',
            'o' => 'bgc-A9C737',
            'ö' => 'bgc-A9C737',
            'p' => 'bgc-3498db',
            'r' => 'bgc-3498db',
            's' => 'bgc-3498db',
            'ş' => 'bgc-3498db',
            't' => 'bgc-3498db',
            'u' => 'bgc-3498db',
            'ü' => 'bgc-3498db',
            'v' => 'bgc-3498db',
            'y' => 'bgc-e74c3c',
            'z' => 'bgc-e74c3c',
            'w' => 'bgc-e74c3c',
            'q' => 'bgc-e74c3c',
            'x' => 'bgc-e74c3c'
        ];

        $bg     = ["E7B91B", "855085", "A9C737", "3498db", "e74c3c"];

        $css_width  = $width  > 0 ? 'width:'  . $width  . 'px;' : null;
        $css_height = $height > 0 ? 'height:' . $height . 'px;' : null;

        $src = html::image($src, $width, $height, [], true, true);

        $html = "
        <a href='#' class='user-img " . $letters[strtolower($first_letter)] . "' style='background-image:url($src);$css_width $css_height'>
            <span class='center-box'>
                <span class='center-content'>
                    $html_name
                    $html_letter
                </span>
            </span>
        </a>
        ";

        return $html;
    }


    public static function id_to_avatar($id = null, $width = 30, $height = 30, $show_name = true)
    {
        if($id == null) $id = self::id();

        $m_user = load::model('user:user');

        $user = $m_user->get([

            'where'   => "id = $id",
            'columns' => ['id', 'user', 'profile_image', 'name', 'surname']
        ]);

        if(count($user) <= 0) return false;

        return self::print_image($user['profile_image'], $user['name'] . ' ' . $user['surname'], $width, $height, $show_name);
    }

    public static function get($id = null)
    {
        if($id == null) $id = self::id();

        return load::model('user:user')->get(['where' => "id=$id"]);
    }

}


?>