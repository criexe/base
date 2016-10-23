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