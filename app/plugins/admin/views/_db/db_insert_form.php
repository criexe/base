<div class="admin-container">

    <div class="title">
        <div class="pull-left">
            <span><?=$display?></span>
        </div>
        <div class="pull-left">
            <a href="<?=URL?>/admin/db_insert_form/<?=$table?>" class="btn btn-success btn-sm"> <i class="fa fa-plus mgr5"></i> Add New </a>
        </div>
    </div>

    <!-- data-location="/admin/db_list/<?=$table?>" -->
    <form action="<?=URL?>/admin/db_insert/<?=$table?>" method="post" data-ajax-form  enctype="multipart/form-data">
        <?php foreach($columns as $k): ?>
            <label><?= $k['display'] ?></label>
            <div class="input-group">
                <?php
                $params                = [];
                $params['name']        = 'column_' . $k['column'];
                $params['class']       = 'form-control orange-tooltip';
//                $params['placeholder'] = $k['display'];

//                $params['data-toggle']    = 'tooltip';
//                $params['data-placement'] = 'top';
//                $params['title']          = $k['display'];

                if($cloneDatas != null) $params['value'] = $cloneDatas->$k;

                // HTML Input
                echo validator::html_input(
                    $k['validation'],
                    $params,
                    array_key_exists('options', $k) ? $k['options'] : null
                );
                ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" data-loading-text="Wait..." class="btn btn-success mgr5"> <span class="fa fa-plus mgr5"></span> Add</button>
        <button type="reset" class="btn btn-danger"> <span class="fa fa-trash mgr5"></span> Reset</button>
    </form>

</div>