<title><?= cx::title() ?></title>

    <meta name="description" content="<?= cx::description() ?>">
    <meta name="keywords"    content="<?= cx::keywords()    ?>">
    <meta name="author"      content="<?= cx::author()      ?>">

    <meta name="robots" content="index, follow">

    <link rel="canonical" href="<?=cx::canonical()?>" />

    <?php if(cx::data('item.data') != null): ?>
<meta property="og:type"        content="article" />
    <meta property="og:url"         content="<?=cx::data('item.data')['full_url']?>" />
    <meta property="og:title"       content="<?=cx::data('item.data')['title']?>" />
    <meta property="og:description" content="<?=cx::data('item.data')['description']?>" />
    <meta property="og:image"       content="<?=html::image_link(cx::data('item.data')['image_url'], 880)?>" />
    <meta property="fb:app_id"      content="<?=cx::option('facebook.app_id')?>" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@<?=cx::option('twitter.username')?>" />
    <meta name="twitter:title" content="<?=cx::data('item.data')['title']?>" />
    <meta name="twitter:description" content="<?=cx::data('item.data')['description']?>" />
    <meta name="twitter:image" content="<?=html::image_link(cx::data('item.data')['image_url'], 880)?>" />
    <?php endif; ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <script src="<?= SYS_ASSETS ?>/cx/plugins/jquery.js"></script>
    <script src="<?= SYS_ASSETS ?>/cx/plugins/jquery.form.js"></script>
    <script src="<?= SYS_ASSETS ?>/cx/plugins/materialize/js/materialize.min.js"></script>
    <script type="text/javascript" src="<?= SYS_ASSETS ?>/cx/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?= SYS_ASSETS ?>/cx/plugins/imagesloaded.js"></script>
    <script type="text/javascript" src="<?= SYS_ASSETS ?>/cx/plugins/isotope.min.js"></script>

    <script src="<?= SYS_ASSETS ?>/cx/js/cx.js?v4"></script>

    <script>
        var URL      = "<?= URL ?>";
        var CONTENTS = "<?= CONTENTS ?>";
    </script>