<style>
    .collection{
        margin-top:0 !important;
        border:none !important;
    }
</style>

<div class="section latest-data">
    <div class="container">
        <h4 class="orange-text text-darken-4 header light">Latest <span class="grey-text text-darken-3">Data</span></h4>

        <div class="row">

            <?php foreach($types as $type): ?>

                <?php
                $type_latest = item::latest(['type' => $type['alias'], 'limit' => 5]);
                if(!$type_latest) continue;
                ?>

                <!--card-->
                <div class="col l4 m6 s12">
                    <div class="card">
                        <div class="card-content grey lighten-5">
                            <a href="<?=_ADMIN?>/latest/<?=$type['alias']?>" class="red-text text-darken-4"><?=$type['title']?></a>
                        </div>
                        <div class="divider"></div>
                        <ul class="collection">
                            <?php foreach($type_latest as $data): ?>
                                <li class="collection-item">
                                    <?php
                                    $_text = $data['title'];

                                    if($_text == null && $data['content'] != null) $_text = $data['content'];
                                    $_text = strip_tags($_text);

                                    switch($data['status'])
                                    {
                                        default:
                                            echo '<a class="grey-text text-darken-3" target="_blank" href="' . $data['full_url'] . '">' . $_text . '</a>';
                                            break;
                                    }
                                    ?>
                                    <div>
                                        <small class="grey-text" style="margin-right:10px"><i class="fa fa-eye"></i> <?=number_format($data['views'])?></small>
                                        <small class="grey-text" style="margin-right:10px"><i class="fa fa-user"></i> <?=$data['user']['title'] ?></small>
                                        <small class="grey-text"><i class="fa fa-calendar"></i> <?=$data['created_at']['date']?></small>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <!--card-->
            <?php endforeach; ?>

        </div>

    </div>
</div>

<script>
    $(function(){
        cx.view.column_grid('.latest-data .row', '.latest-data .col')
    });
</script>