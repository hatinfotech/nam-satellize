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
<h1 class="tit_mid" itemprop="name">ĐĂNG KÝ</h1>

<div class="transport-request-form border-box">
    <div class="clearfix clearfix-20"></div>

    <?php if ($this->errorMessage) {
        ?>
        <div class="col-md-12">
            <h3 class="text-danger"><?php echo $this->errorMessage; ?></h3>
        </div>
        <div class="clearfix clearfix-20"></div>
    <?php
    }?>

    <form action="/dang-ky.html" method="post">
        <div class="col-md-12 form-group">
            <label class="control-label">Tên <span
                    style="color:red">(*)</span></label>
            <input name="Name" type="text" class="form-control"
                placeholder="Nhập tên của bạn hoặc tên công ty" value="<?php echo $_POST[K::Name]; ?>" required tabindex="10"/>
        </div>
        <div class="col-md-12 form-group">
            <label class="control-label">Số điện thoại <span
                    style="color:red">(*)</span></label>
            <input name="Phone" type="text" class="form-control"
                placeholder="Dùng cho đăng nhập" value="<?php echo $_POST[K::Phone]; ?>" required tabindex="10"/>
        </div>
        <div class="col-md-12 form-group">
            <label class="control-label">Email</label>
            <input name="Email" type="text" class="form-control"
                placeholder="Chúng tôi sẽ gửi thông tin cho bạn qua email này" value="<?php echo $_POST[K::Email]; ?>" tabindex="10"/>
        </div>
        <div class="col-md-12 form-group">
            <label class="control-label">Địa chỉ</label>
            <input name="Address" type="text" class="form-control"
                placeholder="Đỉa chỉ của bạn hoặc của công ty" value="<?php echo $_POST[K::Address]; ?>" tabindex="10"/>
        </div>
        <div class="col-md-6 form-group">
            <label class="control-label">Mật khẩu <span
                    style="color:red">(*)</span></label>
            <input name="Password" type="password" class="form-control"
                placeholder="Mật khẩu đăng nhập" required tabindex="10"/>
        </div>
        <div class="col-md-6 form-group">
            <label class="control-label">Nhập lại mật khẩu <span
                    style="color:red">(*)</span></label>
            <input name="ReTypePassword" type="password" class="form-control"
                placeholder="Nhập lại mật khẩu cho chính xác" required tabindex="10"/>
        </div>
        <div class="col-md-6 col-xs-6 form-group">
            <img style="width: 100%; height: 105px" src="<?php echo $_SESSION['captcha']['image_src']; ?>"/>
        </div>
        <div class="col-md-6 col-xs-6 form-group">
            <label class="control-label">Mã bảo vệ <span
                    style="color:red">(*)</span></label>
            <input type="text" name="Captcha" class="form-control"
                placeholder="Nhập mã bảo vệ trong hình" tabindex="200"/>
        </div>
        <div class="col-md-6 col-xs-6 form-group">
            <input type="submit" name="Submit" class="btn btn-block btn-success"
                value="ĐĂNG KÝ" tabindex="300"/>
        </div>
        <div class="clearfix"></div>
    </form>
</div>
<script>
</script>