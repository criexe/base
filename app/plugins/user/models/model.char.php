<?php

class model_char extends model
{

    public $name    = 'user_characters';
    public $display = 'User Characters';


    public function __construct(){ parent::__construct(); }


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
            'value'    => [

                'display'    => 'Character',
                'validation' => 'text'

            ],
            'count'    => [

                'display'    => 'Count',
                'validation' => 'number'

            ]

        ];
    }

    function get_chars($profile_id = null)
    {
        if($profile_id == null) return false;

        $chars = $this->group([

            'where'    => "to_id = $profile_id",
            'limit'    => 10,
            'group_by' => 'value',
            'order_by' => 'COUNT(*) DESC'
        ]);

        return $chars;
    }


    function add_new($from = null, $to = null, $value = null)
    {
        if(!user::logged_in()) return false;
        if($from == null || $to == null || $value == null) return false;

        $get = $this->get([

            'where' => "from_id = $from AND to_id = $to AND value = '$value'",
        ]);

        if($get) return true;

        $add = $this->insert([

            'from_id' => user::id(),
            'to_id'   => $to,
            'value'   => $value
        ]);

        if($add) return true; else return false;
    }


    function increase($to = null, $id = null)
    {
        if(!user::logged_in()) return false;

        $get = $this->get([

            'where' => "id = $id"
        ]);

        if($this->add_new(user::id(), $to, $get['value'])) return true; else return false;
    }


}

?>