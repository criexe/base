    <?php

    if(!$datas) $datas = [];

    $types = cx::type();

    $list_counter = 0;
    foreach($datas as $data): ?>

        <?php
        if($data['title'] == null && $data['parent'] == null) continue;
        if(user::allowed($data['type'], 'list') == false) continue;

        if(is_array($data['parent']) && $data['parent']['title'] != null) $data['title'] = $data['parent']['title'];
        ?>

        <tr data-item="<?=$data['id']?>">
            <td class="grey-text"><?=$data['id']?></td>
            <td>
                <a class="grey-text text-darken-2" href="<?=ADMIN_URL?>/latest/<?=$data['type']?>"><?=$types[$data['type']]['title']?></a>
            </td>

            <!-- Title -->
            <td>
                <a class="red-text text-darken-4" href="<?=ADMIN_URL . "/edit/{$data['id']}"?>"><strong><?=$data['title']?></strong></a>
            </td>
            <!-- Title -->

            <td><a class="grey-text text-darken-1" href="<?=array_key_exists('full_url', $data) ? $data['full_url'] : null ?>" target="_blank"><?=$data['url']?></a></td>

            <td>
                <select name="status" data-id="<?=$data['id']?>" data-change-post-status>
                    <option value="active" <?= $data['status'] == 'active'  ? 'selected' : null ?>>Active</option>
                    <option value="passive"<?= $data['status'] == 'passive' ? 'selected' : null ?>>Passive</option>
                    <option value="waiting"<?= $data['status'] == 'waiting' ? 'selected' : null ?>>Waiting</option>
                </select>
            </td>

            <!-- Actions -->
            <td class="right-align">
                <a href="#" class="dropdown-button btn waves-effect orange darken-4" data-constrainwidth="false" data-activates="item-actions-<?=$data['id']?>"> <i class="material-icons tiny">settings</i> </a>

                <ul id='item-actions-<?=$data['id']?>' class='dropdown-content'>
                    <li>
                        <?php if(user::allowed($data['type'], 'update') == true): ?>
                            <a href="<?=ADMIN_URL . "/edit/{$data['id']}"?>" class="orange-text text-darken-4">
                                <i class="material-icons left tiny">mode_edit</i>
                                <span>Edit</span>
                            </a>
                        <?php endif; ?>

                    </li>
                    <li class="divider"></li>
                    <li class="grey lighten-4">
                        <?php if(user::allowed($data['type'], 'delete') == true): ?>
                            <a data-delete-item-button data-id="<?=$data['id']?>" href="<?=ADMIN_URL?>/delete?id=<?=$data['id']?>" class="red-text text-darken-4">
                                <i class="material-icons left tiny">delete</i>
                                <span>Delete</span>
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </td>
            <!-- Actions -->
        </tr>

    <?php $list_counter++; endforeach; ?>


<?php if($list_counter <= 0): ?>
    <div class="section center">
        <i class="material-icons grey-text text-lighten-2" style="font-size:5vw">warning</i>
        <h4 class="header light red-text text-darken-3">No data.</h4>
    </div>
<?php endif; ?>