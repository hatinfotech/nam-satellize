<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:29 AM
 */
/* @var $this Site_Controller_Admin */
?>
<h1 class="tit_mid" itemprop="name">THÔNG TIN VẬN ĐƠN</h1>

<div class="transport-request-form border-box">
<div class="clearfix clearfix-20"></div>

<style>


    .print-title {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .print-title.left {
        text-align: left;
    }

    .print-identity {
        font-style: italic;
        text-align: center;
    }

    .print-break {
        clear: both;
    }

    .print-combo {
        /*line-height: 22px;*/
    }

    .print-label {
        float: left;
        margin-right: 5px;
    }

    .print-text {
        float: left;
        clear: right;
    }

    .print-combo.print-combo-align-right .print-label {
        float: left;
    }

    .print-combo.print-combo-align-right .print-text {
        float: right;
    }

    .print-logo-image {
        float: right;
        max-height: 50px;
        cursor: pointer;
    }

    .print-detail-col {

    }

    .print-detail-col-right {
        text-align: right;
    }

    .print-detail-footer {
        font-weight: bold;
        /*text-transform: uppercase;*/
        padding-top: 5px;
    }

    .print-detail-header {
        font-weight: bold;
        /*text-transform: uppercase;*/
    }

    .print-detail-title {
        font-weight: bold;
        text-transform: uppercase;
    }

    .print-company-info, .print-logo {
    }

    .lLabel {
        display: block;
    }

    .print-detail-col {
    }

    .print-detail-line {
        border-bottom: 1px solid #ccc;
        /*border-bottom: none;*/
        clear: both;
    }

    @media (min-width: 530px) {
        .lLabel {
            display: none;
        }
    }

    @media (max-width: 530px) {
        .print-company-info {
            text-align: center;
        }

        .print-logo {
            text-align: center;
        }

        .print-logo-image {
            float: none;
        }
    }

</style>
<div class="container-fluid">
<div class="row print-header">
    <!--        <div class="col-xs-7 print-company-info">-->
    <!--            CÔNG TY TNHH THƯƠNG MẠI DỊCH VỤ MTSG ®<br>-->
    <!--            Chuyên Cấp Dịch Vụ Hỗ Trợ Mua Bán, Vận Chuyển<br>-->
    <!--            Tầng trệt, tòa nhà Rosana 60 Nguyễn Đình Chiểu, P. Đakao, Q.1, TP.HCM-->
    <!--        </div>-->
    <div class="col-xs-7 print-company-info">
        ...</div>
    <div class="col-xs-5 print-logo">
        <img class="print-logo-image" src="<?php echo Config_Parameter::g(K::uploadPath) . '/' . Common::getSiteInfo(C::WEB_META_LOGO); ?>" border="0">
    </div>
</div>
<script>
</script>
<div class="row print-body">
<div class="col-xs-12">

<style>
    .print-info {
        text-align: left;
    }

    .print-title {
        text-align: left;
    }

    .print-identity {
        text-align: left;
    }

    .barcode {
        text-align: right;
    }
</style>
<div class="row">
    <div class="col-sm-6">
        <div class="print-info" style="text-align: left">
            <div class="print-title">Vận Đơn</div>
            <div class="print-identity"><?php echo $this->ticket[K::DateOfCreate]; ?></div>
        </div>
    </div>
    <div class="col-sm-6" style="text-align: right">
        <div class="barcode">
            <img style="width: 50%" src="<?php echo $this->ticket['Barcode']; ?>">
            <br><?php echo $this->ticket[K::Code]; ?></div>
    </div>
</div>


<div class="row print-partner-info">
    <!--Id Combo-->
    <div class="col-md-6 col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <h4 style="font-weight: bold !important; font-size: 15px"><i class="fa fa-paper-plane"></i> Người Gửi
                </h4>
            </div>
        </div>
        <div class="row">

            <!--FromSenderContactName Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Tên :</div>
                    <div class="print-text"><?php echo $this->ticket[K::FromSenderContactName]; ?></div>
                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>

            <!--FromSenderContactPhone Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Điện thoại :</div>
                    <div class="print-text"><?php echo $this->ticket[K::FromSenderContactPhone]; ?></div>
                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>

            <!--DateOfRequest Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Ngày Yêu Cầu :</div>
                    <div class="print-text"><?php echo $this->ticket[K::DateOfRequest]; ?></div>

                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>

            <!--TimeOfRequest Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Giờ Yêu Cầu :</div>
                    <div class="print-text"><?php echo $this->ticket[K::TimeOfRequest]; ?></div>
                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>
            <!--ShipFrom Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Ship From :</div>
                    <div class="print-text"><?php echo $this->ticket[K::ShipFrom]; ?></div>
                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>


            <!--ShipFrom Combo-->
            <b>
                <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                    <div class="print-combo print-combo-align-right">
                        <div class="print-label">Cộng Thanh Toán :</div>
                        <div class="print-text"><?php echo $this->ticket[K::ShipCost]; ?>đ</div>
                        <div class="print-break"></div>
                    </div>
                </div>
                <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="print-detail-line"></div>
                </div>
            </b>

        </div>
    </div>
    <div class="col-md-6 col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <h4 style="font-weight: bold !important; font-size: 15px"><i class="fa fa-user-circle"></i> Người nhận
                </h4>
            </div>
        </div>
        <div class="row">


            <!--ReceiverName Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Tên :</div>
                    <div class="print-text"><?php echo $this->ticket[K::ReceiverName]; ?></div>
                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>

            <!--ReceiverPhone Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Điện thoại :</div>
                    <div class="print-text"><?php echo $this->ticket[K::ReceiverPhone]; ?></div>
                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>

            <!--DateOfReceive Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Ngày Nhận :</div>
                    <div class="print-text"><?php echo $this->ticket[K::DateOfReceive]; ?></div>
                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>

            <!--TimeOfReceive Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Giờ Nhận :</div>
                    <div class="print-text"><?php echo $this->ticket[K::TimeOfReceive]; ?></div>
                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>

            <!--ShipTo Combo-->
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                <div class="print-combo print-combo-align-right">
                    <div class="print-label">Chuyển Đến :</div>
                    <div class="print-text"><?php echo $this->ticket[K::ShipTo]; ?></div>
                    <div class="print-break"></div>
                </div>
            </div>
            <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="print-detail-line"></div>
            </div>

            <!---->
            <!--    <!--ShipFrom Combo-->
            <!--    --><!---->


            <b>
                <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                    <div class="print-combo print-combo-align-right">
                        <div class="print-label">Cộng Thanh Toán :</div>
                        <div class="print-text">0 đ</div>
                        <div class="print-break"></div>
                    </div>
                </div>
                <div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="print-detail-line"></div>
                </div>
            </b>

        </div>
    </div>

</div>
</div>


<div class="col-md-12">
    <div class="row print-footer">
        <br>

        <div class="col-xs-6 print-footer-partition">
            <div class="text-center">Xác nhận</div>
            <br>
            <br>
            <br>
            <br>

            <div class="text-center">_ _ _ _ _ _ _ _ _</div>
        </div>

        <div class="col-xs-6 print-footer-partition">
            <div class="text-center">Xác nhận</div>
            <br>
            <br>
            <br>
            <br>

            <div class="text-center">_ _ _ _ _ _ _ _ _</div>
        </div>
    </div>
</div>
</div>
</div>


<div class="clearfix clearfix-20"></div>
<a class="btn btn-success btn-block" role="button" href="/danh-sach-van-don/page-<?php echo $this->prevTicketListPage?>.html">Trở về danh sách vận đơn</a>

<div class="clearfix clearfix-20"></div>

</div>
<script>

</script>