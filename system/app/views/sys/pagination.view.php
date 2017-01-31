<ul class="pagination <?=$container_class?>">
    <?php

//    if((int)$active > 1) echo '<li class="disabled"><a href="' . URL . '"><i class="material-icons">chevron_left</i></a></li>';
    for($i = 1; $i <= $total_page; $i++)
    {
        $_active_class = null;
        if((int)$active == $i) $_active_class = 'active'; else $_active_class = 'waves-effect';

        echo "<li class='$_active_class'><a class='$passive_class' href='" . url::set_parameter($var, $i) . "'>$i</a></li>";
    }
//    if((int)$total > (int)$active) echo '<li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>'

    ?>
</ul>