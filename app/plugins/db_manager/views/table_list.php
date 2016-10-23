<div class="create-database admin-container">
    <div class="title">
        <div class="pull-left">
            <span class="table_name">Table</span> <span class="color666">List</span>
        </div>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td>Table Name</td>
                <td>Created At</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tables as $item): ?>
                <tr>
                    <td><?= $item['table'] ?></td>
                    <td><?= $item['created_at'] ?></td>
                    <td align="right">

                        <!-- Columns -->
                        <a href="/admin/plugin_page/db_manager/column_list?table=<?=$item['id']?>" class="text-danger" data-toggle="tooltip" data-placement="top" title="Columns">
                            <i class="fa fa-columns"></i>
                        </a>

                        <!-- Edit -->
                        <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i class="fa fa-pencil"></i>
                        </a>

                        <!-- Delete -->
                        <a
                            data-toggle="tooltip" data-placement="top" title="Delete"
                            onclick="return confirm('Are you sure ?')"
                            id="dbDelete"
                            data-id="<?=$item['id']?>"
                            href="/admin/db_delete/db_manager:database?id=<?=$item['id']?>"
                            class="text-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>