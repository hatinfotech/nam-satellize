<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 08:43 PM
 */
/** @var Site_Controller_Main $this */
?>

<h2 class="tit_mid">GIỎ HÀNG</h2>

<div class="transport-request-form border-box">
    <div style="padding: 5px; font-size: 14px; padding-bottom: 25px;">
        <div class="clearfix clearfix-20"></div>
        <div class="striped-table">
            <table class="border-box col-sm-12 table-bordered table-striped table-condensed cf ticket-list">
                <thead class="cf">
                <tr>
                    <th>Sản phẩm</th>
                    <th style="text-align: right">Giá</th>
                    <th style="text-align: right">Số lượng</th>
                    <th style="text-align: right">Thành tiền</th>
                    <th style="text-align: right">Xóa</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION[K::cart][K::productList] as $product) {
                    $toMoney = $product[K::Price] * $product[K::Quantity];
                    $total += $toMoney;
                    ?>
                    <tr>
                        <td class="" data-title="Sẩn phẩm"><?php echo $product[K::Name]; ?></td>
                        <td class="" data-title="Giá" style="white-space: nowrap; text-align: right">
                            <?php echo number_format($product[K::Price]) . 'đ'; ?>
                        </td>
                        <td class="" data-title="Số lượng" style="text-align: right"><?php echo $product[K::Quantity]; ?></td>
                        <td class="" data-title="Thành tiền" style="text-align: right"><?php echo number_format($toMoney) . 'đ'; ?></td>
                        <td class="" data-title="Xóa" style="text-align: right">
                            <a class="btn btn-danger" href="/site/cart/removeProduct?product=<?php echo $product[K::Code]; ?>" role="button"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="price-summary text-danger" style="font-weight: bold">
                    <td data-title="" class="price-label">&nbsp;</td>
                    <td data-title="" class="price-description">&nbsp;</td>
                    <td data-title="" class="price-sum"><span>Tổng cộng</span></td>
                    <td data-title="Tổng cộng" style="white-space: nowrap; text-align: right" class="price-cost"><?php echo number_format($total) . 'đ'; ?></td>
                    <td data-title="" style="text-align: right">
                        <a class="btn btn-danger" href="/site/cart/destroy" role="button"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="clearfix-5"></div>
        <a href="/" class="btn btn-primary"><i class="fa fa-cart-plus"></i> Mua thêm hàng</a>

        <div class="clearfix-5"></div>

        <form action="/site/cart/order" method="post" enctype="multipart/form-data">
            <div class="row">
                <hr style="margin-left: 15px;  margin-right:15px;"/>

                <div class="col-md-12 form-group">
                    <label class="control-label">Tên <span
                            style="color:red">(*)</span></label>
                    <input name="Name" type="text" class="form-control"
                        placeholder="Nhập tên của bạn" value="<?php echo $_SESSION[K::USER_INFO][K::Name]; ?>" required tabindex="10"/>
                </div>
                <div class="col-md-4 form-group">
                    <label class="control-label">Số điện thoại <span
                            style="color:red">(*)</span></label>
                    <input name="Phone" value="<?php echo $_SESSION[K::USER_INFO][K::Phone]; ?>" type="text" class="form-control"
                        placeholder="Nhập SĐT của bạn" required tabindex="20"/>
                </div>
                <div class="col-md-8 form-group">
                    <label class="control-label">Email</label>
                    <input name="Email" value="<?php echo $_SESSION[K::USER_INFO][K::Phone]; ?>" type="text" class="form-control"
                        placeholder="Nhập Email của bạn" tabindex="20"/>
                </div>

                <div class="col-md-4 form-group">
                    <label class="control-label">Tỉnh/Thành phố</label>
                    <select name="Province" type="time" class="form-control" tabindex="40">
                        <option class="label">Chọn Tỉnh/Thành phố</option>
                        <?php foreach ($provinceList as $province) {
                            ?>
                            <option value="<?php echo $province[K::Code]; ?>"><?php echo $province[K::FullName]; ?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label class="control-label">Quận/Huyện</label>
                    <select name="District" type="time" class="form-control" tabindex="50">
                        <option class="label">Chọn Quận/Huyện</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label class="control-label">Phường/Xã</label>
                    <select name="Village" type="time" class="form-control" tabindex="60">
                        <option class="label">Phường/Xã</option>
                    </select>
                </div>

                <datalist id="address-history">
                    <!--<option value="HTML">-->
                </datalist>

                <div class="col-md-12 form-group">
                    <label class="control-label">Địa chỉ nhận hàng <span
                            style="color:red">(*)</span></label>
                    <input name="Address" type="text" value="<?php echo $_SESSION[K::USER_INFO][K::Address]; ?>" class="form-control"
                        placeholder="chỉ ghi số nhà, tên đường, vd : 230 trường chinh" required tabindex="70" list="address-history"/>
                </div>
                <div class="clearfix"></div>
                <hr style="margin-left: 15px;  margin-right:15px;"/>


                <div class="col-md-12 form-group">
                    <button type="submit" name="SendTicketRequest" class="btn btn-success" tabindex="190"><i class="fa fa-credit-card"></i> ĐẶT HÀNG</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
</div>