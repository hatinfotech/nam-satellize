<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:31 AM
 */

/* @var $this Controller */

?><!DOCTYPE HTML>
<html lang="vi-VN">
<!-- Mirrored from www.shippersaigon.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 16 Mar 2017 01:53:03 GMT -->
<head>
    <?php include('Includes/meta.php'); ?>
</head>
<body id="top">
<?php include('Includes/header.php'); ?>
<article>
    <div class="clearfix clearfix-23"></div>
    <div class="container">
        <div class="row_pc">
            <div class="col-md-3 col-sm-3">
                <div class="row">
                    <div class="home_left">
                        <?php include('Includes/left-menu.php'); ?>
                        <div class="clearfix clearfix-13"></div>
                        <?php include('Includes/support.php'); ?>
                    </div>
                    <script type="text/javascript">
                        $(function () {

                            $("li.html").mouseover(function (e) {
                                $(".sub-categorys").css("display", "none");
                                thisId = $(this).attr("id");

                                catid = thisId.substr(3);
                                subcatdiv = "sub_" + catid;

                                if ($("#" + subcatdiv).length > 0) {
                                    var top = $('html').offset().top;
                                    $("#" + subcatdiv).css("display", "block");
                                    //$("#" + subcatdiv).css("top",top);
                                }

                            });
                        });

                    </script>
                </div>
            </div>
            <div class="col-md-7 col-sm-6 col-sm-12">
                <div class="row">
                    <div class="home_mid">
                        <?php $this->renderView(); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-3 col-sm-12">
                <div class="row">
                    <div class="home_right">
                        <h2 class="tit_right"> Giải pháp kỹ thuật </h2>

                        <div class="clearfix"></div>
                        <ul class="list_dvkt">
                            <li>
                                <div class="box_dvkt">
                                    <div class="name_dvkt"><a href="Camera-giam-sat-tu-xa/12/faqtech.html"
                                            target="_blank">CAMERA GIÁM SÁT TỪ XA</a></div>
                                    <div class="clearfix"></div>
                                    <div class="img_dvkt"><a href="Camera-giam-sat-tu-xa/12/faqtech.html"
                                            target="techWindow"
                                            onClick="openTech('Camera-giam-sat-tu-xa/12/faqtech.html')"><img
                                                class="w_100" src="<?php $this->getDefaultTemplatePath(); ?>/tech/12.jpg" border="0" width="140px;"
                                                alt="CAMERA GIÁM SÁT TỪ XA"/></a></div>
                                </div>
                            </li>
                            <l
                        </ul>
                        <div class="clearfix clearfix-13"></div>
                        <div class="clearfix clearfix-13"></div>
                        <h2 class="tit_right pd_tktc"> Thống kê truy cập </h2>

                        <div class="thongke_truycap"> 25.294.218</div>
                    </div>
                </div>
                <script type="text/javascript">
                    function updateFilter(filterId, isMulti, val) {
                        return $("#filterform").submit();
                    }


                </script>
                <!------------------------------------------ ende content right -------------->
            </div>
        </div>
        <div class="clearfix clearfix-20"></div>
        <?php include('Includes/brand.php'); ?>
        <div class="clearfix"></div>
    </div>
    </div>
</article>
<div class="clearfix"></div>
<?php include('Includes/footer.php'); ?>
</body>
<!-- Mirrored from www.shippersaigon.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 16 Mar 2017 01:59:05 GMT -->
</html>
<?php
mysql_free_result($SiteInfo);
?>