<div class="input-field col l12">
    <select name="db[category][]" <?=$multiple?>>
        <option value="" disabled selected>Select a category.</option>
        <?php foreach($categories as $topcat): if(is_array($topcat['category'])) continue; ?>

            <?php if($top == true):

                $selected = in_array($topcat['id'], $data) ? 'selected' : null;
                echo "<option value='{$topcat['id']}' $selected>{$topcat['title']}</option>";

            else: ?>

                <optgroup label="<?=$topcat['title']?>">
                    <?php
                    foreach($categories as $cat)
                    {
                        if(!in_array($topcat['id'], $cat['category'])) continue;

                        $selected = in_array($cat['id'], $data) ? 'selected' : null;
                        echo "<option value='{$cat['id']}' $selected>{$cat['title']}</option>";
                    }
                    ?>
                </optgroup>

            <?php endif; ?>

        <?php endforeach; ?>
    </select>
    <label>Category</label>
</div>