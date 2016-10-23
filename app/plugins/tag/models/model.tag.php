<?php

class model_tag extends model
{

    public $name    = 'tags';
    public $display = 'Tag';

    public function __construct(){ parent::__construct(); }


    public function _db_columns()
    {
        return [

            'id' =>
                [
                    'display' => '#',
                    'validation' => 'number'
                ],


            'row_id' =>
                [
                    'display' => 'Row ID',
                    'validation' => 'number'
                ],

            'table' =>
                [
                    'display' => 'Table Name',
                    'validation' => 'text'
                ],

            'tag' =>
                [
                    'display' => 'Tag',
                    'validation' => 'text'
                ]

        ];
    }


    function create_tag($tag = null, $table = null, $id = null)
    {
        try
        {
            if($tag == null || $table == null || $id == null) throw_exception('No parameters.');

            $insert_data = [

                'tag'    => strip_tags($tag),
                'table'  => $table,
                'row_id' => $id
            ];

            $this->insert($insert_data) or throw_exception($this->error());

            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    function create_multi_tag($tags = null, $table = null, $id = null)
    {
        try
        {
            if(is_array($tags))
            {
                $_tags = $tags;
            }
            else if(is_string($tags))
            {
                $_tags = explode(',', $tags);
            }

            foreach($_tags as $tag) $this->create_tag($tag, $table, $id) or throw_exception();

        }
        catch(Exception $e)
        {
            return false;
        }
    }

    function get_tags($table = null, $id = null)
    {
        $tags = $this->get_all([

            'where'    => "row_id = $id AND `table` = '$table'",
            'limit'    => 10,
            'order_by' => 'RAND()'
        ]);

        return $tags;
    }

}

?>