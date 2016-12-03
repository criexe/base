<!DOCTYPE html>
<html>
<head>

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <?=html::css('/system/app/assets/item/style.css')?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title><?=cx::title()?></title>
</head>
<body class="item-layout grey lighten-5">
    <?=cx::body()?>
    <?=layout::content()?>

</body>
</html>