<?php
/**
 * class.filter.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */


class filter
{

    public static function request ($input = null, $level = 'normal')
    {
        global $db;
        $allowed_levels = array('no', 'low', 'normal', 'high');

        if(is_array($input)) return $input;

        if(!in_array($level, $allowed_levels))
        {
            throw_exception('Not allowed : ' . $level);
        }
        else
        {
            if($level == 'no')
            {
                $_output = $input;
            }
            else
            {
                if(is_a($db, 'Database') && $db->db != false)
                    $nwinpt = $db->escape_string($input);
                else
                    $nwinpt = $input;

                // Security Levels
                if($level == 'low')
                {
                    $_output = $nwinpt;
                }
                else if($level == 'normal')
                {
                    $_output = htmlspecialchars($nwinpt, ENT_NOQUOTES | ENT_QUOTES);
                }
                else if($level == 'high')
                {
                    $_output = htmlspecialchars(strip_tags($nwinpt), ENT_NOQUOTES | ENT_QUOTES);
                }
                else
                {
                    throw_exception('Undefined parameter for request filter.');
                }

            }

            return $_output;
        }
    }


//    public static function slugify($text = null)
//    {
//        // replace non letter or digits by -
//        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
//        // trim
//        $text = trim($text, '-');
//        // transliterate
//        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
//        // lowercase
//        $text = strtolower($text);
//        // remove unwanted characters
//        $text = preg_replace('~[^-\w]+~', '', $text);
//        if (empty($text))
//        {
//            return 'n-a';
//        }
//        return $text;
//    }


    public static function slugify($str, $options = array())
    {
        $str      = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
        $defaults = [

            'delimiter'     => '-',
            'limit'         => null,
            'lowercase'     => true,
            'replacements'  => array(),
            'transliterate' => true
        ];
        $options = array_merge($defaults, $options);

        $char_map = [
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',
            // Latin symbols
            '©' => '(c)',
            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',
            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',
            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',
            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        ];

        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

        if ($options['transliterate']) $str = str_replace(array_keys($char_map), $char_map, $str);

        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }
//
//
//    public static function slugify($str, $replace = [], $delimiter = '-')
//    {
//        if(!empty($replace))
//        {
//            $str = str_replace((array)$replace, ' ', $str);
//        }
//
//        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
//        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
//        $clean = strtolower(trim($clean, '-'));
//        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
//
//        return $clean;
//    }


    public static function content($content = null)
    {
        if($content == null) return null;

        $content = htmlspecialchars_decode($content);
        $content = strip_tags($content, '<p><a><b><strong><u><i><ul><li><ol><table><tbody><thead><tr><td><strike><br><sup><sub><img>');

        $content = self::convert_bbcodes($content);

        return $content;
    }


    public static function convert_links($content = null, $convert_images = true, $params = [])
    {
        if($content == null) return null;

        $uri_pattern = "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/si";

        $all_links = preg_match_all($uri_pattern, $content, $links);

        foreach($links[1] as $link)
        {
            $convert_images = false;
            if($convert_images == true)
            {
                $image_types  = ['image/png', 'image/jpeg', 'image/pjpeg', 'image/gif'];

                if(sys::map_exist($link))
                {
                    $content_type = sys::get_map($link)['mime'];
                }
                else
                {
                    @$content_type = get_headers($link, 1)["Content-Type"];
                    logger::add('Getting Header : ' . $link);

                    sys::create_map_file(

                        $link, [

                            'type'     => 'url',
                            'mime'     => $content_type,
                            'original' => $link
                        ]
                    );
                }

                if(in_array($content_type, $image_types))
                {

                    $content = str_replace($link, html::image($link, 0, 400), $content);
                    if(array_key_exists('if_image', $params)) $params['if_image']($link, $content_type);
                }
                else
                {
                    $content = str_replace($link, '<a target="_blank" href="' . _config('app.url') . '/location?url=' . urlencode($link) . '" title="' . $link . '">' . $link . '</a>', $content);

                }
            }
            else
            {
                $content = str_replace($link, '<a target="_blank" href="' . _config('app.url') . '/location?url=' . urlencode($link) . '" title="' . $link . '">' . $link . '</a>', $content);
            }

            //$content = preg_replace($uri_pattern, '<a target="_blank" href="' . sys::get_config('application')['url']. '/location?url=$1" title="$1">$1</a>', $content);
        }

        return $content;
    }

    private static function convert_bbcodes($content = null)
    {
        if($content == null) return null;

        $patterns = [

            "#\[quote\](.*?)\[/quote\]#si"       => '<blockquote><p>$1</p></blockquote>',
            "#\[quote=(.*?)\](.*?)\[/quote\]#si" => '<blockquote><p>$2</p><footer>$1</footer></blockquote>',
            "#\n---\n#i"                         => "\n<hr>\n",

        ];

        foreach($patterns as $k => $v)
        {
            $content = preg_replace($k, $v, $content);
        }

        return $content;
    }

}

?>