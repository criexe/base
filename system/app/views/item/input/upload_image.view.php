<div class="cx-image-upload-button" id="cont_<?=$tag_id?>">
    <button data-target="#<?=$form_id?>" type="button" data-input="#<?=$tag_id?>" class="btn waves-effect orange darken-4"><i class="material-icons left">add_a_photo</i> Image</button>
    <input type="hidden" id="<?=$tag_id?>" name="<?=$name?>" value="<?=$value?>">
</div>

<?php

$wysiwyg = $wysiwyg != null ? "data-wysiwyg=\"$wysiwyg\"" : null;
$preview = $preview != null ? "data-preview=\"$preview\"" : null;

$modal = '
<form id="' . $form_id . '" action="' . URL . '/helper/upload_image" method="post" enctype="multipart/form-data" class="cx-image-upload-form" data-input="#' . $tag_id . '" ' . $preview . '" ' . $wysiwyg . '>
    <div class="file-field input-field">
        <div class="btn orange darken-4">
            <i class="material-icons left">add_a_photo</i> Image
            <input data-form="#' . $form_id . '" class="cx-image-upload-input" name="image" type="file" accept="image/x-png, image/jpeg, image/gif">
        </div>
    </div>
</form>
';

$modal = utils::compress_html($modal);
?>

<script>$("body").append('<?= $modal ?>')</script>
