<?= ( (defined('CONTROLLER')) && ! (CONTROLLER == 'admin' || CONTROLLER == 'developer')) ? cx::option('app.tracking_code') : null ?>

<?php if(_config('layout.' . layout::name() . '.preloaded_assets') === true): ?>
<script> $(function(){ cx.settings.footer() }); </script>
<?php endif; ?>