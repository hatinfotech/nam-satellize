<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:29 AM
 */
$provinceList = Common::getBusinessLocationsByParent("VIETNA0001");
?>
<h1 class="tit_mid" itemprop="name">GỬI YÊU CẦU SHIP HÀNG</h1>

<div class="transport-request-form border-box">
<div class="clearfix clearfix-20"></div>
<form action="/Site/Main/sendTransTicketRequest" method="post" enctype="multipart/form-data">
<div class="col-md-8 form-group">
    <label class="control-label">Người gửi <span
            style="color:red">(*)</span></label>
    <input name="FromSenderContactName" type="text" class="form-control"
        placeholder="Nhập tên người gửi" value="<?php echo $_SESSION[K::USER_INFO][K::Name]; ?>" required/>
</div>
<div class="col-md-4 form-group">
    <label class="control-label">Số điện thoại người gửi <span
            style="color:red">(*)</span></label>
    <input name="FromSenderContactPhone" value="<?php echo $_SESSION[K::USER_INFO][K::Phone]; ?>" type="text" class="form-control"
        placeholder="Nhập SĐT người gửi" required/>
</div>
<div class="col-md-8 form-group">
    <label class="control-label">Ngày yêu cầu lấy hàng</label>
    <input name="DateOfReceive" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
</div>
<div class="col-md-4 form-group">
    <label class="control-label">Giờ yêu cầu gửi hàng</label>
    <input name="TimeOfReceive" type="time" class="form-control"/>
</div>

<div class="col-md-4 form-group">
    <label class="control-label">Tỉnh/Thành phố</label>
    <select name="FromProvince" type="time" class="form-control">
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
    <select name="FromDistrict" type="time" class="form-control">
        <option class="label">Chọn Quận/Huyện</option>
    </select>
</div>
<div class="col-md-4 form-group">
    <label class="control-label">Phường/Xã</label>
    <select name="FromVillage" type="time" class="form-control">
        <option class="label">Phường/Xã</option>
    </select>
</div>

<datalist id="address-history">
    <!--<option value="HTML">-->
</datalist>

<div class="col-md-12 form-group">
    <label class="control-label">Địa chỉ lấy hàng <span
            style="color:red">(*)</span></label>
    <input name="ShipFrom" type="text" value="<?php echo $_SESSION[K::USER_INFO][K::Address]; ?>" class="form-control"
        placeholder="Địa chỉ shipper sẽ đến nhận hàng đi giao" required list="address-history"/>
</div>
<div class="clearfix"></div>
<hr style="margin-left: 15px; margin-right: 15px"/>
<div class="col-md-8 form-group">
    <label class="control-label">Người nhận <span
            style="color:red">(*)</span></label>
    <input name="ReceiverName" type="text" class="form-control"
        placeholder="Nhập tên người nhận" required/>
</div>
<div class="col-md-4 form-group">
    <label class="control-label">Số điện thoại người nhận <span
            style="color:red">(*)</span></label>
    <input name="ReceiverPhone" type="text" class="form-control"
        placeholder="Nhập SĐT người nhận" required/>
</div>
<div class="col-md-8 form-group">
    <label class="control-label">Ngày yêu cầu nhận hàng <span
            style="color:red">(*)</span></label>
    <input name="DateOfReceive" type="date" class="form-control" required/>
</div>
<div class="col-md-4 form-group">
    <label class="control-label">Giờ yêu cầu nhận hàng</label>
    <input name="TimeOfReceive" type="time" class="form-control"/>
</div>

<div class="col-md-4 form-group">
    <label class="control-label">Tỉnh/Thành phố</label>
    <select name="ToProvince" type="time" class="form-control">
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
    <select name="ToDistrict" type="time" class="form-control">
        <option class="label">Chọn Quận/Huyện</option>
    </select>
</div>
<div class="col-md-4 form-group">
    <label class="control-label">Phường/Xã</label>
    <select name="ToVillage" type="time" class="form-control">
        <option class="label">Phường/Xã</option>
    </select>
