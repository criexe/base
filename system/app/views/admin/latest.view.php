<div class="container section">
    <div class="responsive-table">
        <table class="bordered striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?= _render('item/list', ['datas' => $datas, 'params' => $params]) ?>
            </tbody>
        </table>
    </div>

    <?php echo
    _pagination([

        'total'  => $count,
        'limit'  => $limit,
        'active' => input::get('p', ['empty' => 1]),
        'var'    => 'p',

        'active_class' => 'orange darken-4',
        'container_class' => null,
        'passive_class' => null
    ])
    ?>
</div>
