<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail</title>
</head>
<body style="padding:15px;">

<div style="
    color:#444;
    font-family: Roboto, sans-serif;
    font-weight: normal;

    max-width:500px;
    border:solid 1px #e0e0e0;
    background: #fafafa;
    margin:0 auto;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;">

    <div>
        <div style="
            font-size: 18px;
            color:#d35400;
            padding:20px;
            text-align: left;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            border-bottom:solid 1px #e0e0e0;

            "><?=$subject?></div>

        <div style="color:#444;padding:20px;"><?=layout::content()?></div>
    </div>

</div>

</body>
</html>