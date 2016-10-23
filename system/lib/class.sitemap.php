<?php


class sitemap
{

    public static $maps = [];


    public static function set(array $params = [])
    {
        sys::array_key_default_value($params, 'table', false);
        sys::array_key_default_value($params, 'url', false);

        sys::array_key_default_value($params, 'priority', 1);
        sys::array_key_default_value($params, 'changefreq', 'daily');
        sys::array_key_default_value($params, 'lastmod', 'updated_at');

        if($params['table'] || $params['url'])
        {
            self::$maps[] = $params;
        }
    }


    public static function generate()
    {
        $map_files = [];

        foreach(self::$maps as $map)
        {
            $all = null;
            $url = null;

            $file        = SYSDATA_PATH . DS . 'sitemaps' . DS . $map['table'] . '.xml';
            $map_files[] = $map['table'] . '.xml';

            $contents = db::get_all(sys::get_config('database')['prefix'] . $map['table']);

            // Loop : All Datas
            foreach($contents as $content)
            {
                $content_url = $map['url'];
                // Create Columns
                // Loop : Columns
                foreach($content as $key => $value)
                {
                    $content_url = str_replace('{' . $key . '}', $content[$key], $content_url);
                }
                $content_url = trim($content_url, '/');

                $url .= "
                <url>
                    <loc>" . URL . "/$content_url</loc>
                    <lastmod>" . date('c', $content[$map['lastmod']]) . "</lastmod>
                    <changefreq>{$map['changefreq']}</changefreq>
                    <priority>{$map['priority']}</priority>
                </url>
                ";
            }

            $file_data =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?><urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"> $url </urlset>";

            // Create File
            sys::write([

                'file' => $file,
                'data' => $file_data,
                'mode' => 'w'
            ]);
        }

        $sitemap_files = null;

        // Create Index
        foreach($map_files as $item)
        {
            $sitemap_files .= "
            <sitemap>
                <loc>" . URL . "/sysdata/sitemaps/$item</loc>
                <lastmod>" . date('c') . "</lastmod>
            </sitemap>
            ";
        }

        $index_data = "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"> $sitemap_files </sitemapindex>";

        sys::write([

            'file' => SYSDATA_PATH . DS . 'sitemaps' . DS . 'index.xml',
            'data' => $index_data,
            'mode' => 'w'
        ]);

        self::send();
    }


    public static function send()
    {
        $url = 'http://google.com/ping?sitemap=' . URL . '/sysdata/sitemaps/index.xml';

        $send = net::connect([

            'url' => $url
        ]);
    }


}


?>