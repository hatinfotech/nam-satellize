<?php
/* @var $this Site_Controller_Main */
?>
<footer class="footer">
    <div class="main_footer">
        <div class="container">
            <div class="row_pc">
                <div class="row">
                    <?php $this->renderWidget('CONTACT', C::footerWidget); ?>
                    <?php $this->renderWidget('CONPANYINFO', C::footerWidget); ?>
                    <?php $this->renderWidget('COMPANYPOLICY', C::footerWidget); ?>
                    <?php $this->renderWidget('SOCIALSHARINGFOOTER', C::footerWidget); ?>
                    <!--                    <div class="clearfix clearfix-5"></div>-->
                    <!--                    --><?php //$this->renderWidget('COPYRIGHT'); ?>
                    <div class="clearfix clearfix-5"></div>
                    <div class="txt_boz" style="text-align: center;">
                        <?php echo Config_Parameter::g(K::COPYRIGHT); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="clearfix"></div>
    <script type="text/javascript">
        if (window.innerWidth > 320) {
            $(window).scroll(function () {
                if ($(window).scrollTop() >= 36) {
                    $('.sticky-header').addClass('fixed');
                }
                else {
                    $('.sticky-header').removeClass('fixed');
                }
            });
        }

        if (window.innerWidth > 320) {
            $(window).scroll(function () {
                if ($(window).scrollTop() >= 36) {
                    $('.sticky-header').addClass('fixed');
                }
                else {
                    $('.sticky-header').removeClass('fixed');
                }
            });
        }

        /* When the user clicks on the button,
         toggle between hiding and showing the dropdown content */
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        // Close the dropdown menu if the user clicks outside of it
        window.onclick = function (event) {
            if (!event.target.matches('.dropbtn')) {

                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</footer>