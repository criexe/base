<?php


class asset
{

    public static $version = 'v1';

    public static $css_files    = [];
    public static $css_filename = 'style.css';

    public static $js_header_files    = [];
    public static $js_header_filename = 'script.header.js';

    public static $js_footer_files    = [];
    public static $js_footer_filename = 'script.footer.js';


    public static function is_active()
    {
        return sys::get_config('application')['minify_assets'];
    }

    public static function css($filename = null)
    {
        if($filename == null) return null;

        self::$css_files = array_merge(self::$css_files, [$filename]);
    }

    public static function js_header($filename = null)
    {
        if($filename == null) return null;

        self::$js_header_files = array_merge(self::$js_header_files, [$filename]);
    }

    public static function js_footer($filename = null)
    {
        if($filename == null) return null;

        self::$js_footer_files = array_merge(self::$js_footer_files, [$filename]);
    }


    public static function minify_css()
    {
        $css = self::$css_files;

        require_once SYSTEM_LIB_PATH . '/Minify/Minify.php';
        require_once SYSTEM_LIB_PATH . '/Minify/CSS.php';
        require_once SYSTEM_LIB_PATH . '/Minify/JS.php';
        require_once SYSTEM_LIB_PATH . '/Minify/Exception.php';
        require_once SYSTEM_LIB_PATH . '/Minify/Converter.php';

        $minifier = new MatthiasMullie\Minify\CSS();

        $minifier->setMaxImportSize(10);

        $minifier->setImportExtensions([

            'gif' => 'data:image/gif',
            'png' => 'data:image/png',
            'svg' => 'data:image/svg+xml',
        ]);

        if(is_array($css))
        {
            foreach($css as $source)
            {
                $minifier->add(self::get($source));
            }
        }
        else
        {
            $minifier->add(self::get($css));
        }

        return $minifier->minify();
        //return $minifier->gzip();
    }


    public static function minify_js_header()
    {
        $js = self::$js_header_files;

        require_once SYSTEM_LIB_PATH . '/Minify/Minify.php';
        require_once SYSTEM_LIB_PATH . '/Minify/CSS.php';
        require_once SYSTEM_LIB_PATH . '/Minify/JS.php';
        require_once SYSTEM_LIB_PATH . '/Minify/Exception.php';
        require_once SYSTEM_LIB_PATH . '/Minify/Converter.php';

        $minifier = new MatthiasMullie\Minify\JS();

        foreach($js as $source)
        {
            $minifier->add(self::get($source));
        }

        return $minifier->minify();
        //return $minifier->gzip();
    }


    public static function minify_js_footer()
    {
        $js = self::$js_footer_files;

        require_once SYSTEM_LIB_PATH . '/Minify/Minify.php';
        require_once SYSTEM_LIB_PATH . '/Minify/CSS.php';
        require_once SYSTEM_LIB_PATH . '/Minify/JS.php';
        require_once SYSTEM_LIB_PATH . '/Minify/Exception.php';
        require_once SYSTEM_LIB_PATH . '/Minify/Converter.php';

        $minifier = new MatthiasMullie\Minify\JS();

        foreach($js as $source)
        {
            $minifier->add(self::get($source));
        }

        return $minifier->minify();
        //return $minifier->gzip();
    }


    public static function get($file = null)
    {
        if($file == null) return null;

        return file_get_contents($file);
    }


    public static function save()
    {
        if( ! self::is_active()) return false;

        // Css
        $css_file = ASSETS_SYS_PATH . DS . self::$version . DS . self::$css_filename;

        if(!file_exists($css_file))
        {
            sys::write([

                'file' => $css_file,
                'data' => self::minify_css()
            ]);
        }


        // JS Header
        $js_header_file = ASSETS_SYS_PATH . DS . self::$version . DS . self::$js_header_filename;

        if(!file_exists($js_header_file))
        {
            sys::write([

                'file' => $js_header_file,
                'data' => self::minify_js_header()
            ]);
        }


        // JS Footer
        $js_footer_file = ASSETS_SYS_PATH . DS . self::$version . DS . self::$js_footer_filename;

        if(!file_exists($js_footer_file))
        {
            sys::write([

                'file' => $js_footer_file,
                'data' => self::minify_js_footer()
            ]);
        }
    }


    public static function load_css()
    {
        if(self::is_active())
        {
            return html::css(ASSETS_SYS . '/' . self::$version . '/' . self::$css_filename);
        }
        else
        {
            $css    = self::$css_files;
            $output = null;

            foreach($css as $source)
            {
                $output .= html::css($source);
            }

            return $output;
        }
    }


    public static function load_js_header()
    {
        if(self::is_active())
        {
            return html::js(ASSETS_SYS . '/' . self::$version . '/' . self::$js_header_filename);
        }
        else
        {
            $js     = self::$js_header_files;
            $output = null;

            foreach($js as $source)
            {
                $output .= html::js($source);
            }

            return $output;
        }
    }


    public static function load_js_footer()
    {
        if(self::is_active())
        {
            return html::js(ASSETS_SYS . '/' . self::$version . '/' . self::$js_footer_filename);
        }
        else
        {
            $js     = self::$js_footer_files;
            $output = null;

            foreach($js as $source)
            {
                $output .= html::js($source);
            }

            return $output;
        }
    }

}


?>