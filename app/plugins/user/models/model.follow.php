<?php
/**
 * user.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */

class model_follow extends model
{

    public $name    = 'follow';
    public $display = 'Follow';


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

            'from_id'    => [

                'display'    => 'From',
                'validation' => 'user_id'

            ],
            'to_id'    => [

                'display'    => 'To',
                'validation' => 'user_id'

            ],
            'followed_date' => [

                'display'    => 'Followed Date',
                'validation' => 'date_time'
            ]
        ];
    }



    public function follow($from = null, $to = null)
    {
        try
        {
            if(!is_int($to)) throw_exception('[To] Parameter Error.');

            $user_lib   = load::library('user:user');
            $user_model = load::model('user:user');
            $r          = [];

            if(!is_int($from))
            {
                if(!$user_lib->logged_in()) throw_exception('Must login.');

                $from = $user_lib->get_user_id();
            }

            // Is User ?
            if(!$user_model->is_user($from)) throw_exception('Invalid follower.');
            if(!$user_model->is_user($to))   throw_exception('Invalid following.');

            // Is Followed
            $is_followed = $this->count_rows([
                    'where' => "from_id=$from AND to_id=$to",
                    'limit' => 1
                ]);

            if($is_followed > 0)
            {
                // Unfollow
                $action = $this->delete([

                    'where' => "from_id=$from AND to_id=$to",
                    'limit' => 1
                ]);

                $r['action'] = 'unfollowed';
            }
            else
            {
                // Follow
                $action = $this->insert([

                    'from_id'       => $from,
                    'to_id'         => $to,
                    'followed_date' => time()
                ]);

                $r['action'] = 'followed';
            }

            // Status
            if($action != false) $r['status'] = true;
            else                 $r['status'] = false;

            // Return
            return $r;
        }
        catch(Exception $e)
        {
            return [
                'status'  => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function follower_num($id = null)
    {
        if($id == null) return null;
        if(!load::model('user:user')->is_user($id)) return false;

        $num = $this->count_rows([
            'where' => "to_id=$id"
        ]);

        return (int)$num;
    }


    public function following_num($id = null)
    {
        if($id == null) return null;
        if(!load::model('user:user')->is_user($id)) return false;

        $num = $this->count_rows([
            'where' => "from_id=$id"
        ]);

        return (int)$num;
    }



    public function is_followed($id = null)
    {
        if($id == null) return null;
        if(!load::model('user:user')->is_user($id)) return false;

        $my_id = load::library('user:user')->get_user_id();

        $num = $this->count_rows([
            'where' => "from_id=$my_id AND to_id=$id"
        ]);

        if($num > 0) return true; else return false;
    }



    public function follow_button($id = null)
    {
        if($id == null) return null;

        $btn = $this->is_followed($id) ? 'unfollow' : 'follow';
        $msg = $this->is_followed($id) ? 'Takibi Bırak' : 'Takip Et';

        return '
        <a href="#" data-user="' . $id . '" class="btn btn-follow ' . $btn . '" data-toggle="tooltip" data-placement="bottom" title="' . $msg . '">
            <i class="fa fa-check"></i>
            <i class="fa fa-times"></i>
        </a>
        ';
    }


    public function following_list($id = null)
    {
        if($id == null)
        {
            $id = load::library('user:user')->get_user_id();
        }

        $params = [
            'where' => "from_id=$id"
        ];

        $list = $this->get_all($params);
        $r    = [];

        foreach($list as $l) $r[] = $l['to_id'];

        return $r;
    }


}

?>