<?php

class model_comment extends model
{

    public $name    = 'comments';
    public $display = 'Comments';


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

            'comment' => [

                'display'    => 'Comment',
                'validation' => 'big_text'
            ],

            'created_at' => [

                'display' => 'Created At',
                'validation' => 'date_time'
            ]
        ];
    }


    function comments($table = null, $row = null)
    {
        try
        {
            $comments = $this->get_all([

                'where' => "table_name = '$table' AND row_id = $row",
                'limit' => 1
            ]);

            if($comments)
                return html::render('user:comments', ['comments' => $comments]);
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


}

?>