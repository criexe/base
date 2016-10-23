<?php
/**
 * user.php
 *
 * @author Mustafa Aydemir
 * @date   13.11.15
 */


class controller_user extends controller
{

    function __construct(){ parent::__construct(); }



    function login_form()
    {
        if( load::library('user:user')->logged_in() ) sys::location('/home');

        $this->set_title('Giriş');

        $this->view('/login', null, 'html');
    }


    function register_form()
    {
        if( load::library('user:user')->logged_in() ) sys::location('/home');

        $this->set_title('Kayıt Ol');

        $this->view('/register', null, 'html');
    }


    function login()
    {
        $r = [];
        try
        {
            $userLib = load::library('user:user');

            if(input::get('logout') == 'true') $userLib->logout();

            $user = input::post('user');
            $pass = input::post('pass');

            $r = $userLib->login($user, $pass);
        }
        catch(Exception $e)
        {
            $r['status']  = true;
            $r['message'] = $e->getMessage();
        }
        finally
        {
            echo json_encode($r);
        }
    }


    function register()
    {
        $r = [];
        try
        {
            $userLib = load::library('user:user');

            if(input::get('logout') == 'true') $userLib->logout();

            $user    = validator::input('username', 'user');
            $pass    = validator::input('password', 'pass');
            $email   = validator::input('email', 'email');
            $name    = validator::input('name', 'name');
            $surname = validator::input('name', 'surname');

            $r = $userLib->register($user, $pass, $email, $name, $surname);
        }
        catch(Exception $e)
        {
            $r['status']  = true;
            $r['message'] = $e->getMessage();
        }
        finally
        {
            echo json_encode($r);
        }
    }


    function logout()
    {
        load::library('user:user')->logout();
        sys::location('/');
    }



    // Settings
    function settings()
    {
        $userLib   = load::library('user:user');
        $userModel = load::model('user:user');

        if( ! $userLib->logged_in() ) sys::location('/home');

        $this->set_title('Settings');

        $tab  = input::get('tab', ['empty' => 'account']);

        $data = [
            'tab'       => $tab,
            'user_info' => $userModel->get([
                'where'   => 'id=' . $userLib->get_user_id(),
                'columns' => ['user', 'pass', 'email', 'name', 'surname']
            ])
        ];

        $this->view('settings', $data);
    }



    // Actions
    function update_account()
    {
        $r         = [];
        $user_data = [];

        $user_data['user']    = validator::input('username', 'username');
        $user_data['email']   = validator::input('email', 'email');
        $user_data['name']    = validator::input('name', 'name');
        $user_data['surname'] = validator::input('name', 'surname');

        $update = load::model('user:user')->update($user_data, ['id' => load::library('user:user')->get_user_id()]);

        if($update)
        {
            $r['status'] = true;
        }
        else
        {
            $r['status']  = false;
            $r['message'] = 'Error.';
        }

        echo json_encode($r);
    }


    function profile($username = null)
    {
        try
        {
            if($username == null) throw_exception('No username.');

            // Check Username Valid
            validator::check('username', $username);

            // User Models
            $user_model   = load::model('user:user');
            $follow_model = load::model('user:follow');

            // user Info
            $user_info = $user_model->get(['where' => "user='$username'"]);

            load::model('user:log')->add([
                'user_id'  => load::library('user:user')->get_user_id(),
                'activity' => 'view_profile',
                'to_id'    => $user_info['id']
            ]);

            $data = [

                'user_id'   => $user_info['id'],
                'username'  => $user_info['user'],
                'name'      => $user_info['name'],
                'surname'   => $user_info['surname'],
                'birthday'  => $user_info['birthday'],

                'cover_image'   => html::image($user_info['cover_image'], 1200, 0, [], true, true),

                'profile_image' => html::image($user_info['profile_image'], 100, 100, [], true, true),

                'follower_num'  => $follow_model->follower_num($user_info['id']),
                'following_num' => $follow_model->following_num($user_info['id']),

                'chars' => load::model('user:char')->get_chars($user_info['id'])
            ];


            $this->set_title($user_info['name'] . ' ' . $user_info['surname']);
            $this->view('profile', $data, 'profile');

            cache::create();
        }
        catch(Exception $e)
        {
            error::show_404();
        }
    }


