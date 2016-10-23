<div class="container mgt100px">

    <h1>#<?=$hashtag?></h1>
    <hr>

    <div class="row">
        <div class="col-md-5">

            <div class="panel panel-chat">
                <div class="panel-body chat-scroll scrollbar" data-load="/chat/hashtag_chat/?hashtag=<?=$hashtag?>"></div>

                <div class="panel-footer">
                    <form data-target="hashtag" data-hashtag="<?=$hashtag?>">
                        <textarea name="chat-input" class="form-control chat-textarea" placeholder="Yaz keke yaz"></textarea>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-md-7">

            <!-- add new -->
            <div class="panel panel-post new-post">
                <div class="panel-body">
                    <form action="/post/add" data-ajax-form data-type="json" method="post" enctype="multipart/form-data">

                        <div class="default-area">
                            <div class="image-area hidden">
                                <input type="file" name="image">
                                <hr>
                            </div>
                            <textarea name="content-text" class="form-control" placeholder="Yaz bi şeyler..."></textarea>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-success" data-loading-text="Bekle...">Gönder</button>
                        <i class="fa fa-image"></i>
                    </form>

                </div>
            </div>
            <!-- add new -->

            <div data-load="/post/flow/hashtag?hashtag=<?=$hashtag?>">
                <div class="text-center">
                    <img src="http://base.dev/contents/images/17-01-2016/x50/14530489207651.png" alt="Criexe" class="fa-spin">
                </div>
            </div>

        </div>
    </div>
</div>

<script>

    $(function(){

        setTimeout(function(){

            $(".chat-scroll").chat_scroll_bottom(1000)
        }, 1000);

    });

</script>

<script>

    $(function(){

        setInterval(function(){

            var $chat = $(".panel-chat .panel-body");
            var $uri  = $chat.data("load");

            $.get($uri, function(data){

                $chat.html(data);
                $chat.chat_scroll_bottom(1000);

            });

        }, 5000);

    });

</script>