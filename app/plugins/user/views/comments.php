<hr>
<div class="comments">
    <?php foreach($comments as $c): ?>
        <div class="comment-line">
            <a href="#" class="user-img" style="background-image:url(<?= user::profile_image($c['user_id']) ?>);"></a>
            <a class="name-surname"><?= user::name_surname($c['user_id']) ?></a>
            <p class="comment-text">
                <?=$c['comment']?>
            </p>
        </div>
    <?php endforeach; ?>
</div>
