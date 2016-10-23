<?php
/**
 * user.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */

class model_page extends model
{

    public $name    = 'pages';
    public $display = 'Pages';


    public function __construct(){ parent::__construct(); }



    /**
     * ================================
     * === Standarts ==================
     * ================================
     */

    public function _db_columns()
    {
        return [

            'id' => [

                'display'    => '#',
                'validation' => 'number'
            ],
            'user_id' => [

                'display'    => 'User',
                'validation' => 'user_id'

            ],
            'title' => [

                'display'    => 'Title',
                'validation' => 'text'

            ],
            'description' => [

                'display'    => 'Description',
                'validation' => 'big_text'

            ],
            'keywords' => [

                'display'    => 'Keywords',
                'validation' => 'big_text'

            ],
            'content' => [

                'display'    => 'Content',
                'validation' => 'big_text'

            ],
            'layout' => [

                'display'    => 'Layout',
                'validation' => 'text'

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

}

?>