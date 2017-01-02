<?php
$data = cx::data('item.data');
list($img_width, $img_height) = getimagesize($data['image_url']);
?>
<!doctype html>
<html amp>
<head>
    <meta charset="utf-8">
    <title><?=cx::title()?></title>
    <link rel="canonical" href="<?=cx::canonical()?>">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "NewsArticle",
      "mainEntityOfPage": "<?=cx::canonical()?>",
      "publisher": {
        "@type": "Organization",
        "name": "<?=cx::option('app.name')?>",
        "logo": {
          "@type": "ImageObject",
          "url": "<?=URL?>/logo.png",
          "width": 178,
          "height": 60
        }
      },
      "headline": "<?=$data['title']?>",
      "datePublished": "<?=cx::date('c', $data['created_at']['time'])?>",
      <?php if($data['updated_at']): ?>
      "dateModified": "<?=cx::date('c', $data['updated_at']['time'])?>",
      <?php endif; ?>
      "author": {
        "@type": "Person",
        "name": "<?=cx::option('app.name')?>"
      },
      "description": "<?=cx::description()?>",
      "image": {
        "@type": "ImageObject",
        "url": "<?=$data['image_url']?>",
        "width": <?=$img_width?>,
        "height": <?=$img_height?>
      }
    }
    </script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">

    <style amp-custom>
        *{margin:0;}
        body{background: #fff; font-family: Roboto, sans-serif; font-weight:300; margin:0;color:555;}
        h1{font-weight:400;color:#444;font-family:Roboto, sans-serif;font-size:20px;}
        .header{text-align:left; background: #D32F2F;padding:0;}
        .header a{color:#fff;font-weight:300;font-size:30px;text-decoration: none;display:block;padding:10px 0}
        .amp-container{max-width:600px; margin:0 auto; padding:0 15px}
        .bg-eee{background:#eee;margin:0;margin-bottom:15px;padding:15px 0;border-bottom:solid 2px #e0e0e0}
        amp-img, img{margin:15px 0; -webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;}
        ul{list-style:none;margin-left:0;padding-left:15px;border-left:solid 2px #eee;margin-bottom:15px}
        li{list-style:none;padding:10px;padding-left:0;border-bottom:solid 1px #eee;}
        li:last-child{border-bottom:none;}
        article p{margin-bottom:15px}article h2{color:#B71C1C;font-size:28px;font-weight:300;margin:1.14rem 0 0.912rem 0}article h3,article h4{font-weight:300;color:#d35400;font-size:24px;margin-bottom:15px}article blockquote{margin:20px 0;border-left:3px solid #ee6e73;background-color:#fafafa;padding:20px;color:#666}article blockquote p{margin:0}
        .footer{border-bottom:none;border-top:solid 2px #e0e0e0;margin:20px 0 0 0;}
    </style>
</head>
<body>

<div class="header">
    <div class="amp-container">
        <a href="<?=URL?>"><?=cx::option('app.name')?></a>
    </div>
</div>

<div class="bg-eee">
    <div class="amp-container">
        <h1><?=$data['title']?></h1>
    </div>
</div>

<div class="amp-container">
    <article>
        <?=amp::content_filter($data['content'])?>
    </article>
</div>

<div class="bg-eee footer">
    <div class="amp-container">
        <?=cx::option('app.name')?>
    </div>
</div>

<amp-analytics type="googleanalytics" id="analytics1">
    <script type="application/json">
        {
            "vars": {
                "account": "<?=cx::option('google.analytics.id')?>"
            },
            "triggers": {
                "trackPageview": {
                    "on": "visible",
                    "request": "pageview"
                }
            }
        }
    </script>
</amp-analytics>

</body>
</html>