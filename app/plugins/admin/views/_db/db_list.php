<div class="admin-container">

    <div class="title">
        <div class="pull-left">
            <span><?=$display?></span>
            <a href="<?=URL?>/admin/db_insert_form/<?=$table?>" class="btn btn-success"> <i class="fa fa-plus mgr5"></i> Add New </a>
        </div>
        <div class="pull-right">
            <form action="?" method="get">
                <input type="search" name="search" class="form-control" placeholder="Search..." value="<?=input::get('search')?>">
            </form>
        </div>
    </div>

    <?php if( count($datas) ): ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <td>#</td>
                    <?php
//                    foreach($columns as $k => $v)
//                    {
//                        $td_align = array_key_exists('align', $v) ? $v['align'] : 'left';
//
//                        echo "<td align='$td_align'>" . $v['display'] . '</td>';
//                    }
                    $col_opt = [];

                    foreach($columns as $k)
                    {
                        echo "<td>" . $k['display'] . '</td>';
                        $col_opt[$k['column']] = $k['options'];
                    }
                    ?>
                    <td align="right"></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($datas as $data): ?>
                    <tr data-id="<?=$data['id']?>">
                        <td><?= $data['id'] ?></td>
                        <?php
//                        foreach($columns as $k => $v)
//                        {
//                            $td_align = array_key_exists('align', $v) ? $v['align'] : 'left';
//
//                            echo "<td align='$td_align'>" . validator::display($v['validation'], $data[$k]) . '</td>';
//                        }

                        foreach($columns as $k)
                        {
//                            $opt = load::model('db_manager:column')->get(['where' => "column = '{$k['column']}'"]);
                            echo "<td>" . utils::limit_words(validator::display($k['validation'], $data[$k['column']], $col_opt[$k['column']])) . '</td>';
                        }
                        ?>
                        <td align="right" class="text-nowrap table-right-buttons">

                            <!-- Edit -->
                            <a href="<?=URL?>/admin/db_update_form/<?=$table?>?id=<?=$data['id']?>" class="text-warning" data-toggle="tooltip" data-placement="top" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <!-- Clone -->
                            <a href="<?=URL?>/admin/db_insert_form/<?=$table?>?clone=<?=urlencode(json_encode($data))?>" class="text-primary" data-toggle="tooltip" data-placement="top" title="Clone">
                                <i class="fa fa-clone"></i>
                            </a>

                            <!-- Delete -->
                            <a
                                data-toggle="tooltip" data-placement="top" title="Delete"
                                onclick="return confirm('Are you sure ?')"
                                id="dbDelete"
                                data-id="<?=$data['id']?>"
                                href="<?=URL?>/admin/db_delete/<?=$table?>?id=<?=$data['id']?>"
                                class="text-danger">
                                <i class="fa fa-trash"></i>
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">No data.</div>
    <?php endif; ?>

    <nav class="text-right pagination-row">
        <?=html::pagination($total_data, $page_no, 100)?>
    </nav>
</div>