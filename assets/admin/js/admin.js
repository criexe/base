/**
 * Created by mustafa on 14.11.15.
 */

//
//// Ajax Form
//$("body").on("submit", "form[data-ajax-form]",
//
//    function(e)
//    {
//        e.preventDefault();
//
//        var alert_message, alert_class;
//
//        var _location = $(this).attr("data-location");
//
//
//        // Button / Loading
//        var $btn_submit = $(this).children('[type="submit"]');
//        $btn_submit.button("loading");
//
//
//        $(this).ajaxSubmit({
//            success : function(r)
//            {
//
//                var objData = $.parseJSON(r);
//                if (objData.status == true)
//                {
//                    alert_class = "alert-success";
//                    alert_message = "Success.";
//                }
//                else
//                {
//                    alert_class = "alert-danger";
//                    alert_message = "<strong>Error : </strong> " + objData.message;
//                }
//
//                topAlert({
//                    cssClass: alert_class,
//                    message: alert_message,
//                    done: function(){
//
//                        if(_location != undefined) _go(_location);
//                    }
//                });
//
//                $btn_submit.button("reset");
//            }
//        });
//
//        return false;
//    }
//);


// DB Delete
$("body").on("click", "#dbDelete",

    function(e)
    {
        e.preventDefault();

        var data_id = $(this).attr("data-id");
        var link    = $(this).attr("href");
        var tr      = $("tr[data-id='" + data_id + "']");

        var alert_message, alert_class;

        $.get(link, function(r){

            var objData = $.parseJSON(r);
            if(objData.status == true)
            {
                alert_class   = "alert-success";
                alert_message = "Success.";

                tr.addClass("bg-danger");
                tr.hide("slow");
            }
            else
            {
                alert_class   = "alert-danger";
                alert_message = "Error.";
            }

            topAlert({
                cssClass : alert_class,
                message  : alert_message
            });
        });

        return false;
    }
);



// Admin Menu Toggle
$("body").on("click", ".admin-menu li.admin-menu-item a",

    function(e)
    {
        if($(this).parent().has("ul").length)
        {
            e.preventDefault();

            // Toggle Menu
            $(this).parent().children("ul.submenu").toggle("fast");

            // Toggle CSS
            $(this).parent().toggleClass("active");
            $(".admin-menu .admin-menu-item:not(:parent)").removeClass("active");
        }
    }
);