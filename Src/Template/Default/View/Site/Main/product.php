<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:29 AM
 */
/** @var Site_Controller_Main $this */
?>
<h2 class="tit_mid">SẢN PHẨM</h2>

<div class="transport-request-form border-box">
    <div style="padding: 5px; font-size: 14px; padding-bottom: 25px;">
        <div class="clearfix clearfix-20"></div>
        <?php
        //print_r($this->productInfo);
        ?>
        <div class="product-info">
            <div class="title" style="color: red; font-weight: bold; font-size: 16px;"><?php echo $this->productInfo[K::Name]; ?></div>
            <div class="code" style="font-weight: bold">Code : <?php echo $this->productInfo[K::Code]; ?></div>
            <div class="image"><img style="width: 100%" src="<?php echo '/upload/' . $this->productInfo['PictureId']; ?>"></div>
            <?php if ($this->productInfo[K::Price]) { ?>
                <div class="price" style="font-weight: bold">Giá : <?php echo $this->productInfo[K::Price]; ?></div>
                <div class="control">
                    <a href="/site/cart/addToCart?product=<?php echo $this->productInfo[K::Code]; ?>" class="btn btn-success"><i class="fa fa-cart-plus"></i> Mua ngay</a>
                </div>
            <?php } ?>
            <!--<div class="after-price">KM : ???</div>-->
            <?php if ($this->productInfo[K::Description]) { ?>
                <div class="block-title" style="border-bottom: 1px solid #ccc; font-weight: bold; font-size: 16px; margin-bottom: 5px; margin-top: 10px">Mô tả</div>
                <div class="description"><?php echo str_replace("\n", '<br>', $this->productInfo[K::Description]); ?></div>
            <?php } ?>
            <?php if ($this->productInfo[K::Technical]) { ?>
                <div class="block-title" style="border-bottom: 1px solid #ccc; font-weight: bold; font-size: 16px; margin-bottom: 5px; margin-top: 10px">Thông số</div>
                <div class="technical"><?php echo str_replace("\n", '<br>', $this->productInfo[K::Technical]); ?></div>
            <?php } ?>
            <hr>
            <div class="keyword" style="font-style: italic; font-size: 12px">Từ khóa : <?php echo $this->productInfo[K::Keyword]; ?></div>
        </div>
    </div>
</div>

