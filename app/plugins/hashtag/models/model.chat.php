<?php
/**
 * model.hashtag.php
 *
 * @author Mustafa Aydemir
 * @date   18/01/16
 */


class model_chat extends model
{

    public $name    = 'hashtag_chat';
    public $display = 'Hashtag Chat';

    public function __construct(){ parent::__construct(); }


    public function _db_columns()
    {
        return [

            'id' =>
                [
                    'display' => '#',
                    'validation' => 'number'
                ],


            'user_id' =>
                [
                    'display' => 'User',
                    'validation' => 'user_id'
                ],

            'hashtag' =>
                [
                    'display' => 'Hashtag',
                    'validation' => 'number'
                ],

            'message' =>
                [
                    'display' => 'Message',
                    'validation' => 'big_text'
                ],

            'creation_date' =>
                [
                    'display' => 'Date',
                    'validation' => 'date_time'
                ]

        ];
    }

}

?>