</div>

<div class="col-md-12 form-group">
    <label class="control-label">Địa chỉ nhận hàng <span
            style="color:red">(*)</span></label>
    <input name="ShipTo" type="text" class="form-control"
        placeholder="Địa chỉ shipper sẽ gửi hàng tới" required/>
</div>
<div class="clearfix"></div>
<hr style="margin-left: 15px;  margin-right:15px;"/>
<div class="col-md-8 form-group">
    <label class="control-label">Tên hàng hóa</label>
    <input name="GoodsName" type="text" class="form-control"
        placeholder="Tên hàng hóa cần gửi"/>
</div>
<div class="col-md-4 form-group">
    <label class="control-label">Hình ảnh hàng hóa</label>
    <input name="GoodsImage" type="file" accept=".png,.jpg,.jpge"
        class="form-control"/>
</div>
<div class="col-md-8 form-group">
    <label class="control-label">Thanh toán phí ship : </label>

    <div class="form-control" style="height: initial">
        <label style="line-height: 20px">
            <input name="PayFor" type="radio" value="SENDER"
                class="form-control_" required/>
            Người gửi
        </label>
        /
        <label>
            <input name="PayFor" type="radio" value="RECEIVER"
                class="form-control_" required/>
            Người nhận
        </label>

        <div class="clearfix"></div>
    </div>
</div>

<div class="col-md-4 form-group">
    <label class="control-label">Cân nặng (kg)</label>
    <input name="Weight" type="number" class="form-control" required/>
</div>
<div class="clearfix"></div>

<div class="col-md-8 form-group">
    <label class="control-label">Thu hộ/Tạm ứng : </label>

    <div class="form-control" style="height: initial">
        <label style="line-height: 20px">
            <input name="IsCodOrAdvancePayment" type="radio" value="false"
                class="choosePaymentCost" checked/>
            Không áp dụng
        </label>
        /
        <label style="line-height: 20px">
            <input name="IsCodOrAdvancePayment" type="radio" value="IsCashOnDelivery"
                class="choosePaymentCost"/>
            Thu hộ
        </label>
        /
        <label>
            <input name="IsCodOrAdvancePayment" type="radio" value="IsAdvancePayment"
                class="choosePaymentCost"/>
            Tạm ứng
        </label>

        <div class="clearfix"></div>
    </div>
</div>
<div class="col-md-4 form-group cashOnDelivery" style="display: none">
    <label class="control-label">Tiền thu hộ</label>
    <input name="CashOnDelivery" type="text" class="form-control"
        placeholder="Tiền thu hộ"/>
</div>
<div class="col-md-4 form-group advancePayment" style="display: none">
    <label class="control-label">Tiền ứng</label>
    <input name="AdvancePayment" type="text" class="form-control"
        placeholder="Tiền ứng trước"/>
</div>
<div class="clearfix"></div>
<hr style="margin-left: 15px;  margin-right:15px;"/>
<div class="col-md-12 price-list">
    <div style="font-size: 16px;
    font-weight: bold;
    color: #F44336;">Quảng đường dự tính : <span class="distance">?</span> km
        <button class="btn btn-danger btn-sm priceCheck"><i class="fa fa-check"></i> Kiểm tra giá</button>
    </div>

    <h3><i class="fa fa-server" aria-hidden="true"></i> Báo giá</h3>

    <div class="striped-table">
        <table class="border-box col-sm-12 table-bordered table-striped table-condensed cf price-list">
            <thead class="cf">
            <tr class="price-title">
                <th>Gói dịch vụ</th>
                <th>Mô tả</th>
                <th>Số lượng</th>
                <th class="price-cost">Cước</th>
                <th style="text-align: right">Chọn</th>
            </tr>
            </thead>
            <tbody>
            <tr class="price-item">
                <td data-title="Gói dịch vụ" class="price-label"></td>
                <td data-title="Mô tả" class="price-description"></td>
                <td data-title="Số lượng" class="price-amount">
                    <input type="number" value="1" min="1" style="text-align: center; width: 40px"></td>
                <td data-title="Cước" class="price-cost" style="white-space: nowrap"></td>
                <td data-title="Chọn" style="text-align: right" class="price-choose"><input type="checkbox" value="1">
                </td>
            </tr>
            <tr class="price-summary text-danger" style="font-weight: bold">
                <td data-title="" class="price-label">&nbsp;</td>
                <td data-title="" class="price-description">&nbsp;</td>
                <td data-title="" class="price-sum"><span>Tổng cộng</span></td>
                <td data-title="Tổng cộng" style="white-space: nowrap" class="price-cost"><input readonly type="text" style="width: 100%;"/>
                </td>
                <td data-title="" style="text-align: right">&nbsp;</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="clearfix-20"></div>
