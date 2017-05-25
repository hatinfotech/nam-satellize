<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:29 AM
 * @var $this Site_Controller_Admin
 */
$provinceList = Common::getLocationsByParent("VIETNA0001");
?>
<h1 class="tit_mid" itemprop="name">ĐĂNG NHẬP</h1>

<div class="transport-request-form border-box">
    <div class="clearfix clearfix-20"></div>

    <?php if ($this->requireLoginMessage) { ?>
        <div class="col-md-12 text-danger">
            <div class="h2"><?php echo $this->requireLoginMessage; ?></div>
        </div>
        <div class="clearfix clearfix-20"></div>
    <?php } ?>

    <form action="/Site/Admin/login" method="post">
        <div class="col-md-12 form-group">
            <label class="control-label">Username<span
                    style="color:red">(*)</span></label>
            <input name="Username" type="text" class="form-control"
                placeholder="Số điện thoại của bạn" required tabindex="10"/>
        </div>
        <div class="col-md-12 form-group">
            <label class="control-label">Password<span
                    style="color:red">(*)</span></label>
            <input name="Password" type="password" class="form-control"
                placeholder="Nhập password của bạn" required tabindex="20"/>
        </div>
        <div class="col-md-12 form-group">
            <input type="submit" name="Submit" class="btn btn-success"
                value="ĐĂNG NHẬP" tabindex="190"/>
        </div>
        <div class="clearfix"></div>
    </form>
</div>
<script>
</script>