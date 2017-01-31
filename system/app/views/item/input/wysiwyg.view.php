<div>
    <div class="z-depth-1">
        <textarea name="<?=$name?>"><?= $value ?></textarea>
    </div>
    <div style="margin-top:10px"><?=form::image('insert_image_' . $name, null, ['wysiwyg' => $name])?></div>
</div>
<script>cx.wysiwyg.editor('<?=$name?>', '<?=$type?>')</script>