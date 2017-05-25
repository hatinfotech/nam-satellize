<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 23/3/2017
 * Time: 12:30 PM
 */
/* @var $this Site_Controller_Main */
?>
<?php if ($this->widget) { ?>
    <ul class="list_link_ft">
        <?php
        $list = json_decode($this->widget[K::Content], true);
        foreach ($list as $item) {
            ?>
            <li style="padding: 3px"><a class="icon_link_ft <?php echo $item[K::Icon]; ?>" style="border: 1px #ccc solid; padding: 2px; background-color: #eee; border-radius: 2px; width: 25px"
                    href="<?php echo $item[K::Link]; ?>" target="<?php echo $item[K::Target]?:'_blank'; ?>">&nbsp;</a>
            </li>
        <?php
        }
        ?>

    </ul>
<?php } ?>