<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 23/3/2017
 * Time: 11:29 AM
 */
/* @var $this Site_Controller_Main */
?>
<ul class="">
    <?php
    foreach ($this->productCate as $productCategory) {
        ?>
        <li id="li_186" class="cat"><a href="/phan-loai-san-pham/<?php echo $productCategory[K::UniqueKey]; ?>.html"
                title="<?php echo $productCategory[K::Name]; ?>"><img
                    class="icon_menu_left"
                    src="img/icon_menu_left.png"
                    alt="C"/><?php echo $productCategory[K::Name]; ?></a></li>
    <?php
    }
    ?>


</ul>