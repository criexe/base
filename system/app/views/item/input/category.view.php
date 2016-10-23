<div class="input-field col l12">
    <select name="db[category][]" <?=$multiple?>>
        <option value="" disabled selected>Categories</option>
        <?php
        foreach($categories as $cat)
        {
            $selected = in_array($cat['id'], $data) ? 'selected' : null;
            echo "<option value='{$cat['id']}' $selected>{$cat['title']}</option>";
        }
        ?>
    </select>
</div>