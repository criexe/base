</div><!-- right-col-body -->
</div><!-- right-col -->
</div><!-- main-content -->

<!-- Top Alerts -->
<div class="topAlert"></div>
<!-- Top Alerts -->


<script type="text/javascript">
    $(function(){
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    });

    $(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>

<?= html::js(ASSETS . '/default/js/ajax.js') ?>
<script type="text/javascript" src="<?= ASSETS ?>/admin/js/admin.js"></script>
<script type="text/javascript" src="<?= ASSETS ?>/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>