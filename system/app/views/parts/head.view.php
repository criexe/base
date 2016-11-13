<title><?= cx::title() ?></title>

<meta name="description" content="<?= cx::description() ?>">
<meta name="keywords"    content="<?= cx::keywords()    ?>">
<meta name="author"      content="<?= cx::author()      ?>">

<?php if(cx::data('item.data') != null): ?>
    <meta property="og:type"        content="article" />
    <meta property="og:url"         content="<?=cx::data('item.data')['full_url']?>" />
    <meta property="og:title"       content="<?=cx::data('item.data')['title']?>" />
    <meta property="og:description" content="<?=cx::data('item.data')['description']?>" />
    <meta property="og:image"       content="<?=html::image_link(cx::data('item.data')['image_url'], 880)?>" />
    <meta property="fb:app_id"      content="<?=cx::option('facebook.app_id')?>" />
<?php endif; ?>

<meta charset="utf-8">

<script src="<?= SYS_ASSETS ?>/cx/plugins/jquery.js"></script>
<script src="<?= SYS_ASSETS ?>/cx/plugins/jquery.form.js"></script>

<script src="<?= SYS_ASSETS ?>/cx/js/cx.js"></script>
<link rel="stylesheet" href="<?= SYS_ASSETS ?>/cx/css/style.css">

<link rel="stylesheet" href="<?= SYS_ASSETS ?>/cx/plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?= SYS_ASSETS ?>/cx/plugins/animate.css">

<!-- MaterializeCSS -->
<link rel="stylesheet" href="<?= SYS_ASSETS ?>/cx/plugins/materialize/css/materialize.min.css">
<script src="<?= SYS_ASSETS ?>/cx/plugins/materialize/js/materialize.min.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- MaterializeCSS -->

<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<script type="text/javascript" src="<?= SYS_ASSETS ?>/cx/plugins/ckeditor/ckeditor.js"></script>

<script>
    var URL      = "<?= URL ?>";
    var CONTENTS = "<?= CONTENTS ?>";
</script>