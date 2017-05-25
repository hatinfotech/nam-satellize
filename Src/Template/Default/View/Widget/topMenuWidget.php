<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 23/3/2017
 * Time: 06:00 PM
 */
?>
<?php
$list = json_decode($this->widget[K::Content], true);
?>
<ul class="list_menu_top">
    <?php foreach ($list as $item) {
        ?>
        <li><a id="uberuns" href="<?php echo $item[K::Link]; ?>" alt="<?php echo $item[K::Title]; ?>"><?php echo $item[K::Title]; ?></a>
        </li>
    <?php
    }
    ?>
</ul>