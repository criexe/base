<?php


class sitemap
{

    public static function generate()
    {
        $all_types     = cx::type();
        $sitemap_types = [];

        foreach($all_types as $item_type)
        {
            sys::array_key_default_value($item_type, 'sitemap', false);

            if($item_type['sitemap'] === true)
            {
                $sitemap_types[] = $item_type['alias'];
            }
        }

        foreach($sitemap_types as $type)
        {
            $all = null;
            $url = null;

            $file         = SYSDATA_PATH . DS . 'sitemaps' . DS . $type . '.xml';
            $item_files[] = $type . '.xml';

            $contents = item::get_all(['type' => $type]);

            // Loop : All Datas
            foreach($contents as $content)
            {
                if( ! array_key_exists('full_url', $content)) continue;

                // Create Columns
                // Loop : Columns
                $url .= "
                <url>
                    <loc>{$content['full_url']}</loc>
                    <lastmod>" . date('c', $content['updated_at']['time']) . "</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>1</priority>
                </url>
                ";
            }

            $file_data =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?><urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"> $url </urlset>";

//            die($file_data);

            // Create File
            sys::write([

                'file' => $file,
                'data' => $file_data,
                'mode' => 'w'
            ]);
        }

        $sitemap_files = null;

        // Create Index
        foreach($sitemap_types as $index_type)
        {
            $sitemap_files .= "
            <sitemap>
                <loc>" . URL . "/sysdata/sitemaps/$index_type.xml</loc>
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