<?php
/**
 * user.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */

class model_transaction extends model
{

    public $name    = 'transactions';
    public $display = 'Transactions';


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
            'transaction_id' => [

                'display'    => 'Transaction ID',
                'validation' => 'text'

            ],
            'user_id' => [

                'display'    => 'User',
                'validation' => 'user_id'

            ],
            'type' => [

                'display'    => 'Type',
                'validation' => 'text'

            ],
            'detail' => [

                'display'    => 'Detail',
                'validation' => 'big_text'

            ],
            'amount' => [

                'display'    => 'Amount',
                'validation' => 'number'

            ],
            'status' => [

                'display'    => 'Status',
                'validation' => 'transaction_status'

            ]

        ];
    }

}

?>