<div class="row">
    <div class="col s12">
        <ul class="tabs grey darken-3">
            <li class="tab col s3"><a class="orange-text" href="#controllers-list">Controllers</a></li>
            <li class="tab col s3"><a class="orange-text" href="#models-list">Models</a></li>
            <li class="tab col s3"><a class="orange-text" href="#views-list">Views</a></li>
        </ul>
    </div>
    <div id="controllers-list" class="col s12">
        <ul class="light">
            <?php foreach($files['controller'] as $c) echo "<li>$c<li>"; ?>
        </ul>
    </div>
    <div id="models-list" class="col s12"><ul class="light">
            <?php foreach($files['model'] as $m) echo "<li>$m<li>"; ?>
        </ul></div>
    <div id="views-list" class="col s12">
        <ul class="light">
            <?php foreach($files['view'] as $v) echo "<li>$v<li>"; ?>
        </ul>
    </div>
</div>