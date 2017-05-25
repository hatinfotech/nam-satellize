<?php
/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 08:43 PM
 */
?>

<h2 class="tit_mid">THÔNG BÁO</h2>

<div class="transport-request-form border-box">
    <div style="padding: 5px; font-size: 24px; padding-bottom: 25px;">
        <div class="clearfix clearfix-20"></div>
        <?php

        if ($_SESSION[K::LAST_NOTIFICATION]) {
            echo $_SESSION[K::LAST_NOTIFICATION];
        }

        /** @var Exception $lastException */
        if (!$_SESSION[K::LAST_NOTIFICATION] && $_SESSION[K::LAST_EXCEPTION]) {
            $lastException = $_SESSION[K::LAST_EXCEPTION];
            if ($lastException instanceof Exception) {
                echo "<br>" . $lastException->getMessage();
            }
            ?>
            <div style="display: none">
                <?php $lastException->getTraceAsString(); ?>
            </div>
        <?php
        }
        unset($_SESSION[K::LAST_NOTIFICATION]);
        unset($_SESSION[K::LAST_EXCEPTION]);
        ?>
        <br/>
    </div>
</div>