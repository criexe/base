<div class="navbar-fixed">
    <nav>
        <div class="nav-wrapper red darken-4">
            <a href="<?= URL . '/' . $data['url'] ?>" class="brand-logo center"><?=$data['title']?></a>
        </div>
    </nav>
</div>

<div class="container section">

    <h1 class="flow-text"></h1>

    <ul class="collapsible popout" data-collapsible="accordion">
        <?php foreach($data as $k => $v): ?>

            <?php if($v == null) continue; ?>

            <li>
                <div class="collapsible-header"><?=$k?></div>
                <div class="collapsible-body flow-text">
                    <p class="truncate">
                        <?php
                        if(is_array($v))
                        {
                            echo nl2br(json::encode($v, ['pretty' => true]));
                        }
                        else if(json::valid($v))
                        {
                            echo nl2br(json::encode(json::decode($v), ['pretty' => true]));
                        }
                        else
                        {
                            echo "$v";
                        }
                        ?>
                    </p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

</div>