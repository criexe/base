<?='<?xml version="1.0"?>' . "\n"?>
<rss version="2.0">
    <channel>

        <title><?= $title ?></title>
        <link><?= cx::option('app.url') ?></link>
        <description><?= cx::option('app.description') ?></description>
        <pubDate><?= date('r', $items[0]['created_at']['time']) ?></pubDate>
        <lastBuildDate><?= date('r', $items[0]['created_at']['time']) ?></lastBuildDate>
        <generator>CX.RSS.Generator</generator>
        <webMaster>info@criexe.com (Criexe)</webMaster>

        <?php
        foreach($items as $item):
        if($item['title']       == null) continue;
        if($item['url']         == null) continue;
        if($item['description'] == null) continue;
        ?>

        <item>
            <title><![CDATA[<?=$item['title']?>]]></title>
            <link><?=$item['full_url']?></link>
            <description><![CDATA[<strong><?=$item['description']?></strong>]]></description>
            <?php if($item['user'] != null && $item['user']['email'] != null && $item['user']['title'] != null): ?>
            <author><![CDATA[<?=$item['user']['email']?> (<?=$item['user']['title']?>)]]></author>
            <?php endif; ?>
            <?php if($item['image'] != null): ?>
            <media:content url="<?=$item['image_url']?>" type="image/png" medium="image" expression="sample"/>
            <?php endif; ?>
            <guid><?=$item['full_url']?></guid>
            <pubDate><?=date('r', $item['created_at']['time'])?></pubDate>
        </item>
        <?php endforeach; ?>

    </channel>
</rss>