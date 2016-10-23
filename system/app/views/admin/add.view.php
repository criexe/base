<div class="container section">

    <h4 class="header light">
        <span class="orange-text text-darken-4">New</span>
        <span class="grey-text text-darken-2"><?=$type_title?></span>
    </h4>

    <div data-add-form>
        <?= $form_content ?>
    </div>

</div>

<script>
    cx.util.convert_to_url("<?=$item_type?>/");
</script>