var cx = {


    tmp : {

        ajax_form : false
    },


    settings : {

        footer : function(){

            //$("select")                   . material_select();
            //$(".tooltip, [data-tooltip]") . tooltip({delay : 10});
            //$('.imgboxed')                . materialbox();
            //$('.modal-trigger')           . leanModal();

            /*
            $('.datepicker').pickadate({

                selectMonths : true,
                selectYears  : 15
            });
            */

            // Image Upload Trigger
            cx.event.click(".cx-image-upload-button button", function(e){

                cx.alert.toast("Choose an image.");

                var $form = $(this).attr("data-target");
                $($form + " .cx-image-upload-input").trigger("click");
            });

            // Image Upload Trigger
            cx.event.change("input.cx-image-upload-input", function(e){

                var $form = $(this).attr("data-form");
                $form     = $($form);

                $form.trigger("submit");
            });

            // Upload Image
            cx.ajax.submit(".cx-image-upload-form", function(data){

                var $form = $(cx.tmp.ajax_form);
                data      = cx.json.decode(data);

                var $form_id = $form.attr("id");

                cx.alert.toast(data.message);

                if(data.status == true)
                {
                    var $input   = $form.attr("data-input");
                    var $preview = $form.attr("data-preview");
                    var $wysiwyg = $form.attr("data-wysiwyg");

                    $($input)   . val(data.filename);
                    $($preview) . attr("src", data.image.secure_url).show("normal");

                    cx.wysiwyg.insert_html($wysiwyg, '<img data-cx-image src="' + data.image.secure_url + '">');

                    //$("#" + $form_id + " .img-preview img").attr("src", CONTENTS + '/' + data.image);
                    //$($preview).attr("src", CONTENTS + '/' + data.image);
                }
            });


            // Login & Register & Settings
            cx.ajax.submit("[data-cx-modal-user-login-form], [data-cx-modal-user-register-form], [data-cx-modal-user-settings-form]", {

                success : function(data){

                    data = cx.json.decode(data);
                    cx.alert.toast(data.message);

                    if(data.location != null && data.location != false) cx.location(data.location, 1000);
                }
            });


            // Social Share
            cx.event.click("[data-share]", function(e){

                e.preventDefault();

                var $social = $(this).attr("data-share");
                var $this   = $(this);

                if ($(this).attr("href") == "#")
                {
                    $url = window.location.href;
                }
                else
                {
                    $url = $(this).attr("href");
                }

                if ($social == "twitter")
                {
                    var $via  = $this.attr("data-via");
                    var $text = $this.attr("data-text");

                    if($text == undefined || $text == "" || typeof $text == "undefined") $text = "";

                    window.open("https://twitter.com/share?url=" + $url + "&text=@" + $via + " " + $text, "Twitter", 'width=600,height=500,scrollbars=no');
                    //window.open("https://twitter.com/share?url=" + $url + "&text=" + $text + "&via=" + $via, "Twitter", 'width=600,height=500,scrollbars=no');
                }
                else if ($social == "facebook")
                {
                    window.open("http://www.facebook.com/share.php?u=" + $url, "Facebook", 'width=600,height=500,scrollbars=no');
                }
                else if ($social == "google-plus")
                {
                    window.open("https://plusone.google.com/_/+1/confirm?hl=en&url=" + $url, "Google Plus", 'width=600,height=500,scrollbars=no');
                }
                else if($social == "whatsapp")
                {
                    window.open("whatsapp://send?text=" + $url, "Whatsapp", 'width=600,height=500,scrollbars=no');
                }
                else if($social == "linkedin")
                {
                    window.open("https://www.linkedin.com/cws/share?url=" + $url, "Linkedin", 'width=600,height=500,scrollbars=no');
                }

            });


            // Scrollbar
            $("[data-cx-scrollbar], [data-scrollbar], [cx-scrollbar]").perfectScrollbar();


            // Search
            cx.event.keyup("#search_modal .search-input", function(e){

                var $s        = $(this).val();
                var $ajax_url = _URL + "/sys/ajax_search";
                var $data     = {s : $s};

                cx.ajax.post($ajax_url, $data, function(data){

                    data = cx.json.decode(data);

                    if(data.status == true)
                    {

                        $("#search_modal .search-results").slideDown("normal").html(data.html);
                    }
                });
            });
        }
    },


    store : {

        get : function(key) {

            if(typeof(Storage) !== "undefined")
            {
                return localStorage.getItem(key);
            }
            else
            {
                return false;
            }
        },

        set : function(key, value) {

            if(typeof(Storage) !== "undefined")
            {
                localStorage.setItem(key, value);
            }
        }
    },



    log : function(msg){

        console.log(msg);
    },



    event : {

        click : function($sel, $func){

            $(document).on("click", $sel, $func);
        },

        submit : function($sel, $func){

            $(document).on("submit", $sel, $func);
        },

        change : function($sel, $func){

            $(document).on("change", $sel, $func);
        },

        keyup : function($sel, $func){

            $(document).on("keyup", $sel, $func);
        },

        blur : function($sel, $func){

            $(document).on("blur", $sel, $func);
        },

        on : function($on, $sel, $func){

            $(document).on($on, $sel, $func);
        },

        images_loaded : function($sel, $func){

            var $js_path = _URL + "/system/app/assets/cx/plugins/imagesloaded.js";

            cx.include.js($js_path, function(){

                $($sel).imagesLoaded($func);
            });
        }
    },



    ajax : {

        get  : function($url, $func){ return $.get($url, $func); },
        post : function($url, $data, $func){ return $.post($url, $data, $func); },
        load : function(){},
        submit : function($sel, $obj){

            cx.event.submit($sel, function(e){

                e.preventDefault();

                cx.tmp.ajax_form = this;
                $(this).ajaxSubmit($obj);
            });
        },

        upload : {

            image : function($sel){

                var $modal = "#image_upload_modal";
                $($modal).openModal();
            }
        }
    },


    wysiwyg : {

        editor : function ($name, $type){

            $type = typeof $type !== 'undefined' ?  $type : 'basic';

            var $basic_config = _URL + "/system/app/assets/cx/plugins/ckeditor4.6/config.js";
            var $full_config  = _URL + "/system/app/assets/cx/plugins/ckeditor4.6/full_config.js";
            var $css_file     = _URL + "/system/app/assets/cx/css/wysiwyg.content.css";

            var $config;

            if($type == "basic")
            {
                $config = {

                    customConfig : $basic_config,
                    contentsCss  : $css_file
                };
            }
            else
            {
                $config = {

                    customConfig : $full_config,
                    contentsCss  : $css_file
                };
            }

            var editor  = CKEDITOR.replace($name, $config);

            editor.on('change', function( evt ){

                $("[name='" + $name + "']").val(evt.editor.getData());
            });
        },

        insert_html : function($name, $html){

            CKEDITOR.instances[$name].insertHtml($html);
        }
    },


    code_editor : function($id, $lang, $theme){

        // Include ACE Editor
        cx.include.js(URL + "/system/app/assets/cx/plugins/ace-editor/ace.js");

        if($lang  == undefined) $lang  = "php";
        if($theme == undefined) $theme = "monokai";

        var editor = ace.edit($id);
        editor.setTheme("ace/theme/" + $theme);
        editor.getSession().setMode("ace/mode/" + $lang);

        return editor;
    },



    alert : {

        toast : function(msg, time, callback){

            if(time == undefined) time = 3000;

            Materialize.toast(msg, time, '', callback);
        },

        confirm : function(q){

            return confirm(q);
        }
    },



    location : function(url, delay){

        if(delay == undefined) delay = 0;

        setTimeout(function(){

            window.location.href = url;

        }, delay);
    },



    json : {

        encode : function($data){

            return JSON.stringify($data);
        },

        decode : function($data){

            return $.parseJSON($data);
        }
    },


    base64 : {

        encode : function(str) {

            return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {

                return String.fromCharCode('0x' + p1);
            }));
        },

        decode : function(str) {

            return decodeURIComponent(Array.prototype.map.call(atob(str), function(c) {

                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));
        }
    },


    include : {

        js : function($src, $success_func){

            $.getScript($src, $success_func);
        }
    },


    util : {

        /**
         * Create a web friendly URL slug from a string.
         *
         * Requires XRegExp (http://xregexp.com) with unicode add-ons for UTF-8 support.
         *
         * Although supported, transliteration is discouraged because
         *     1) most web browsers support UTF-8 characters in URLs
         *     2) transliteration causes a loss of information
         *
         * @author Sean Murphy <sean@iamseanmurphy.com>
         * @copyright Copyright 2012 Sean Murphy. All rights reserved.
         * @license http://creativecommons.org/publicdomain/zero/1.0/
         *
         * @param string s
         * @param object opt
         * @return string
         */

        slugify : function(s, opt) {

            s   = String(s);
            opt = Object(opt);

            var defaults = {
                'delimiter': '-',
                'limit': undefined,
                'lowercase': true,
                'replacements': {},
                'transliterate': (typeof(XRegExp) === 'undefined') ? true : false
            };

            // Merge options
            for (var k in defaults) {
                if (!opt.hasOwnProperty(k)) {
                    opt[k] = defaults[k];
                }
            }

            var char_map = {
                // Latin
                'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE', 'Ç': 'C',
                'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I',
                'Ð': 'D', 'Ñ': 'N', 'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', 'Ő': 'O',
                'Ø': 'O', 'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ű': 'U', 'Ý': 'Y', 'Þ': 'TH',
                'ß': 'ss',
                'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae', 'ç': 'c',
                'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i',
                'ð': 'd', 'ñ': 'n', 'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ő': 'o',
                'ø': 'o', 'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ű': 'u', 'ý': 'y', 'þ': 'th',
                'ÿ': 'y',

                // Latin symbols
                '©': '(c)',

                // Greek
                'Α': 'A', 'Β': 'B', 'Γ': 'G', 'Δ': 'D', 'Ε': 'E', 'Ζ': 'Z', 'Η': 'H', 'Θ': '8',
                'Ι': 'I', 'Κ': 'K', 'Λ': 'L', 'Μ': 'M', 'Ν': 'N', 'Ξ': '3', 'Ο': 'O', 'Π': 'P',
                'Ρ': 'R', 'Σ': 'S', 'Τ': 'T', 'Υ': 'Y', 'Φ': 'F', 'Χ': 'X', 'Ψ': 'PS', 'Ω': 'W',
                'Ά': 'A', 'Έ': 'E', 'Ί': 'I', 'Ό': 'O', 'Ύ': 'Y', 'Ή': 'H', 'Ώ': 'W', 'Ϊ': 'I',
                'Ϋ': 'Y',
                'α': 'a', 'β': 'b', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'h', 'θ': '8',
                'ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': '3', 'ο': 'o', 'π': 'p',
                'ρ': 'r', 'σ': 's', 'τ': 't', 'υ': 'y', 'φ': 'f', 'χ': 'x', 'ψ': 'ps', 'ω': 'w',
                'ά': 'a', 'έ': 'e', 'ί': 'i', 'ό': 'o', 'ύ': 'y', 'ή': 'h', 'ώ': 'w', 'ς': 's',
                'ϊ': 'i', 'ΰ': 'y', 'ϋ': 'y', 'ΐ': 'i',

                // Turkish
                'Ş': 'S', 'İ': 'I', 'Ç': 'C', 'Ü': 'U', 'Ö': 'O', 'Ğ': 'G',
                'ş': 's', 'ı': 'i', 'ç': 'c', 'ü': 'u', 'ö': 'o', 'ğ': 'g',

                // Russian
                'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ё': 'Yo', 'Ж': 'Zh',
                'З': 'Z', 'И': 'I', 'Й': 'J', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O',
                'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C',
                'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Sh', 'Ъ': '', 'Ы': 'Y', 'Ь': '', 'Э': 'E', 'Ю': 'Yu',
                'Я': 'Ya',
                'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh',
                'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
                'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
                'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu',
                'я': 'ya',

                // Ukrainian
                'Є': 'Ye', 'І': 'I', 'Ї': 'Yi', 'Ґ': 'G',
                'є': 'ye', 'і': 'i', 'ї': 'yi', 'ґ': 'g',

                // Czech
                'Č': 'C', 'Ď': 'D', 'Ě': 'E', 'Ň': 'N', 'Ř': 'R', 'Š': 'S', 'Ť': 'T', 'Ů': 'U',
                'Ž': 'Z',
                'č': 'c', 'ď': 'd', 'ě': 'e', 'ň': 'n', 'ř': 'r', 'š': 's', 'ť': 't', 'ů': 'u',
                'ž': 'z',

                // Polish
                'Ą': 'A', 'Ć': 'C', 'Ę': 'e', 'Ł': 'L', 'Ń': 'N', 'Ó': 'o', 'Ś': 'S', 'Ź': 'Z',
                'Ż': 'Z',
                'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z',
                'ż': 'z',

                // Latvian
                'Ā': 'A', 'Č': 'C', 'Ē': 'E', 'Ģ': 'G', 'Ī': 'i', 'Ķ': 'k', 'Ļ': 'L', 'Ņ': 'N',
                'Š': 'S', 'Ū': 'u', 'Ž': 'Z',
                'ā': 'a', 'č': 'c', 'ē': 'e', 'ģ': 'g', 'ī': 'i', 'ķ': 'k', 'ļ': 'l', 'ņ': 'n',
                'š': 's', 'ū': 'u', 'ž': 'z'
            };

            // Make custom replacements
            for (var k in opt.replacements) {
                s = s.replace(RegExp(k, 'g'), opt.replacements[k]);
            }

            // Transliterate characters to ASCII
            if (opt.transliterate) {
                for (var k in char_map) {
                    s = s.replace(RegExp(k, 'g'), char_map[k]);
                }
            }

            // Replace non-alphanumeric characters with our delimiter
            var alnum = (typeof(XRegExp) === 'undefined') ? RegExp('[^a-z0-9]+', 'ig') : XRegExp('[^\\p{L}\\p{N}]+', 'ig');
            s = s.replace(alnum, opt.delimiter);

            // Remove duplicate delimiters
            s = s.replace(RegExp('[' + opt.delimiter + ']{2,}', 'g'), opt.delimiter);

            // Truncate slug to max. characters
            s = s.substring(0, opt.limit);

            // Remove delimiter from ends
            s = s.replace(RegExp('(^' + opt.delimiter + '|' + opt.delimiter + '$)', 'g'), '');

            return opt.lowercase ? s.toLowerCase() : s;
        }
    },


    animate : {

        css : function($sel, $animation){

            var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

            $($sel).addClass("animated " + $animation).on(animationEnd, function(){

                $($sel).removeClass("animatd " + $animation);
            });
        }
    },


    scrollfire : function($options){

        Materialize.scrollFire($options);
    },


    view : {

        column_grid : function($sel, $column_data_sel, $appended_data){

            var $js_path = URL + "/system/app/assets/cx/plugins/isotope.min.js";

            cx.include.js($js_path, function(){

                cx.event.images_loaded($sel, function(){

                    if(typeof $appended_data == 'undefined')
                    {
                        $($sel).isotope({itemSelector : $column_data_sel});
                    }
                    else
                    {
                        $($sel).isotope({itemSelector : $column_data_sel}).isotope('appended', $appended_data);
                    }
                });
            });
        }
    },

    load_more : function($opt){

        $(function(){

            if(typeof $opt == 'undefined') $opt = {};

            var $current_page = $opt.start;

            $($opt.button).css({"cursor" : "pointer"});
            cx.event.click($opt.button, function(e){

                cx.ajax.get($opt.url, { p : $current_page, limit : $opt.limit }).done(function(x){

                    console.log($opt);

                    $($opt.container).append(x);
                    $current_page++;
                    if(typeof $opt.success != 'undefined') $opt.success(x);
                });

            });
        });
    }



}