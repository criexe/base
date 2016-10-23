<?php

class model_like extends model
{

    public $name    = 'likes';
    public $display = 'Likes';


    public function __construct(){ parent::__construct(); }


    public function _db_columns()
    {
        return [

            'id'    => [

                'display'    => '#',
                'validation' => 'number'

            ],

            'table_name'    => [

                'display'    => 'Table',
                'validation' => 'text'

            ],
            'row_id'    => [

                'display'    => 'Row ID',
                'validation' => 'number'

            ],
            'user_id' => [

                'display'    => 'User',
                'validation' => 'user_id'
            ],

            'created_at' => [

                'display' => 'Created At',
                'validation' => 'date_time'
            ]
        ];
    }


    function toggle($table = null, $row = null, $user = null)
    {
        try
        {
            if(!user::logged_in()) return false;

            $user = $user == null ? user::id() : $user;

            $count = $this->count_rows([

                'where' => "table_name = '$table' AND row_id = $row AND user_id = $user",
                'limit' => 1
            ]);

            if($count <= 0)
            {
                $insert = $this->insert([

                    'table_name' => $table,
                    'row_id' => $row,
                    'user_id' => $user,
                    'created_at' => time()
                ]);

                load::model('user:log')->add([

                    'user_id'  => $user,
                    'post_id'  => $row,
                    'activity' => 'liked'
                ]);

                return 'liked';
            }
            else
            {
                $this->delete([

                    'where' => "table_name = '$table' AND row_id = $row AND user_id = $user",
                    'limit' => 1
                ]);

                load::model('user:log')->add([

                    'user_id'  => $user,
                    'post_id'  => $row,
                    'activity' => 'disliked'
                ]);

                return 'disliked';
            }

            return true;

        }
        catch(Exception $e)
        {
            return false;
        }
    }

    function is_liked($table = null, $row = null, $user = null)
    {
        try
        {
            if(!user::logged_in()) return false;

            $user = $user == null ? user::id() : $user;

            $count = $this->count_rows([

                'where' => "table_name = '$table' AND row_id = $row AND user_id = $user",
                'limit' => 1
            ]);

            if($count > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    function count($table = null, $row = null)
    {
        try
        {
            $count = $this->count_rows([

                'where' => "table_name = '$table' AND row_id = $row",
                'limit' => 1
            ]);

            if($count) return (int)$count;
            else return 0;
        }
        catch(Exception $e)
        {
            return 0;
        }
    }


    function likers($table = null, $row = null)
    {
        try
        {
            $likers = $this->get_all([

                'where' => "table_name = '$table' AND row_id = $row",
            ]);

            return $likers;

        }
        catch(Exception $e)
        {
            return false;
        }
    }


}

?>