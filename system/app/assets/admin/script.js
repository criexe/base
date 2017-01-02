cx.ajax.submit("#admin_login_form", {

    success : function($data){

        $data = cx.json.decode($data);

        cx.alert.toast($data.message, 2000, function(){

            if($data.status == true) cx.location(URL + "/admin");
        });
    }
});


cx.ajax.submit("[data-add-form] form, [data-edit-form] form", {

    success : function($data){

        $data = cx.json.decode($data);

        if($data.status == true)
        {
            cx.alert.toast('Success');
        }
        else
        {
            if($data.message != "") cx.alert.toast($data.message);
            else                    cx.alert.toast("Error");
        }

        if($data.location != "" && $data.location != false)
        {
            setTimeout(function(){ cx.location($data.location) }, 1000);
        }
    }
});


cx.event.click("[data-delete-item-button]", function(e){

    e.preventDefault();

    if(cx.alert.confirm('Do you really want to delete this?'))
    {
        var $href      = $(this).attr("href");
        var $id        = $(this).attr("data-id");
        var $item_card = $("[data-item='" + $id + "']");

        cx.ajax.get($href, function($data){

            $data = cx.json.decode($data);

            if($data.status == true)
            {
                $item_card.hide("normal", function(){

                    $item_card.remove();
                });
            }
            else
            {
                if($data.message == "") $data.message = "Error";

                cx.alert.toast($data.message);
            }
        });
    }
});

cx.event.change("[data-change-post-status]", function(e){

    var $url  = URL + "/admin/change_post_status";
    var $id   = $(this).attr("data-id");
    var $val  = $(this).val();

    var $data = {

        id  : $id,
        val : $val
    };

    cx.ajax.post($url, $data, function(data){

        data = cx.json.decode(data);
        cx.alert.toast(data.message);
    });
});

$(function(){ $('.scrollspy').scrollSpy(); });

cx.ajax.submit("#settings_form", {

    success : function(data){

        data = cx.json.decode(data);
        cx.alert.toast(data.message);
    }
});


if($("[name='db[url]']").val() == "")
{
    cx.event.keyup("[name='db[title]']", function(e){

        var from_val = $("[name='db[title]']").val();
        var to_val   = cx.util.slugify(from_val);

        $("[name='db[url]']").val(to_val);
        $("[name='db[url]']").trigger("change");
    });
}

cx.event.on("change blur", "[name='db[url]']", function(e){

    $url_input = $(this);

    cx.ajax.post(URL + "/helper/check_url", {url : $url_input.val()}, function(data){

        if(data == "true")
        {
            $url_input.removeClass("valid").addClass("invalid");
            $("[type='submit']").attr("disabled", "disabled");
        }
        else
        {
            $url_input.removeClass("invalid").addClass("valid");
            $("[type='submit']").removeAttr("disabled");
        }
    });
});