<?php
/**
 * model.image.php
 *
 * @author Mustafa Aydemir
 * @date   31/12/15
 */

class model_image extends model
{

    public $name    = 'images';
    public $display = 'Images';


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

            'image' =>
                [
                    'display' => 'Image',
                    'validation' => 'image',
                    'align'      => 'center'
                ],

            'alt' =>
                [
                    'display' => 'Alt',
                    'validation' => 'text'
                ],

            'title' =>
                [
                    'display' => 'Title',
                    'validation' => 'text'
                ],

            'description' =>
                [
                    'display' => 'Description',
                    'validation' => 'big_text'
                ]

        ];
    }

}

?>