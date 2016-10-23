<div class="section">
    <h4 class="header thin orange-text">
        <?=$header?>
    </h4>
    <div class="divider grey darken-3"></div>
    <pre class=""><?= json::encode($data, ['pretty' => true]) ?></pre>
</div>