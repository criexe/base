/**
 *
 * @author Mustafa Aydemir
 * @date   26 Feb 2016
 *
 */

$.fn.loadMore = function(opt)
{
    var $url    = this.data("url");
    var $btn    = this.data("button");
    var $cont   = this.data("container");
    var $auto   = this.data("auto");
    var $start  = this.data("start");
    var $page   = this.data("page");

    var $this    = this;
    var $pending = false;

    if($auto == "true" || $auto == true) $auto = true; else $auto = false;
    if($cont == undefined) $cont = window;

    if($url  == undefined){ console.log("Set the URL."); return false; }
    if($btn  == undefined && $auto == false){ console.log("Set the button."); return false; }

    if($start == undefined || $start == "1") $start = 1; else $start = parseInt($start, 10);
    if($page  == undefined || $page  == "1") $page  = 1; else $page  = parseInt($page, 10);

    if(opt == undefined) opt = {};

    var $active = true;

    var $get_data = function()
    {
        if(opt.before != undefined) opt.before();
        if($btn != undefined) $($btn).button("loading");

        if($pending == false)
        {
            $.get($url, {

                    page_no  : $page,
                    start    : $start
                }
            ).done(function(data){

                if(data == "")
                {
                    $active = false;
                    $($btn).hide("normal");
                }

                if($page == 1) $this.html("");
                $this.append(data);
                $page++;
                $pending = false;

                if($btn != undefined)
                {
                    $($btn).button("reset");
                    $($btn).removeClass("display-none");
                }
                if(opt.after != undefined) opt.after();
                if(opt.first_after != undefined && $page == 1) opt.first_after();
            });
        }
        $pending = true;
    }

    $get_data();

    if($auto == true)
    {
        console.log("Auto load more.");

        $($cont).on("scroll", function(e){

            if($active == true)
            {
                var $bottom      = $($cont).scrollTop() + $($cont).height() == $(document).height();
                var $near_bottom = $($cont).scrollTop() + $($cont).height() > $($cont)[0].scrollHeight - $($cont).height();

                //console.log('Cont : ' + $($cont).scrollTop() );
                //console.log('Doc : ' + $(document).height() );
                //console.log('ScrollHeight : ' + $($cont)[0].scrollHeight );

                if($near_bottom) $get_data();

                if(opt.after_scroll != undefined) opt.after_scroll();
            }
        });
    }
    else
    {
        $("body").on("click", $btn, function(){

            if($active == true)
            {
                $get_data();
            }

        });
    }
}