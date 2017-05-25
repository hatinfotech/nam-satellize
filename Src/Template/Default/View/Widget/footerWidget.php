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
    <div class="col-md-3 col-sm-3 col-xs-6 col-480-12">
        <div class="tit_footer"><?php echo $this->widget[K::Title]; ?></div>
        <div class="clearfix">&nbsp;</div>
        <?php
        switch ($this->widget[K::Type]) {
            case 'HeaderSocialWidget':
            case 'ListBodyWidget':
            case 'ListImageWidget':

                $list = json_decode($this->widget[K::Content], true);
                ?>
                <ul class="menu_ft">
                    <?php
                    foreach ($list as $item) {
                        ?>
                        <li><a href="<?php echo $item[K::Link]; ?>" target="<?php echo $item[K::Target]; ?>"><?php echo $item[K::Title]; ?></a></li>
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
                            <a class="icon_link_ft <?php echo $item[K::Icon]; ?>" target="<?php echo $item[K::Target]; ?>" target="ex-fb"
                                href="<?php echo $item[K::Link]; ?>"></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                break;
            default:
                echo $this->widget[K::Content];
                break;
        }
        ?>
        <div class="clearfix">&nbsp;</div>
    </div>
<?php } ?>
