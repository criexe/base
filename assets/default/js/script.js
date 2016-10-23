$("body").off("click", ".btn-follow").on("click", ".btn-follow",

    function(e)
    {
        e.preventDefault();

        var $user = $(this).data('user');
        var $this = $(this);

        if($user != undefined)
        {
            $.get("/user/follow/" + $user,

                function(d)
                {
                    var data   = $.parseJSON(d);

                    if(data.status == true)
                    {
                        if(data.action == "unfollowed")
                        {
                            $this.removeClass('unfollow').addClass('follow');

                            $this.tooltip("hide");
                            $this.attr("data-original-title", "Takip Et");
                            $this.tooltip("fixTitle");
                            $this.tooltip("show");
                        }
                        else if(data.action == "followed")
                        {
                            $this.removeClass('follow').addClass('unfollow');

                            $this.tooltip("hide");
                            $this.attr("data-original-title", "Takibi Kaldır");
                            $this.tooltip("fixTitle");
                            $this.tooltip("show");
                        }
                    }
                }
            );
        }
    }
);


// Delete Post
$("body").off("click", ".delete-post").on("click", ".delete-post",

    function(e)
    {
        e.preventDefault();

        var $this = $(this);
        var $post_id = $this.data('post-id');

        if($post_id != undefined)
        {
            $(".panel-post[data-post-id='" + $post_id + "']").hide(500,

                function()
                {
                    $.get("/post/remove/" + $post_id);
                }
            );
        }
    }
);


// Settings Toggle
$("body").off("click", ".settings-list-group .list-group-item .toggle-button").on("click", ".settings-list-group .list-group-item .toggle-button",

    function(e)
    {
        e.preventDefault();

        var $this      = $(this);
        var $cur_text  = $this.text();
        var $panel     = $this.siblings(".settings-panel");
        var $item      = $this.parent();

        $this.text( $cur_text == 'Düzenle' ? 'Kapat' : 'Düzenle' );

        // Loading Icon
        if($panel.html() == "")
        {
            $panel.html("<div class='text-center'><img class='fa-spin' src='/assets/default/img/loading_icon.png' alt='Loading'></div>");
        }

        // Toggle
        $panel.slideToggle("normal");
        $item.siblings(".list-group-item").slideToggle("normal");


        // Get Form
        setTimeout(

            function()
            {
                if($item.attr("data-ajax") == "true")
                {
                    $.get("/user/settings_panel/" + $item.data("alias"),

                        function(data)
                        {
                            $panel.html(data);
                        }
                    );
                    $item.attr("data-ajax", "false");
                }
            }, 1000
        );
    }
);



// Browser Modal
$("body").off("click", ".built-in-browser").on("click", ".built-in-browser",

    function(e)
    {
        e.preventDefault();

        var $link = $(this).attr("href");
        var $title = $(this).attr('title');

        window.open($link, "built-in-browser-iframe");

        // Open Browser
        $(".browserModal #browserTitle").text($title);
        $(".browserModal").modal("show");
        $('.browsermodal').modal('handleUpdate');

        return false;
    }
);


// Chat
$("body").off("keydown", ".chat-textarea").on("keydown", ".chat-textarea",

    function(e)
    {
        if( (e.keyCode == 13 || e.which == 13) && !e.shiftKey )
        {
            e.preventDefault();

            var $textarea = $(this);
            var $form     = $textarea.parent();
            var $chat     = $(".chat-scroll");

            var $target   = $form.data('target');
            var $hashtag  = $form.data('hashtag');

            var $message = $textarea.val();

            $textarea.val("").focus();

            $.post("/chat/hashtag_add?hashtag=" + $hashtag,

                {'message' : $message},

                function($data)
                {
                    $chat.chat_append_message($data)
                    $chat.chat_scroll_bottom()
                }
            );
        }
    }
);




/* New Post Form */
$("body").off("click", ".new-post .remove-image").on("click", ".new-post .remove-image",

    function()
    {
        var $image_item = $(this).parent();

        $image_item.hide("normal",

            function()
            {
                $image_item.remove();
                $(".post-image-label").toggle("normal");
            }
        );
    }
);

$("body").off("change", ".new-post #upload-post-image").on("change", ".new-post #upload-post-image",

    function(event)
    {
        var $image = $(this).val();
        var $tmp   = URL.createObjectURL(event.target.files[0]);

        if($image != "")
        {
            $(".uploaded-image-area img").attr("src", $tmp);
            $(".uploaded-image-area").slideToggle();
        }

        $(".post-image-label").toggle("normal");
    }
);