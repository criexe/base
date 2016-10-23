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

<!-- Top Alerts -->
<div class="topAlert"></div>
<!-- Top Alerts -->

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

</body>
</html>