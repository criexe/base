<?php
/**
 * user.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */

class model_user extends model
{

    public $name    = 'users';
    public $display = 'Users';


    public function __construct(){ parent::__construct(); }



    /**
     * ================================
     * === Standarts ==================
     * ================================
     */

    public function _db_columns()
    {
        return [

            'id'    => [

                'display'    => '#',
                'validation' => 'number'

            ],
            'profile_image' => [

                'display'    => 'Profile Image',
                'validation' => 'image',
                'align'      => 'center',
                'options'    =>
                    [
                        'resize' =>

                            [
                                [60, null],
                                [80, null],
                                [120, null],
                                [150, null],
                                [180, null],
                                [200, null]
                            ],

                        'path' => 'user' . DS . 'profile_images'
                    ]
            ],
            'cover_image' => [

                'display'    => 'Cover Image',
                'validation' => 'image',
                'align'      => 'center',
                'options'    =>
                    [
                        'resize' =>

                            [
                                [500, null],
                                [1000, null],
                                [1500, null]
                            ],

                        'path' => 'user' . DS . 'cover_images'
                    ]
            ],
            'user'    => [

                'display'    => 'Username',
                'validation' => 'username'

            ],
            'pass'    => [

                'display'    => 'Password',
                'validation' => 'password'

            ],
            'email'   => [

                'display'    => 'Email',
                'validation' => 'email'

            ],
            'name'    => [

                'display'    => 'Name',
                'validation' => 'name'

            ],
            'surname' => [

                'display'    => 'Surname',
                'validation' => 'surname'

            ],
            'birthday' => [

                'display'    => 'Birthday',
                'validation' => 'datepicker'

            ],
            'verified' => [

                'display'    => 'Verified',
                'validation' => 'user_verified',
                'align'      => 'center'

            ],
            'authority' => [

                'display'    => 'Authority',
                'validation' => 'text',
                'align'      => 'center'

            ],
            'creation_date' => [

                'display'    => 'Creation Date',
                'validation' => 'date_time'
            ],
            'update_date' => [

                'display'    => 'Last Login',
                'validation' => 'date_time'
            ]

        ];
    }


    public function _list($params = [])
    {
        $r = [];

        $columns = $this->_db_columns();
        unset($columns['pass'], $columns['creation_date'], $columns['update_date']);

        $r['datas']   = $this->get_all( array_merge(['order_by' => 'id DESC'], $params) );
        $r['columns'] = $columns;

        return $r;
    }


    /**
     * @param array $user
     *
     * @return array
     */
    public function _insert($user = [])
    {
        try
        {
            validator::check('username', $user['user']);
            validator::check('password', $user['pass']);
            validator::check('email',    $user['email']);
            validator::check('name',     $user['name'],    ['required' => true]);
            validator::check('surname',  $user['surname'], ['required' => true]);

            $data = [];
            $r    = [];

            $data['user']     = "'{$user['user']}'";
            $data['pass']     = "'{$this->create_password($user['pass'])}'";
            $data['email']    = "'{$user['email']}'";
            $data['name']     = "'{$user['name']}'";
            $data['surname']  = "'{$user['surname']}'";
            $data['verified'] = "{$user['verified']}";

            $data['creation_date'] = time();
            $data['update_date']   = time();

            $check = $this->count_rows([
                'where' => "user={$data['user']} OR email={$data['email']}",
                'limit' => 1
            ]);

            if($check > 0) throw_exception('This user already exist.');

            $insert = $this->insert($data);

            if($insert)
            {
                $r['status']  = true;
            }
            else
            {
                $r['message'] = $this->error();
                $r['status']  = false;
            }
        }
        catch(Exception $e)
        {
            logger::add("[ERROR] [CAN'T REGISTER] {$e->getMessage()}", 'error');

            $r['message'] = $e->getMessage();
            $r['status']  = false;
        }
        finally
        {
            return $r;
        }
    }



    public function _update($user = [], $params = null)
    {
        try
        {
            validator::check('username', $user['user']);
            validator::check('password', $user['pass']);
            validator::check('email',    $user['email']);
            validator::check('name',     $user['name'],    ['required' => true]);
            validator::check('surname',  $user['surname'], ['required' => true]);
            validator::check('verified', $user['verified'], ['required' => true]);

            $data = [];
            $r    = [];

            $data['user']     = "'{$user['user']}'";
            $data['pass']     = "'{$this->create_password($user['pass'])}'";
            $data['email']    = "'{$user['email']}'";
            $data['name']     = "'{$user['name']}'";
            $data['surname']  = "'{$user['surname']}'";
            $data['verified'] = "{$user['verified']}";

            $data['creation_date'] = time();
            $data['update_date']   = time();

            $update = $this->update($data, $params);

            if($update)
            {
                $r['status']  = true;
            }
            else
            {
                $r['message'] = $this->error();
                $r['status']  = false;
            }
        }
        catch(Exception $e)
        {
            logger::add("[ERROR] [CAN'T UPDATE] {$e->getMessage()}", 'error');

            $r['message'] = $e->getMessage();
            $r['status']  = false;
        }
        finally
        {
            return $r;
        }
    }












    /**
     * @param null $pass
     *
     * @return null
     */
    public function create_password($pass = null)
    {
        if($pass != null) return $pass;
    }


    /**
     * @param null   $wanted_column
     * @param null   $user
     * @param string $by
     *
     * @return mixed
     */
    public function get_info($wanted_column = null, $user = null, $by = 'id')
    {
        $params = [];
        $params['where'] = "$by=$user";
        $params['columns'] = [$by, $wanted_column];

        return $this->get($params)[$wanted_column];
    }



