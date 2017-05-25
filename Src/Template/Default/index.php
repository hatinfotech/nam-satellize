<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:31 AM
 */

/* @var $this Site_Controller_Main */

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
                        <!--                        --><?php //include('Includes/left-menu.php'); ?>
                        <?php $this->renderWidget('PRODUCTCATE'); ?>
                        <div class="clearfix clearfix-13"></div>
                        <div class="addleftblocks">
                            <?php $this->renderWidget('BUSSUPPORT'); ?>
                            <?php $this->renderWidget('TECHSUPPORT'); ?>
                            <?php $this->renderWidget('CUSTOMERCARE'); ?>
                        </div>
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
                        <?php
                        try {
                            $this->renderView();
                        } catch (Exception $e) {
                            ?>
                            <h2 class="tit_mid">THÔNG BÁO</h2>

                            <div class="transport-request-form border-box">
                                <div style="padding: 5px; font-size: 24px">
                                    <div class="clearfix clearfix-20"></div>
                                    <?php echo $e->getMessage(); ?>
                                    <br/>
                                </div>
                            </div>
                        <?php
                        } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-3 col-sm-12">
                <div class="row">
                    <div class="home_right">
                        <?php if (!$_SESSION[K::USER_INFO]) {
                            $this->renderWidget('SOLOUTION');
                        } else {
                            $this->renderWidget('ADMINCATE');
                        }
                        ?>
                        <?php $this->renderWidget('ACCESSCOUNT'); ?>
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
</html>
<?php
mysql_free_result($SiteInfo);
?>