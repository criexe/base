<?php
/**
 * model.log.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */

class model_log extends model
{

    public $name    = 'user_logs';
    public $display = 'User Logs';


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

            'user_id'    => [

                'display'    => 'From',
                'validation' => 'user_id'

            ],
            'post_id'    => [

                'display'    => 'Post ID',
                'validation' => 'number'

            ],
            'followed_date' => [

                'display'    => 'Followed Date',
                'validation' => 'date_time'
            ]
        ];
    }



    public function list_item($params = [])
    {
        sys::specify_params($params, ['id', 'user_id', 'post_id', 'activity', 'creation_date']);

        $dict = [

            'registered'        => 'Üye oldu.',
            'login'             => 'Giriş yaptı.',
            'share_text'        => 'Yazı paylaştı.',
            'share_image'       => 'Resim paylaştı.',
            'followed'          => 'Takip etti.',
            'liked'             => 'Bir gönderiyi beğendi.',
            'disliked'          => 'Bir gönderiyi beğenmekten vazgeçti.',

            'view_profile'      => 'Bir profili görüntüledi.',
            'self_view_profile' => 'Kendi profilini görüntüledi.',

            'dictionary.new_title' => 'Yeni bir sözlük başlığı açtı.',
            'dictionary.add_post'  => 'Sözlüğe içerik paylaştı.'
        ];

        if($params['user_id'] == null) return null;

        $user_model = load::model('user:user');



        // User Image
        $user_img = $params['user_id'] == null ?

            $user_model->get_random_anon_image() :
            validator::display('image', $user_model->get_profile_image($params['user_id']), ['link' => true]);


        // Name Surname
        if($params['user_id'] == null)
        {
            $name_surname = 'Anonim';
        }
        else
        {
            $get_name = $user_model->get([

                'columns' => ['user', 'name', 'surname', 'id'],
                'where' => "id={$params['user_id']}"
            ]);

            $name_surname = $get_name['name'] . ' ' . $get_name['surname'];
        }


        if($params['user_id'] == $params['to_id'] && $params['activity'] == 'view_profile' )
        {
            $params['activity'] = 'self_view_profile';
        }

        $message = $dict[$params['activity']];

        if($message == null) return null;

        $line = '
        <div class="userline">
            <div class="line-left">

                ' . user::print_image($user_img, $name_surname, 35, 35) . '
            </div>
            <div class="line-right">
                <span class="namesurname">' . $name_surname . '</span>,
                <span class="action">' . $message . '</span>
            </div>
        </div>
        ';

        return $line;
    }


    public function get_logs($users = null)
    {
        $params = [];

        $follow_model = load::model('user:follow');

        // TODO :  Düzeltilecek

//        if($users != null)
//        {
//            $list = $follow_model->following_list();
//            $in   = implode(', ', $list);
//        }
//        else
//        {
//            $in = load::library('user:user')->get_user_id();
//        }

        // Get Logs
        $logs = $this->get_all([

            //'where'    => "user_id IN ($in)",
            //'limit'    => 20,
            'order_by' => 'id DESC',
        ]);

        return $logs;
    }


    public function add($params = [])
    {
        if(!array_key_exists('creation_date', $params))
            $params['creation_date'] = time();

        return $this->insert($params);
    }


    public function create_list($users = null)
    {
        $logs = $this->get_logs($users);
        $temp = [];
        $r    = null;

        sys::specify_params($temp, ['user_id', 'to_id', 'activity', 'post_id']);

        foreach($logs as $l)
        {
            if(
                $l['user_id'] == $temp['user_id']   &&
                $l['to_id'] == $temp['to_id']       &&
                $l['activity'] == $temp['activity'] &&
                $l['post_id'] == $temp['post_id']) continue;

            $temp = $l;
            $r   .= $this->list_item($l);
        }

        return $r;
    }

}

?>