    /**
     * @param null   $id
     * @param string $by
     *
     * @return mixed
     */
    public function get_username($user = null, $by = 'id')
    {
        return $this->get_info('user', $user, $by);
    }


    /**
     * @param null   $user
     * @param string $by
     *
     * @return mixed
     */
    public function get_profile_image($user = null, $by = 'id')
    {
        return $this->get_info('profile_image', $user, $by);
    }


    /**
     * @param null $user
     * @param null $pass
     *
     * @return bool
     */
    public function login($user = null, $pass = null)
    {
        if($user == null || $pass == null) return false;

        try
        {
            validator::check('username', $user);
            validator::check('password', $pass);

            $params = [];
            $r      = [];

            $params['columns'] = ['id', 'user', 'pass'];
            $params['where']   = "user='{$user}' AND pass='{$this->create_password($pass)}'";
            $params['limit']   = 1;

            $usr = $this->get($params);

            if(count($usr) > 0)
            {
                $r['status']  = true;
                $r['user_id'] = $usr['id'];

                $this->update(['update_date' => time()], ['where' => "user='$user'", 'limit' => 1]);
                logger::add("Logged In : {$user} : {$pass}", 'error');
            }
            else
            {
                logger::add("[ERROR] [CAN'T LOGIN] {$user} : {$pass}", 'error');

                $r['status']  = false;
                $r['message'] = 'Wrong email or password.';
            }
        }
        catch(Exception $e)
        {
            logger::add("[ERROR] [CAN'T LOGIN] {$e->getMessage()}", 'error');

            $r['status']  = false;
            $r['message'] = $e->getMessage();
        }
        finally
        {
            return $r;
        }
    }



    public function register($user = null, $pass = null, $email = null, $name = null, $surname = null)
    {
        if($user == null || $pass == null || $email == null || $name == null || $surname == null) return false;

        try
        {
            validator::check('username', $user);
            validator::check('password', $pass);
            validator::check('email', $email);
            validator::check('name', $name);
            validator::check('name', $surname);

            $params = [];
            $r      = [];

            $params['columns'] = ['id', 'user', 'pass'];
            $params['where']   = "user='{$user}' AND pass='{$this->create_password($pass)}'";
            $params['limit']   = 1;

            $usr = $this->get($params);

            if(count($usr) > 0)
            {
                $r['status']  = true;
                $r['user_id'] = $usr['id'];

                $this->update(['update_date' => time()], ['where' => "user='$user'", 'limit' => 1]);
                logger::add("Logged In : {$user} : {$pass}", 'error');
            }
            else
            {
                // Register
                $register = $this->insert([

                    'user' => $user,
                    'pass' => $pass,
                    'email' => $email,
                    'name' => $name,
                    'surname' => $surname,
                    'birthday' => null,
                    'verified' => null,
                    'creation_date' => time(),
                    'update_date' => time()
                ]);

                if($register)
                {
                    $r['status']  = true;
                    $r['user_id'] = $register;
                }
                else
                {
                    $r['status']  = false;
                    $r['user_id'] = 'Error';
                }
            }
        }
        catch(Exception $e)
        {
            logger::add("[ERROR] [CAN'T REGISTER] {$e->getMessage()}", 'error');

            $r['status']  = false;
            $r['message'] = $e->getMessage();
        }
        finally
        {
            return $r;
        }
    }








    /**
     * @param null $user_id
     *
     * @return null
     */
    public function get_password($user = null, $by = 'id')
    {
        if($user == null) return null;

        $pass = $this->get([
            'columns' => [$by, 'pass'],
            'where'   => "{$by}={$user}"
        ]);

        return $pass['pass'];
    }



    public function get_authority($user = null, $by = 'id')
    {
        $auth = $this->get([
            'columns' => [$by, 'authority'],
            'where'   => "{$by}={$user}"
        ]);

        switch($auth['authority'])
        {
            case 1:
                return 'admin';
            break;

            case null:
            case 0:
                return 'user';
            break;

            default:
                return 'visitor';
            break;
        }
    }


    /**
     * @param null $user_id
     *
     * @return null|string
     */
    public function create_cookie_key($user_id = null)
    {
        if($user_id == null) return null;

        $ip         = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $cookie_key = "..::..||$user_id||$ip||$user_agent||=||::.." . sys::get_config('security')['user_cookie_key'];
        $key        = sha1(md5(md5($cookie_key)));
        $r          = $user_id . '-' . $key;

        return $r;
    }



    /**
     * @param null   $user
     * @param string $by
     *
     * @return bool
     */
    public function is_user($user = null, $by = 'id')
    {
        $params          = [];
        $params['limit'] = 1;
        $params['where'] = "`$by`='$user'";

        $count = $this->count_rows($params);

        if($count > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }






    public function get_random_anon_image()
    {
        $images = ['mask1.png', 'mask2.png', 'mask3.png', 'man1.png', 'man2.png', 'man3.png', 'man4.png', 'man5.png', 'man6.png'];
        return '/user/profile_images/anon/' . $images[array_rand($images)];
    }

    public function get_random_anon_name()
    {
        $names = ['Ziyaretçi', 'Gizlenen İnsan', 'Gölge', 'Görünmez', 'Anonim'];
        return '<i class="fa fa-times"></i>' . $names[array_rand($names)];
    }

}

?>