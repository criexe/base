<title><?= cx::title() ?></title>

    <meta name="description" content="<?= cx::description() ?>">
    <meta name="keywords"    content="<?= cx::keywords()    ?>">
    <meta name="author"      content="<?= cx::author()      ?>">

    <meta name="robots" content="index, follow">

    <link rel="canonical" href="<?=cx::canonical()?>" />
    <?php if(cx::data('amp.active')) echo '<link rel="amphtml" href="' . amp::link(cx::canonical()) . '">' ?>

    <?php if(cx::data('item.data') != null): ?>
<meta property="og:type"        content="article" />
    <meta property="og:url"         content="<?=cx::data('item.data')['full_url']?>" />
    <meta property="og:title"       content="<?=cx::data('item.data')['title']?>" />
    <meta property="og:description" content="<?=cx::data('item.data')['description']?>" />
    <meta property="og:image"       content="<?=cx::data('item.data')['image_url']?>" />
    <meta property="fb:app_id"      content="<?=cx::option('facebook.app_id')?>" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@<?=cx::option('twitter.username')?>" />
    <meta name="twitter:creator" content="@<?=cx::option('twitter.username')?>" />
    <meta name="twitter:title" content="<?=cx::data('item.data')['title']?>" />
    <meta name="twitter:description" content="<?=cx::data('item.data')['description']?>" />
    <meta name="twitter:image" content="<?=cx::data('item.data')['image_url']?>" />
    <?php endif; ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <base href="<?=URL?>" target="_blank">

    <?php if(_config('layout.' . layout::name() . '.preloaded_assets') === true): ?>
    <?= _js(SYS_ASSETS . '/cx/plugins/jquery.js') ?>
    <?= _js(SYS_ASSETS . '/cx/plugins/jquery.form.js') ?>
    <?= _js(SYS_ASSETS . '/cx/plugins/ckeditor4.6/ckeditor.js') ?>
    <?= _js(SYS_ASSETS . '/cx/plugins/imagesloaded.js') ?>
    <?= _js(SYS_ASSETS . '/cx/plugins/isotope.min.js') ?>
    <?= _js(SYS_ASSETS . '/cx/js/cx.js') ?>
    <?php endif; ?>

    <script>
        var _URL       = "<?= URL ?>";
        var _CONTENTS  = "<?= CONTENTS ?>";
        var _USER_NAME = "<?= user::name() ?>";
        var _USER_ID   =  <?= user::id() == null ? 'false' : user::id() ?>;
    </script>

    