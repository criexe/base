<div class="panel panel-default">
    <div class="cover">
        <div class="cover-bg" style="background-image: url(<?=image::scale('http://naturewallpaperhd.org/wp-content/uploads/2015/06/NatureWallpaperHD_nature_autumn_forest_view_hd_wallpaper-wide.jpg', 1200, 0)?>);"></div>
        <div class="cover-content">
            <div class="center-box">
                <div class="center-content">
                    <div class="profile-area">
                        <div class="profile-area">
                            <div class="profile-image">
                                <img src="<?=$profile_image?>"
                                     alt="<?="$name $surname"?>">
                                <!-- <i class="fa fa-check-circle verified-icon"></i>-->
                            </div>
                            <div class="user-name"><?="$name $surname"?></div>
                            <div class="user-username">@<?="$username"?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 left-col">
        <div class="panel panel-default char-panel">
            <div class="characters-box">

                <?php foreach($chars as $char): ?>
                <div class="char-row clear">
                    <span class="char"><?=strip_tags($char['value'])?></span>
                    <span class="badge"><?=number_format($char['COUNT(*)'])?></span>
                    <button class="btn btn-primary btn-sm" data-char-id="<?=$char['id']?>">
                        <i class="fa fa-thumbs-up"></i>
                        <span>Oy Ver</span>
                    </button>
                </div>
                <?php endforeach; ?>

            </div>
            <div class="panel-footer">
                <form action="/user/add_char" id="add-new-char" method="post">
                    <input type="text" class="form-control input-user-char" name="value" placeholder="Yeni..." maxlength="30">
                    <input type="hidden" name="to" value="<?=$user_id?>">
                    <button type="submit" class="btn btn-success btn-sm btn-char-submit" data-loading-text="Ekleniyor...">
                        <i class="fa fa-check"></i>
                    </button>
                </form>
                <script>
                    $(function(){


                        $("body").off("click", "[data-char-id]").on("click", "[data-char-id]", function(e){

                            e.preventDefault();

                            var $btn = $(this);

                            $.get("/user/increase?id=" + $(this).data("char-id") + "&to=<?=$user_id?>", function(data){

                                if(data == "true"){

                                    $btn.html("<i class='fa fa-check'></i> <span>Oy Verildi</span>");
                                    $btn.removeClass("btn-primary").addClass("btn-success");
                                    $btn.parent().children('.badge').increase();
                                }
                            });
                        });


                        $("body").off("submit", "#add-new-char").on("submit", "#add-new-char", function(e){

                            e.preventDefault();

                            var $btn = $(this).children("button");
                            $btn.button("loading");

                            $(this).ajaxSubmit({

                                success: function(data){

                                    $btn.button("reset");
                                }

                            });
                        });

                    });
                </script>
            </div>
        </div>
    </div>
    <section class="col-lg-8 col-md-8 col-sm-8 content-col">
        <div class="loadMore" data-url="/dictionary/home?user=<?=$user_id?>" data-auto="true" data-button=".btnLoadMore" data-container=".content-body">
            <div class='loading'>
                <i class='fa fa-circle-o-notch fa-spin'></i>
                <span>Yükleniyor...</span>
            </div>
        </div>

        <button class="btnLoadMore btn btn-primary btn-block display-none" data-loading-text="Yükleniyor...">Daha Fazla</button>

        <script>
            $(function(){

                $(".loadMore").loadMore();
            });
        </script>
    </section>
</div>