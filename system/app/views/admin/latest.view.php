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
            <?=item::dblist($params)?>
            </tbody>
        </table>
    </div>
</div>