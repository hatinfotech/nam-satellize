<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:29 AM
 */
/* @var $this Site_Controller_Admin */
//$provinceList = Common::getLocationsByParent("VIETNA0001");
//$requestPage = $this->getBootstrap()->getRequestParams('page') ?: 1;
//$response = NaMApi::g()->getTicketList(20, $requestPage);
?>
<div class="striped-table">
    <h1 class="tit_mid" itemprop="name">DANH SÁCH VẬN ĐƠN</h1>

    <table class="border-box col-sm-12 table-bordered table-striped table-condensed cf ticket-list">
        <thead class="cf">
        <tr>
            <th>Mã</th>
            <th>Phí ship</th>
            <th>ĐC lấy hàng</th>
            <th>Hàng hóa/chuyển tiếp</th>
            <th>ĐC nhận hàng</th>
            <th style="text-align: right">Chi tiết</th>
        </tr>
        </thead>
        <tbody>
        <?php

        //$list = $response[K::data][K::listKey];
        foreach ($this->ticketList as $item) {
            $goodsList = $item[K::GoodsList];
            $goodsListAsStr = '';
            foreach ($goodsList as $goods) {
                $goodsListAsStr .= $goods[K::GoodsName] . ", ";
            }

            $goodsListAsStr = trim($goodsListAsStr, ', ');

            $histories = $item[K::History];
            //Common::printArr($histories);

            $openStateStr = '';
            $acceptStateStr = '';
            $receiveStateStr = '';
            $shippingStateStr = '';
            $forwardStateStr = '';
            $completeStateStr = '';
            foreach ($histories as $history) {
                if (in_array($history[K::TicketState], array(
                    C::OPEN
                ))) {
                    $openStateStr .= '<span class="label label-' . $history[K::StateColor] . '"><i class="' . $history[K::StateIcon] . '"></i> ' . $history[K::StateLabel] . '</span><br>';
                }

                if (in_array($history[K::TicketState], array(
                    C::ACCEPT
                ))) {
                    $acceptStateStr .= '<span class="label label-' . $history[K::StateColor] . '"><i class="' . $history[K::StateIcon] . '"></i> ' . $history[K::StateLabel] . '</span><br>';
                }

                if (in_array($history[K::TicketState], array(
                    C::RECEIVED
                ))) {
                    $receiveStateStr .= '<span class="label label-' . $history[K::StateColor] . '"><i class="' . $history[K::StateIcon] . '"></i> ' . $history[K::StateLabel] . '</span><br>';
                }

                if (in_array($history[K::TicketState], array(
                    C::SHIPPING
                ))) {
                    $shippingStateStr .= '<span class="label label-' . $history[K::StateColor] . '"><i class="' . $history[K::StateIcon] . '"></i> ' . $history[K::StateLabel] . '</span><br>';
                }

                if (in_array($history[K::TicketState], array(
                    C::SHIPPING, C::FORWARDING, C::REQUESTFORWARDACCEPT, C::CANCElFORWARD, C::FORWARDACCEPT
                ))) {
                    $forwardStateStr .= '<span class="label label-' . $history[K::StateColor] . '"><i class="' . $history[K::StateIcon] . '"></i> ' . $history[K::StateLabel] . '</span><br>';
                }

                if (in_array($history[K::TicketState], array(
                    C::COMPLETE
                ))) {
                    $completeStateStr .= '<span class="label label-' . $history[K::StateColor] . '"><i class="' . $history[K::StateIcon] . '"></i> ' . $history[K::StateLabel] . '</span><br>';
                }
            }

            $tdBg = '';

            if ($_SESSION[K::LAST_NEW_TICKET] == $item[K::Code]) {
                $tdBg = 'bg-danger';
            }
            ?>
            <tr>
                <td class="<?php echo $tdBg; ?>" data-title="Mã"><?php echo $openStateStr . $item[K::Code]; ?></td>
                <td class="<?php echo $tdBg; ?>" data-title="Phí ship" style="white-space: nowrap">
                    <?php echo $acceptStateStr . number_format($item[K::ShipCost]) . 'đ'; ?>
                </td>
                <td class="<?php echo $tdBg; ?>" data-title="ĐC lấy hàng"><?php echo $receiveStateStr . $item[K::ShipFrom]; ?></td>
                <td class="<?php echo $tdBg; ?>" data-title="Hàng hóa/chuyển tiếp"><?php echo $forwardStateStr . $goodsListAsStr; ?></td>
                <td class="<?php echo $tdBg; ?>" data-title="ĐC nhận hàng"><?php echo $completeStateStr . $item[K::ShipTo]; ?></td>
                <td class="<?php echo $tdBg; ?>" data-title="Chi tiết" style="text-align: right">
                    <a class="btn btn-success" href="/thong-tin-van-don/<?php echo $item[K::Code]; ?>.html" role="button"><i class="fa fa-external-link"></i></a>
                </td>
            </tr>
        <?php
        }
        //$_SESSION[K::LAST_NEW_TICKET] =
        ?>
        </tbody>
    </table>
    <?php
    //$totalPage = $response[K::data][K::paging][K::totalPage];
    //$currentPage = $response[K::data][K::paging][K::page];
    ?>
    <ul class="pagination" style="margin-top: 0">
        <li>
            <a href="/danh-sach-van-don/page-<?php echo $this->currentPage > 1 ? $this->currentPage - 1 : 1; ?>.html" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php
        for ($i = 1; $i <= $this->totalPage; $i++) {
            ?>
        <li class="<?php echo $this->currentPage == $i ? 'active' : ''; ?>">
            <a href="/danh-sach-van-don/page-<?php echo $i; ?>.html"><?php echo $i; ?></a></li><?php
        }
        ?>
        <li>
            <a href="/danh-sach-van-don/page-<?php echo $this->currentPage < $this->totalPage ? $this->currentPage + 1 : $this->totalPage; ?>.html" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
    <div class="clearfix clearfix-20"></div>

    <!-- Table -->
    <!--<div class="row">-->
    <!--    <div id="no-more-tables" class="col-sm-12">-->
    <!--        -->
    <!--    </div>-->
    <!--</div>-->

    <!--</div>-->
</div>
<script>

</script>