    public function follow($id = null)
    {
        $model = load::model('user:follow');

        $r = $model->follow(null, (int)$id);

        echo json_encode($r);
    }


    public function get_info($username = null, $info = null)
    {
        try
        {
            if($username == null) return null;

            // Load User Model
            $user_model = load::model('user:user');

            if(!$user_model->is_user($username, 'user')) throw_exception('No user.');

            switch($info)
            {
                case 'id':       $column = 'id';       break;
                case 'name':     $column = 'name';     break;
                case 'surname':  $column = 'surname';  break;
                case 'birthday': $column = 'birthday'; break;
                case 'verified': $column = 'verified'; break;

                default: return false; break;
            }

            echo $user_model->get_info($column, '"' . $username . '"', 'user');
        }
        catch(Exception $e)
        {
            return false;
            logger::add( $e->getMessage(), 'profile' );
        }
    }


    public function settings_panel($alias = null)
    {
        try
        {
            sys::allowable_parameter_values($alias, ['account', 'password', 'profile']);
            if($alias == null) throw_exception('No alias');

            $user_lib   = load::library('user:user');
            $user_model = load::model('user:user');

            $user_id = $user_lib->get_user_id();

            switch($alias)
            {
                case 'account':

                    $data = [
                        'email'    => $user_model->get_info( 'email', $user_id ),
                        'name'     => $user_model->get_info( 'name', $user_id ),
                        'surname'  => $user_model->get_info( 'surname', $user_id ),
                        'username' => $user_model->get_info( 'user', $user_id )
                    ];
                    break;

                case 'password': $data = []; break;
                case 'profile': $data = []; break;
            }

            $this->view('settings/' . $alias, $data, false);
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    public function change_password()
    {
        try
        {
            $old    = validator::input('password', 'old-pass');
            $new    = validator::input('password', 'new-pass');
            $repeat = validator::input('password', 'new-repeat');

            $r = [];

            $user_model = load::model('user:user');
            $user_lib   = load::library('user:user');
            $user_id    = $user_lib->get_user_id();

            if($new != $repeat) throw_exception('Şifreler aynı değil.');
            if($user_model->get_info('pass', $user_id) != $old) throw_exception('Eski şifrenizi yanlış girdiniz.');

            $update_params = [

                'limit' => 1,
                'where' => "id=$user_id"
            ];

            if( $user_model->update(['pass' => $new], $update_params) )
            {
                $r['status'] = true;
                $r['message'] = 'Şifreniz değişti.';
            }
            else
            {
                throw_exception('Bir hata oluştu.');
                logger::add('[Error]' . $user_model->error(), 'db');
            }
        }
        catch(Exception $e)
        {
            $r['status'] = false;
            $r['message'] = $e->getMessage();
        }
        finally
        {
            echo json_encode($r);
        }
    }


    public function change_profile_image()
    {
        try
        {
            $image = validator::input('image', 'profile-image');

            if($image == false)
            {
                $r['status'] = false;
                $r['message'] = 'Bir hata oluştu. Daha sonra tekrar deneyin.';
            }
            else
            {
                $r['status']  = true;
                $r['message'] = 'Profil resmi başarıyla yüklendi.';
            }

            $m_user = load::model('user:user');

            $update = $m_user->update([

                'profile_image' => $image

            ],[

                'where' => "id = " . user::id(),
                'limit' => 1

            ]);

        }
        catch(Exception $e)
        {
            $r['status'] = false;
            $r['message'] = $e->getMessage();
        }
        finally
        {
            echo json_encode($r);
        }
    }


    public function add_char()
    {
        try
        {
            user::must_login();

            $value = validator::input('text', 'value');
            $to    = validator::input('user_id', 'to');
            $from  = user::id();

            $m_char = load::model('user:char');

            $add = $m_char->add_new($from, $to, $value) or throw_exception($m_char->error());

            echo 'true';
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    public function increase()
    {
        try
        {
            user::must_login();

            $id = input::get('id');
            $to = input::get('to');

            $m_char = load::model('user:char');

            $add = $m_char->increase($to, $id) or throw_exception($m_char->error());

            echo 'true';
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

}

?>