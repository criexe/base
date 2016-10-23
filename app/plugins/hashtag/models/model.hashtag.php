<?php
/**
 * model.hashtag.php
 *
 * @author Mustafa Aydemir
 * @date   18/01/16
 */


class model_hashtag extends model
{

    public $name    = 'hashtags';
    public $display = 'Hashtags';

    public function __construct(){ parent::__construct(); }



    public $hashtag_pattern = "/#([A-Za-z0-9öÖçÇşŞıİğĞ_]+)/i";



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

            'post_id' =>
                [
                    'display' => 'Post',
                    'validation' => 'number' // TODO: post_id validation oluşturulacak
                ],

            'creation_date' =>
                [
                    'display' => 'Date',
                    'validation' => 'date_time'
                ]

        ];
    }


    public function find($text = null)
    {
        try
        {
            if($text == null) return null;

            $r = [];

            if(preg_match_all($this->hashtag_pattern, $text, $matches))
            {
                foreach($matches[1] as $match)
                {
                    $r[] = $match;
                }
            }

            return $r;
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    public function put_links(&$text)
    {
        $hashtags = $this->find($text);

        if($text != null && count($hashtags) > 0)
        {
            foreach($hashtags as $h)
            {
                $text = preg_replace($this->hashtag_pattern, '<a href="/hashtag/$1" class="hashtag">#$1</a>', $text);
            }
        }
    }


    public function trend()
    {
        $params             = [];
        $params['limit']    = 1000;
        $params['order_by'] = 'COUNT(*) DESC';
        $params['group_by'] = 'hashtag';
        $params['columns']   = ['hashtag'];

        return $this->group($params);
    }

}

?>