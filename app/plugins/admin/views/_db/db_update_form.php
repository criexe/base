<div class="admin-container">

    <div class="title">
        <div class="pull-left">
            <span><?=$display?></span>
        </div>
        <div class="pull-left">
            <a href="<?=URL?>/admin/db_insert_form/<?=$table?>" class="btn btn-success btn-sm"> <i class="fa fa-plus mgr5"></i> Add New </a>
        </div>
    </div>

    <?php
    $col_opt = [];

    foreach($columns as $k) $col_opt[$k['column']] = $k['options'];
    ?>

    <form action="<?=URL?>/admin/db_update/<?=$table?>?id=<?=$id?>" method="post" data-ajax-form >
        <?php foreach($columns as $k): ?>
            <label><?= $k['display'] ?></label>
            <div class="input-group">
                <?php
                $params                = [];
                $params['name']        = 'column_' . $k['column'];
                $params['value']       = $datas[$k['column']];
                $params['class']       = 'form-control orange-tooltip';
                //                    $params['placeholder'] = $k['display'];

                //                    $params['data-toggle']    = 'tooltip';
                //                    $params['data-placement'] = 'top';
                //                    $params['title']          = $k['display'];

                // HTML Input
                echo validator::html_input(
                    $k['validation'],
                    $params,
                    array_key_exists('options', $k) ? $k['options'] : null
                );
                ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" data-loading-text="Wait..." class="btn btn-success mgr5"> <span class="fa fa-pencil mgr5"></span> Update</button>
        <button type="reset" class="btn btn-danger"> <span class="fa fa-trash mgr5"></span> Reset</button>
    </form>


</div>