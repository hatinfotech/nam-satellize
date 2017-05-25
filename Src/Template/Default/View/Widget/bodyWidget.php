<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 23/3/2017
 * Time: 10:24 AM
 */
/* @var $this Site_Controller_Main */
if ($this->widget) {
    ?>
    <h2 class="tit_left"><?php echo $this->widget[K::Title]; ?></h2>
    <div class="box_content list_menu_left">

        <?php
        if (!function_exists('renderProductCate')) {
            function renderProductCate($tree, $level = 1, $hidden = false) {
                $isHidden = $level > 1 && $hidden;
                $isChildrenShow = $level > 1 && !$hidden;
                ?>
                <ul parent="<?php echo $tree[K::Code] ?: 'ROOT'; ?>" style="<?php echo $level > 1 ? 'padding-left:20px;' : ''; ?><?php //echo $isHidden ? '; display:none' : ''; ?><?php echo $level > 1 ? 'display:none' : ''; ?>">
                    <?php
                    foreach ($tree[K::branches] as $productCategory) {
                        ?>
                        <li id="li_186" class="cat" uniqueId="<?php echo $productCategory[K::UniqueKey]; ?>">
                            <a href="/phan-loai-san-pham/<?php echo $productCategory[K::UniqueKey]; ?>.html" target="<?php echo $productCategory[K::Target]; ?>"
                                title="<?php echo $productCategory[K::Name]; ?>"><img
                                    class="icon_menu_left"
                                    src="img/icon_menu_left.png"
                                    alt="Shipper"/><?php echo $productCategory[K::Name]; ?></a>
                            <?php if (count($productCategory[K::branches]) > 0) {
                                $isChildrenShow = renderProductCate($productCategory, $level + 1, $isChildrenShow ? false : (Bootstrap::g()->getRequestParams('uniqueKey') != $productCategory[K::UniqueKey]));
                            } else {
                                $isChildrenShow = (Bootstrap::g()->getRequestParams('uniqueKey') == $productCategory[K::UniqueKey]) ?: $isChildrenShow;
                            }?>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            <?php
            if ($level == 1){
            ?>
                <script>
                    function expandSelectedCategory(root, selectedUniqueKey) {
                        var isChildrenSelected = false;
                        root.find('>li').each(function () {
                            var item = $(this);
                            var children = item.find('>ul');
                            if (selectedUniqueKey == item.attr('uniqueId')) {
                                children.show();
                                isChildrenSelected = true;
                            }
                            if (expandSelectedCategory(children, selectedUniqueKey)) {
                                children.show();
                                isChildrenSelected = true;
                            }
                        });
                        return isChildrenSelected;
                    }
                    expandSelectedCategory($('ul[parent="ROOT"]'), '<?php echo Bootstrap::g()->getRequestParams('uniqueKey'); ?>');
                </script>
            <?php
            }
                return $isChildrenShow;
            }
        }

        switch ($this->widget[K::Type]) {
            case 'ListImageWidget':
                $list = json_decode($this->widget[K::Content], true);
                ?>
                <ul class="xxxx">
                    <?php
                    foreach ($list as $item) {
                        ?>
                        <li><a href="<?php echo $item[K::Link]; ?>" target="<?php echo $item[K::Target]; ?>"><img
                                    src="<?php echo $item[K::Icon]; ?>"/> <?php echo $item[K::Title]; ?></a></li>
                    <?php
                    }
                    ?>
                </ul>
                <?php

                break;
            case 'ListBodyWidget':
                $list = json_decode($this->widget[K::Content], true);
                ?>
                <ul>
                    <?php
                    foreach ($list as $item) {
                        ?>
                        <li><a href="<?php echo $item[K::Link]; ?>"><?php echo $item[K::Title]; ?></a></li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                break;
            case 'FooterSocialWidget':
                $list = json_decode($this->widget[K::Content], true);
                ?>
                <ul class="list_link_ft">
                    <?php foreach ($list as $item) {
                        ?>
                        <li>
                            <a class="icon_link_ft c" target="ex-fb" target="<?php echo $item[K::Target]; ?>"
                                href="<?php echo $item[K::Link]; ?>"></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                break;
            case 'HeaderSocialWidget':
                ?>
                <ul class="list_link_top pull-right">
                    <?php foreach ($list as $item) {
                        ?>
                        <li>
                            <a target="ex-fb" href="<?php echo $item[K::Link]; ?>" target="<?php echo $item[K::Target]; ?>"
                                class="fa <?php echo $item[K::Icon]; ?> bg_3b5998 icon_link_top"></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                break;
            case 'ProductCategoryWidget':
                renderProductCate($this->productCate);
                ?>
                <!--<ul class="">-->
                <!--    --><?php
                //    foreach ($this->productCate[K::branches] as $productCategory) {
                //
                ?>
                <!--        <li id="li_186" class="cat"><a href="/phan-loai-san-pham/--><?php //echo $productCategory[K::UniqueKey]; ?><!--.html" target="--><?php //echo $item[K::Target]; ?><!--"-->
                <!--                title="--><?php //echo $productCategory[K::Name]; ?><!--"><img-->
                <!--                    class="icon_menu_left"-->
                <!--                    src="img/icon_menu_left.png"-->
                <!--                    alt="Shipper"/>--><?php //echo $productCategory[K::Name]; ?><!--</a></li>-->
                <!--    --><?php
                //    }
                //
                ?>
                <!---->
                <!---->
                <!--</ul>-->
                <?php
                break;
            default:
                echo $this->widget[K::Content];
                break;
        }
        ?>

        <div class="clearfix clearfix-10">&nbsp;</div>
    </div>
    <div class="clearfix clearfix-13">&nbsp;</div>
<?php } ?>
