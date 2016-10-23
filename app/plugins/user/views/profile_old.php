<div class="cover">
    <div class="cover-bg" style="background-image: url(<?=$cover_image?>)"></div>
    <div class="cover-content">
        <div class="center-box">
            <div class="center-content">

                <div class="profile-area">
                    <div class="profile-image">
                        <img src="<?=$profile_image?>"
                             alt="<?="$name $surname"?>">
<!--                        <i class="fa fa-check-circle verified-icon"></i>-->
                    </div>
                    <div class="user-name"><?="$name $surname"?></div>
                    <div class="user-username">@<?="$username"?></div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="profile-follow-button-box">
    <?= load::model('user:follow')->follow_button(1) ?>
</div>

<div class="panel panel-profile-bar clear">
    <div class="panel-body">

        <a href="#">
            <span class="num"><?=$follower_num?></span>
            <span class="label">Takipçi</span>
        </a>

        <a href="#">
            <span class="num"><?=$following_num?></span>
            <span class="label">Takip</span>
        </a>

    </div>
</div>


<div class="profile-content">

    <div class="container">
        <div class="row">
            <div class="col-md-5">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="list-group user-logs">
                            <?= load::model('user:log')->create_list(); ?>
                        </ul>
                    </div>
                </div>

                <div class="panel panel-profile">
                    <div class="panel-body">
                        <ul class="list-group panel-list-group">
                            <li class="list-group-item">
                                <h4>Doğum Tarihi</h4>
                                <p><?=$birthday?></p>
                            </li>
                            <li class="list-group-item">
                                <h4>Meslek</h4>
                                <p>Founder of Criexe Inc.</p>
                            </li>
                            <li class="list-group-item">
                                <h4>Yaşadığı Yer</h4>
                                <p>Ankara, Türkiye</p>
                            </li>
                        </ul>
                    </div>
                </div>

                <footer class="site-footer text-center hidden-sm hidden-xs">
                    <img src="http://base.dev/contents/images/17-01-2016/x50/14530489207651.png" alt="Criexe">
                </footer>

            </div>
            <div class="col-md-7">

                <!-- add new -->
                <div class="panel panel-post new-post">
                    <div class="panel-body">
                        <div class="row text-center">
                            <div class="col-sm-2">
                                <i class="fa fa-font"></i>
                            </div>
                            <div class="col-sm-2">
                                <i class="fa fa-image"></i>
                            </div>
                            <div class="col-sm-2">
                                <i class="fa fa-video-camera"></i>
                            </div>
                            <div class="col-sm-2">
                                <i class="fa fa-question-circle"></i>
                            </div>
                            <div class="col-sm-2">
                                <i class="fa fa-comment"></i>
                            </div>
                            <div class="col-sm-2">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>

                        <form action="/post/add" data-ajax-form data-type="json" method="post">
                            <hr>
                            <textarea name="content-text" class="form-control" placeholder="Yaz bi şeyler..."></textarea>
                            <hr>
                            <button type="submit" class="btn btn-success" data-loading-text="Bekle...">Gönder</button>
                        </form>
                        
                    </div>
                </div>
                <!-- add new -->

                <div data-load="/post/flow?id=1">
                    <div class="text-center">
                        <img src="http://base.dev/contents/images/17-01-2016/x50/14530489207651.png" alt="Criexe" class="fa-spin">
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>


<script>

    $cover_height  = $(".cover").height();
    $scroll_status = "cover";

    $(window).scroll(

        function(e)
        {
            if( ($(window).scrollTop() > $cover_height) && ($scroll_status == "cover") )
            {
                // Cover End
                console.log("[ Cover ] : End");
                $scroll_status = 'content';
            }
            else if( ($(window).scrollTop() < $cover_height) && ($scroll_status == "content") )
            {
                // Cover Start
                console.log("[ Cover ] : Start");
                $scroll_status = 'cover';
            }
        }
    );

</script>