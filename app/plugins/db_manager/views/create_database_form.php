<div class="create-database admin-container">

    <div class="title">
        <div class="pull-left">
            <span>Create New Table</span>
        </div>
    </div>

    <div class="form-container">
        <form action="#" method="post">
            <div class="input-group">
                <label for="">Display Name</label>
                <input name="db-display-name" type="text" class="form-control" placeholder="Display Name">
            </div>
            <div class="input-group">
                <label for="">Table Name</label>
                <input name="db-table-name" type="text" class="form-control" placeholder="Table Name">
            </div>

            <button type="submit" class="btn btn-success"> <i class="fa fa-plus mgr5"></i> Create </button>
        </form>
    </div>

</div>

<script>

        $("body").off("submit", "form").on("submit", "form", function(e){

            e.preventDefault();

            var $data = $(this).serialize();

            $.post("/db_manager/create_table", $data).done(function(data){

                alert(data);
            });

        });

</script>