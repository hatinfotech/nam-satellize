<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:29 AM
 */
/* @var $this Site_Controller_Main */
?>
<h2 class="tit_mid">DANH MỤC SẢN PHẨM</h2>

<div class="transport-request-form border-box_" style="padding-top: 0px">
    <div class="container-fluid">
        <div style="font-size: 14px">
            <div class="row product-list" style="border-left: 1px solid #ccc; border-top: 1px solid #ccc">
                <?php foreach ($this->productList as $product) { ?>
                    <div class="col-md-4 product" style="
                    border-right: 1px solid #ccc;
                    border-bottom: 1px solid #ccc;
                    padding-top: 5px;
                    padding-bottom: 5px;
                    height: 318px">
                        <div class="title" style="
                        color: #0787ea;
                        font-family: UTM_AvoBol;
                        line-height: 16px;
                        font-size: 18px;
                        font-weight: bold;
                        margin-bottom: 5px">
                            <a href="/<?php echo $product[K::UniqueKey]; ?>.html"><?php echo $product[K::Name]; ?></a>
                        </div>
                        <div class="code" style="color: #213E57;
                                    font-size: 14px;
                                    font-weight: bold;
                                    margin-bottom: 5px">MÃ : <?php echo $product[K::Code]; ?></div>
                        <div class="summary" style="
                        line-height: 16px;
                        max-height: 48px;
                        overflow: hidden;
                        "><?php echo strip_tags($product[K::Description]); ?>
                        </div>
                        <div class="price" style="font-weight: bold">GIÁ : <span style="color: red"><?php echo number_format($product[K::Price]); ?>đ</span></div>
                        <div class="after-price">(Đã bao gồm VAT)</div>
                        <div class="bottom" style="position: absolute; bottom: 0; left: 0; padding: 5px">
                            <div class="image">
                                <a href="/<?php echo $product[K::UniqueKey]; ?>.html"><img class="w_100" itemprop="image" src="/upload/<?php echo $product[K::PictureId]; ?>" alt="" title="" style="height: 112.45px;"></a>
                            </div>
                            <div class="clearfix-5"></div>
                            <div class="control">
                                <a role="button" href="/site/cart/addToCart?product=<?php echo $product[K::Code]; ?>" class="btn btn-block<?php echo $product[K::Price] ? ' btn-success' : ' btn-default disabled'; ?>"><i class="fa fa-cart-plus"></i> Cho vào giỏ</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <nav aria-label="...">
        <nav aria-label="...">
            <ul class="pagination">
                <li><a href="/phan-loai-san-pham/<?php echo $this->productCateUniqueKey; ?>/trang-<?php echo $this->productInCatePage > 1 ? $this->productInCatePage - 1 : 1; ?>.html" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                <?php for ($page = 1; $page <= $this->productInCateTotalPage; $page++) {
                    ?>
                <li class="<?php echo $page == $this->productInCatePage ? 'active' : ''; ?>"><a href="/phan-loai-san-pham/<?php echo $this->productCateUniqueKey; ?>/trang-<?php echo $page; ?>.html"><?php echo $page; ?></a></li><?php
                }
                ?>
                <li><a href="/phan-loai-san-pham/<?php echo $this->productCateUniqueKey; ?>/trang-<?php echo $this->productInCatePage < $this->productInCateTotalPage ? $this->productInCateTotalPage : $this->productInCatePage + 1; ?>.html" aria-label="Next"><span aria-hidden="true">»</span></a></li>
            </ul>
        </nav>
    </nav>
</div>