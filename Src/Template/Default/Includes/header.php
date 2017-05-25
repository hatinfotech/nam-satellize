<?php
/* @var $this Site_Controller_Main */
?>
<header>
    <div class="bg_header_mobile">
        <div class="container">
            <div class="row_pc">
                <div class="  visible-xs visible-sm menu_mb">
                    <button class="nav-toggle">
                        <div class="icon-menu"><span class="line line-1"></span> <span class="line line-2"></span> <span
                                class="line line-3"></span></div>
                    </button>
                    <div class="" style="width: 100%; text-align: center"><a href="/" title="Shipper Sai Gon"><img
                                style="max-height: 60px; max-width: 70%"
                                src="<?php echo Config_Parameter::g(K::uploadPath) . '/' . Common::getSiteInfo(C::WEB_META_LOGO); ?>"
                                alt=""></a></div>
                </div>
            </div>
        </div>
    </div>
    <img style="width:0px; height:0px; display:none;" src="imgs/khuyenmai.png"/>

    <div class="bg_header">
        <div class="container">
            <div class="row_pc">
                <div class="row">
                    <div class="col-lg-650 col-md-7 col-sm-7 col-xs-12">
                        <ul class="list_info_top">
                            <li><img class="icon_info_top" src="img/info1.png"
                                    alt=""/><?php echo $siteInfo['WEB_INFO_ADDRESS']; ?></li>
                            <li><a href="mailto:<?php echo $siteInfo['WEB_INFO_EMAIL']; ?>"><img class="icon_info_top"
                                        src="img/info2.png" alt=""/><?php echo $siteInfo['WEB_INFO_EMAIL']; ?></a></li>
                            <li><img class="icon_info_top" src="img/info3.png"
                                    alt=""/><?php echo $siteInfo['WEB_INFO_PHONE']; ?> </li>
                        </ul>
                    </div>
                    <div class="col-lg-350 col-md-5 col-sm-5 col-xs-12">
                        <div class="pull-right">
                            <?php $this->renderWidget('MENUTOP', C::topMenuWidget); ?>
                            <div class="login_info">
                                <?php if ($_SESSION[K::USER_INFO]) { ?>
                                    Xin chào : <a><?php echo $_SESSION[K::USER_INFO][K::Name]; ?></a> |
                                    <a href="/dang-xuat.html">Đăng xuất</a>
                                <?php
                                } else {
                                    ?><a href="/dang-nhap.html">Đăng nhập</a><?php
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="sticky-header">
        <div class="bg_mid_top">
            <div class="container">
                <div class="row_pc">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 hidden-xs"><a href="/" class="logo_pc"> <img class="w_100"
                                    src="<?php echo Config_Parameter::g(K::uploadPath) . '/' . Common::getSiteInfo(C::WEB_META_LOGO); ?>"
                                    alt=""/> </a></div>
                        <div class="col-md-7 col-sm-6 col-xs-12">
                            <form id='inputform' name='inputform' method='post' onSubmit="return checkSearchForm();"
                                action='http://www.shippersaigon.com/searchresult.html'>
                                <div class="input-group group_search">
                                    <input id="seracharg" name="seracharg" type="text" class="form-control icon_bg"
                                        name="x" style="border-radius: 5px;" placeholder="Tra cứu thông tin">
                  <span class="input-group-btn z_butt_search">
                  <button class="btn btn-default butt_search" type="image" style="border-bottom-right-radius:30px; border-top-right-radius: 30px"></button>
                  </span></div>
                            </form>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?php $this->renderWidget('SOCIALSHARINGHEADER', C::headerSocialWidget); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="bg_menu">
            <div class="container">
                <div class="row_pc">
                    <div class="menu_main">
                        <nav class="nav is-fixed" role="navigation" style="position: relative;z-index: 5;">
                            <div class="wrapper wrapper-flush">
                                <div class="nav-container">
                                    <ul class="nav-menu menu">
                                        <?php
                                        $menu = Common::getMenu();
                                        foreach ($menu as $item) {
                                            ?>
                                            <li class="menu-item"><a class="menu-link"
                                                    id="<?php echo $item[K::Code]; ?>"
                                                    href="<?php echo $item[K::Link]; ?>"
                                                    alt="<?php echo $item[K::Name]; ?>"><?php echo $item[K::Name]; ?></a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="slider_full">
        <div id="slider1_container"
            style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1350px; height: 374px; overflow: hidden;">
            <div u="slides"
                style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1350px;  height: 374px; overflow: hidden;">
                <?php $banner = Common::getBanner('BANNER');
                foreach ($banner[K::Details] as $item) {
                    ?>
                    <div><a href="#"><img u="image"
                                src="<?php echo Config_Parameter::g(K::uploadPath) . '/' . $item[K::Image];; ?>"/></a>
                    </div>
                <?php
                }

                ?>
                <!--                <div><a href="#"><img u="image" src="img/headerbanner/cc.jpg"/></a></div>-->
                <!--                <div><a href="#"><img u="image" src="img/headerbanner/ha1.jpg"/></a></div>-->
                <!--                <div><a href="#"><img u="image" src="img/headerbanner/ha3.jpg"/></a></div>-->
                <!--                <div><a href="#"><img u="image" src="img/headerbanner/ha4.jpg"/></a></div>-->
                <!--                <div><a href="#"><img u="image" src="img/headerbanner/ha5.jpg"/></a></div>-->
                <!--                <div><a href="#"><img u="image" src="img/headerbanner/ha7.jpg"/></a></div>-->
                <!--<div><a href="#"><img u="image" src="img/headerbanner/ha8.jpg"/></a></div>-->
                <!--<div><a href="#"><img u="image" src="img/headerbanner/ha9.jpg"/></a></div>-->
            </div>
            <div u="navigator" class="jssorb21" style="position: absolute; bottom: 26px; left: 6px;">
                <div u="prototype"
                    style="POSITION: absolute; WIDTH: 19px; HEIGHT: 19px; text-align:center; line-height:19px; color:White; font-size:12px;"></div>
            </div>
            <span u="arrowleft" class="jssora21l" style="width: 55px; height: 55px; top: 10px; left: 0px;"> </span>
            <span u="arrowright" class="jssora21r" style="width: 55px; height: 55px; top: 100px; right: 0px;"> </span>
        </div>
    </div>
</header>