<?= ( (defined('CONTROLLER')) && ! (CONTROLLER == 'admin' || CONTROLLER == 'developer')) ? cx::option('app.tracking_code') : null ?>

<?php if(false && cx::data('item.data') && (user::authority() === 'developer' || user::authority() === 'admin')): ?>
    <a href="<?=_ADMIN?>/edit/<?=cx::data('item.data')['id']?>" class="btn-floating cx-fixed btn-large waves-effect waves-light red modal-trigger"><i class="material-icons">settings</i></a>
<?php endif; ?>

<script> $(function(){ cx.settings.footer() }); </script>