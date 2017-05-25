<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:29 AM
 */
?>
<h1 class="tit_mid" itemprop="name">LIÊN HỆ</h1>

<div class="transport-request-form border-box">
    <div class="contact-info">
        <div class="col-md-12">
            <?php echo Config_Parameter::getSiteInfo(K::WEB_CONTACT_INFO); ?>
        </div>
    </div>
    <div class="clearfix clearfix-13"></div>
    <div class="col-md-12">
        <hr/>
    </div>

    <form action="send-ticket-request.php" method="post" enctype="multipart/form-data">
        <div class="col-md-12 form-group">
            <label class="control-label">Tên<span
                    style="color:red">(*)</span></label>
            <input name="FromSenderContactName" type="text" class="form-control"
                placeholder="Nhập tên" required/>
        </div>
        <div class="col-md-12 form-group">
            <label class="control-label">Email<span
                    style="color:red"></span></label>
            <input name="FromSenderContactName" type="text" class="form-control"
                placeholder="Nhập tên người gửi"/>
        </div>
        <div class="col-md-12 form-group">
            <label class="control-label">Điện thoại<span
                    style="color:red"></span></label>
            <input name="FromSenderContactName" type="text" class="form-control"
                placeholder="Nhập tên người gửi"/>
        </div>
        <div class="col-md-12 form-group">
            <label class="control-label">Nội dung liên hệ<span
                    style="color:red">(*)</span></label>
            <textarea name="FromSenderContactName" type="text" class="form-control"
                placeholder="Nhập tên người gửi" required></textarea>
        </div>
        <div class="col-md-12 form-group">
            <input type="submit" name="SendContact" class="btn btn-success"
                value="GỬI LIÊN HỆ"/>
        </div>
        <div class="clearfix"></div>
    </form>
</div>