</div>

<div class="col-md-12 form-group">
    <input type="submit" name="SendTicketRequest" class="btn btn-success"
        value="GỬI YÊU CẦU"/>
</div>
<div class="clearfix"></div>
</form>
</div>
<script>
    $('select[name="FromProvince"],select[name="FromDistrict"],select[name="FromVillage"],select[name="ToProvince"],select[name="ToDistrict"],select[name="ToVillage"]').select2();
    var priceCheck = $('.priceCheck');
    var fromProvince = $('select[name="FromProvince"]');
    var fromDistrict = $('select[name="FromDistrict"]');
    var fromVillage = $('select[name="FromVillage"]');
    var toProvince = $('select[name="ToProvince"]');
    var toDistrict = $('select[name="ToDistrict"]');
    var toVillage = $('select[name="ToVillage"]');
    var weight = $('input[name="Weight"]');
    var isCodOrAdvancePayment = $('input[name="IsCodOrAdvancePayment"]:checked');


    fromProvince.change(function () {
        $.ajax({
            type: 'GET',
            url: '/Site/Main/getLocationByParent',
            data: 'parent=' + $(this).val(),
            success: function (response) {
//                console.log(response);
                if (response && response['return']) {
                    fromDistrict.setSelectList(response['data']);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    fromDistrict.change(function () {
        $.ajax({
            type: 'GET',
            url: '/Site/Main/getLocationByParent',
            data: 'parent=' + $(this).val(),
            success: function (response) {
//                console.log(response);
                if (response && response['return']) {
                    fromVillage.setSelectList(response['data']);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    toProvince.change(function () {
        $.ajax({
            type: 'GET',
            url: '/Site/Main/getLocationByParent',
            data: 'parent=' + $(this).val(),
            success: function (response) {
//                console.log(response);
                if (response && response['return']) {
                    toDistrict.setSelectList(response['data']);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    toDistrict.change(function () {
        $.ajax({
            type: 'GET',
            url: '/Site/Main/getLocationByParent',
            data: 'parent=' + $(this).val(),
            success: function (response) {
//                console.log(response);
                if (response && response['return']) {
                    toVillage.setSelectList(response['data']);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    fromProvince.val('HOCHIM0051').change();
    toProvince.val('HOCHIM0051').change();

    var priceList = $('.price-list');
    var priceItem = $('.price-item');
    var priceItemTemplate = priceItem.clone();
    priceItem.remove();


    priceList.find('.price-summary .price-cost input').myNumber('numeric', {
        frontEnd: {
            digits: 3,
            groupSeparator: ' ',
            radixPoint: ',',
            suffix: 'đ'
        }
    });

    function priceCalculate() {
        var totalCost = 0;
        priceList.find('.price-item').each(function () {
            if ($(this).find('.price-choose input').is(':checked')) {
                var cost = $(this).find('.price-cost').attr('data');
                var amount = $(this).find('.price-amount input').val();

                totalCost += parseInt(cost ? cost : 0) * parseInt(amount ? amount : 0);
            }
        });
        priceList.find('.price-summary .price-cost input').val(totalCost);
    }

    function suggestPrices() {
        var cashOnDelivery = $('input[name="CashOnDelivery"]');
        var advancePayment = $('input[name="AdvancePayment"]');
        var isCodOrAdvancePayment = $('input[name="IsCodOrAdvancePayment"]:checked');
        var fromLocationCode = fromVillage.val();
        var toLocationCode = toVillage.val();
        if (fromLocationCode && toLocationCode) {
            priceList.find('.price-item').remove();
            $.ajax({
                type: 'GET',
                url: '/Site/Admin/getSuggestPrices',
                data: 'locationFrom=' + fromLocationCode + '&locationTo=' + toLocationCode + '&weight=' + weight.val() + '&isCodOrAdvancePayment=' + isCodOrAdvancePayment.val() + '&cashOnDelivery=' + cashOnDelivery.val() + '&advancePayment=' + advancePayment.val(),
                success: function (response) {
                    console.log(response);
                    if (response) {
                        $('.distance').html(response['distance']);
                        $.each(response['suggestPrices'], function (index, item) {

                            var newItem = priceItemTemplate.clone();
                            newItem.attr('Criteria', item['Criteria']);
                            newItem.attr('CriteriaGroup', item['CriteriaGroup']);
                            newItem.attr('PriceDetailId', item['Id']);
                            newItem.find('.price-label').html(item['Label']);
                            newItem.find('.price-description').html(item['Description']);
                            newItem.find('.price-cost').attr('data', item['MinPrice']).html(item['MinPriceFormat']);
                            var amount = newItem.find('.price-amount input').prop('disabled', item['Selected']);
                            var choose = newItem.find('.price-choose input').prop('checked', item['Selected']).prop('disabled', item['Selected']).attr('isSelected', item['Selected']);

                            choose.attr('name', 'Prices[' + item['Id'] + '][Choose]');
                            amount.attr('name', 'Prices[' + item['Id'] + '][Amount]');

                            priceList.find('.price-summary').before(newItem);

                            amount.change(priceCalculate);
                            choose.change(priceCalculate);
                            newItem.find('*').click(function (e) {
                                if (e.target.nodeName.toLowerCase() != 'input') {
                                    $(this).closest('tr').find('.price-choose input').click();
                                }
                            });
                            newItem.find('.price-choose input').click(function () {
                                var $this = $(this);
                                if ($(this).is(':checked')) {
                                    priceList.find('.price-item[CriteriaGroup="' + $this.closest('tr').attr('CriteriaGroup') + '"] .price-choose input').each(function () {
                                        if ($(this).closest('tr').attr('PriceDetailId') != $this.closest('tr').attr('PriceDetailId') && $(this).closest('tr').attr('CriteriaGroup') != '') {
                                            $(this).prop('checked', false);
                                        }
                                    });
                                } else {

                                }
                            });
                        });
                        priceCalculate();
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }
    priceCheck.click(function () {
        suggestPrices();
        return false;
    });
    //fromVillage.change(suggestPrices);
    //toVillage.change(suggestPrices);

    priceList.find('.price-item').click(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        checkbox.click();
    });


    var choosePaymentCost = $('.choosePaymentCost');
    var cashOnDelivery = $('.cashOnDelivery');
    var advancePayment = $('.advancePayment');
    console.log('choosePaymentCost:');
    console.log(choosePaymentCost);
    choosePaymentCost.click(function () {
        var $this = $(this);
        console.log($this.val());
        switch ($this.val()) {
            case 'IsCashOnDelivery':
                cashOnDelivery.show();
                advancePayment.hide();
                break;
            case 'IsAdvancePayment':
                cashOnDelivery.hide();
                advancePayment.show();
                break;
            default :
                cashOnDelivery.hide();
                advancePayment.hide();
                break;
        }
    });
</script>