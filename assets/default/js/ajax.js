/**
 * Created by mustafa on 15.11.15.
 */



function show_loading(text, write)
{
    text  = (typeof text !== 'undefined') ? text : 'Yükleniyor...'
    write = typeof write !== 'undefined' ? true : false;

    var html = "<div class='loading'><i class='fa fa-circle-o-notch fa-spin'></i> <span>" + text + "</span></div>";

    if(write == true)
    {
        document.write(html);
        return html;
    }
    else
    {
        return html;
    }
}




$("body").off("submit", "form[data-ajax-form]").on("submit", "form[data-ajax-form]", function(e){

    e.preventDefault();

    var alert_message, alert_class;

    var _location = $(this).attr("data-location");
    var data_type = $(this).attr("data-type");


    // Button / Loading
    var $btn_submit = $(this).children('[type="submit"]');
    $btn_submit.button("loading");


    $(this).ajaxSubmit({
        success : function(r)
        {
            if(data_type == 'json')
            {
                var objData = $.parseJSON(r);
                if (objData.status == true)
                {
                    alert_class = "alert-success";
                    alert_message = "Success.";
                }
                else
                {
                    alert_class = "alert-danger";
                    alert_message = objData.message;
                }

                topAlert({
                    cssClass: alert_class,
                    message: alert_message,
                    done: function(){

                        if(_location != undefined) _go(_location);
                    }
                });
            }
            else
            {
                topAlert({
                    cssClass: 'alert-warning',
                    message: r,
                    done: function(){

                        if(_location != undefined) _go(_location);
                    }
                });
            }
            $btn_submit.button('reset');
        }
    });

    return false;
});



// Data Load
$("[data-load]").each(

    function(e)
    {
        var $this        = $(this);
        var $content_url = $this.data('load');
        var $content;

        if($content_url != "" && $content_url != undefined)
        {
            $.get($content_url,

                function(data)
                {
                    $this.html(data);
                }
            );
        }
    }
);


// Ajax Link
$("body").off("click", "[data-ajax-link]").on("click", "[data-ajax-link]", function(e){

    e.preventDefault();

    // Content URL
    var content_url = $(this).attr("href");

    // Change URL
    var change_url = $(this).data("change-url");

    if(change_url == "false" || change_url == false)
    {
        change_url = false;
    }
    else
    {
        change_url = true;
    }

    // Set Link
    var set_link = $(this).attr("data-set-link");
    var show_link;
    if(set_link == "" || set_link == undefined)
    {
        show_link = content_url;
    }
    else
    {
        show_link = set_link;
    }

    // Target Content
    var target_content = $(this).attr("data-target");
    var _content_area;

    if(target_content == "" || target_content == undefined) // if attr is not exist
        _content_area = $("[data-ajax-content-area]");
    else
        _content_area = $(target_content);


    $animation_time = 0;

    // Loading
    _content_area.fadeOut($animation_time, function () {
        _content_area.html("<div class='loading'><i class='fa fa-circle-o-notch fa-spin'></i> <span>Yükleniyor...</span></div>");
    });
    _content_area.fadeIn($animation_time);


    // Get Content
    $.ajax({
        url: content_url,
        type: "get",
        data: {layout : "false"},
        success: function (r)
        {
            if (r == "error")
            {
                alert("Error.");
            }
            else
            {
                if(change_url)
                {
                    // Change URL
                    history.pushState({id: show_link}, '', show_link);
                }

                _content_area.fadeOut($animation_time, function () {
                    _content_area.html(r);
                });
                _content_area.fadeIn($animation_time, function(){
                    _content_area.show();
                });

                $(".scrollbar").stop().animate({scrollTop:0}, "slow");
            }
        }
    });
});