<div class="menu_left">
    <h2 class="tit_left"><a href="index.php" title="Danh mục sản phẩm">Danh mục sản phẩm</a></h2>

    <div class="clearfix"></div>
    <ul class="list_menu_left">
        <?php $productCategories = Common::getProductCategory();
        foreach ($productCategories as $productCategory) {
            ?>
            <li id="li_186" class="cat"><a href="cate.php?code=<?php echo $productCategory[K::Code]; ?>"
                    title="<?php echo $productCategory[K::Name]; ?>"><img
                        class="icon_menu_left"
                        src="img/icon_menu_left.png"
                        alt="Shipper"/><?php echo $productCategory[K::Name]; ?></a></li>
        <?php
        }
        ?>


    </ul>
</div>