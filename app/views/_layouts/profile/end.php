</div>

</div>


</div><!-- content-body -->
</div><!--content-col-->
<div class="site-right-col col-lg-2 col-md-3 pull-right">

    <div class="right-body scrollbar">
        <div class="date-label"><span>Bugün</span></div>

        <?php

        $m_user_log = load::model('user:log');
        $logs       = $m_user_log->get_logs();

        foreach($logs as $log)
        {
            echo $m_user_log->list_item($log);
        }
        ?>

    </div>

</div><!--right-col-->
</div><!--site-container-->


<!-- Settings -->
<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Kapat"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="settingsLabel">Ayarlar</h4>
            </div>
            <div class="modal-body">

                <ul class="list-group settings-list-group">
                    <li class="list-group-item" data-alias="account" data-ajax="true">
                        <span> <i class="fa fa-cog"></i> Hesap Ayarlar</span>
                        <a class="badge toggle-button">Düzenle</a>
                        <div class="settings-panel"></div>
                    </li>

                    <li class="list-group-item" data-alias="password" data-ajax="true">
                        <span> <i class="fa fa-key"></i> Şifre Ayarları</span>
                        <a class="badge toggle-button">Düzenle</a>
                        <div class="settings-panel"></div>
                    </li>
                </ul>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
<!-- Settings -->


<!-- New Dict -->
<div class="modal fade new-dict-modal" id="newDictModal" tabindex="-1" role="dialog" aria-labelledby="newDictLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="dict-post-form" id="newDictTitle" action="/dictionary/new_title" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="newDictLabel">Yeni Başlık Aç</h4>
                </div>
                <div class="modal-body">

                    <div class="alert alert-warning">Giriş yapmadığınız için <strong>anonim</strong> olarak gözükeceksiniz.</div>

                    <div class="form-group">
                        <input type="text" name="title" class="form-control dict-title-input" placeholder="Başlık" onkeyup="input_set_store(this, 'new_dict_title')" onclick="input_get_store(this, 'new_dict_title')">
                    </div>
                    <div class="form-group">
                        <textarea name="content" class="form-control dict-content-input" placeholder="İçerik..." onkeyup="input_set_store(this, 'new_dict_content')" onclick="input_get_store(this, 'new_dict_content')"></textarea>
                        <small class="limitless noselect">Sınırsız</small>
                    </div>
                    <div class="form-group">
                        <input type="text" name="tags" class="form-control tags-input" placeholder="Etiketler">
                    </div>

                    <div class="msg-area display-none"></div>

                    <button type="submit" class="btn btn-success" data-loading-text="Bekle...">
                        <i class="fa fa-check"></i>
                        <span>Kaydet</span>
                    </button>

                    <script>
                        $(function(){

                            $("body").off("change", ".dict-title-input").on("change", ".dict-title-input", function(e){

                                var $title_str = $(".dict-title-input").val();
                                var $tags = $title_str.split(" ");

                                $tags.forEach(function($tag){

                                    if(! $(".tags-input").tagExist($tag))
                                    {
                                        $('.tags-input').addTag($tag.toLowerCase());
                                    }
                                });
                            });
                        });
                    </script>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- New Dict -->

<script>
    $(function(){

        $("body").off("submit", "#newDictTitle").on("submit", "#newDictTitle", function(e){

            e.preventDefault();

            $(this).ajaxSubmit({

                success : function(data){

                    $("#newDictTitle .msg-area").html(data).slideDown();
                    input_set_store(false, "new_dict_title", "");
                    input_set_store(false, "new_dict_content", "");

                    $("#newDictTitle").trigger('reset');

                    return true;
                }
            });

        });

    });
</script>


<!-- New Post -->
<div class="modal fade new-post" id="newPostModal" tabindex="-1" role="dialog" aria-labelledby="newPostLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/post/add" data-ajax-form data-type="json" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="newPostLabel">Paylaş</h4>
                </div>
                <div class="modal-body">
                    <div class="default-area">
                        <div class="image-area hidden">
                            <input type="file" name="image">
                            <hr>
                        </div>
                        <textarea name="content-text" class="form-control" placeholder="Bir şeyler yaz..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success pull-left" data-loading-text="Bekle...">Paylaş</button>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Vazgeç</button>

                    <a href="" class="pull-right">
                        <i class="fa fa-image"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- New Post -->

<!-- Browser -->
<div class="modal fade browserModal" tabindex="-1" role="dialog" aria-labelledby="browserModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="pull-right btn btn-danger btn-xs" data-dismiss="modal">
                    <i class="fa fa-times"></i> Kapat
                </button>
                <h4 class="modal-title" id="browserTitle"></h4>
            </div>

            <iframe name="built-in-browser-iframe" src="" frameborder="0" style="width:100%; height:100%; margin:0; padding:0;"></iframe>

        </div>
    </div>
</div>

<!-- Browser -->


<!-- Top Alerts -->
<div class="topAlert"></div>
<!-- Top Alerts -->

<script>
    $(function(){

        $(".loadMoreSidebar").loadMore();
    });
</script>

<script>
    $(function(){

        $(".tags-input").tagsInput({
            width:"100%",
            height:"auto",
            'defaultText':'Etiketler',
            placeholderColor:"#9B9999"
        });
    });
</script>

<script>
    $(function(){

        $(".scrollbar").perfectScrollbar();
        $(".scrollbar").perfectScrollbar('update');
    });
</script>

<script type="text/javascript">
    $(function(){
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    });

    $(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>

<?= asset::load_js_footer() ?>

<!--<script type="text/javascript" src="--><?//= ASSETS ?><!--/app/opt.js"></script>-->
<!--<script type="text/javascript" src="--><?//= ASSETS ?><!--/default/js/script.js"></script>-->
<!--<script type="text/javascript" src="--><?//= ASSETS ?><!--/fullpage/jquery.fullpage.min.js"></script>-->
<!--<script type="text/javascript" src="--><?//= ASSETS ?><!--/bootstrap/js/bootstrap.min.js"></script>-->
<!--<script type="text/javascript" src="--><?//= ASSETS ?><!--/default/js/ajax.js"></script>-->

</body>
</html>