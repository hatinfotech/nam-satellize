<?php
/* @var $this Controller */
$siteInfo = Config_Parameter::getSiteInfo();
?>
<base href="<?php echo $this->getDefaultTemplatePath(); ?>/"/>
<meta charset="utf-8"/>
<title><?php echo $this->getSiteTitle(); ?></title>
<link href="/upload/shippersaigon-icon.png" rel="icon" type="image/x-icon">
<link href="/upload/shippersaigon-icon.png" rel="shortcut icon">
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta name="description" content="<?php echo $this->getSiteDescription(); ?>" property="og:description"/>
<meta name="keywords" content="<?php echo $this->getSiteKeyword(); ?>"/>
<meta content="<?php echo $this->getSiteTitle(); ?>" property="og:title"/>
<meta content="company" property="og:type"/>
<meta content="<?php echo $this->getSiteLogo(); ?>" property="og:image"/>
<meta content="<?php echo $this->getSiteName(); ?>" property="og:site_name"/>
<meta name="author" content="stvt"/>
<meta name="Revisit-after" content="1 day"/>
<meta name="Robots" content="INDEX,FOLLOW"/>
<meta name="page-topic" content="<?php echo $this->getSiteTopic(); ?>"/>
<meta name="page-type" content="<?php echo $this->getSiteType(); ?>"/>


<link href="css/bootstrap.min.css" rel="stylesheet"/>
<link href="plugin/select2/select2.css" rel="stylesheet"/>
<link href="plugin/select2/select2-metronic.css" rel="stylesheet"/>
<link href="css/font-awesome.css" rel="stylesheet"/>
<link href="css/style.css" rel="stylesheet"/>
<link href="css/setmedia.css" rel="stylesheet"/>
<link href="css/menu-1.css" rel="stylesheet"/>
<link href="css/slider_full.css" rel="stylesheet"/>
<link href="css/<?php echo Config_Parameter::g(K::SITE_CODE); ?>.css" rel="stylesheet"/>

<!-- slider_full -->
<link href="css/related_product.css" rel="stylesheet"/>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<!--<script type="text/javascript" src="js/jquery-2.0.0.js"></script>-->
<script type="text/javascript" src="js/bootstrap.min.js"></script>


<script type="text/javascript" src="plugin/jQuery-Inputmask/js/inputmask.js"></script>
<script type="text/javascript" src="plugin/jQuery-Inputmask/js/inputmask.numeric.extensions.js"></script>
<script type="text/javascript" src="plugin/jQuery-Inputmask/js/jquery.inputmask.js"></script>
<script type="text/javascript" src="plugin/number/number.js"></script>
<script type="text/javascript" src="plugin/jquery.number.js"></script>

<script type="text/javascript" src="js/nav-menu3.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<!--<script src="select2-4.0.3/dist/js/select2.min.js"></script>-->
<script src="plugin/select2/select2.js"></script>

<!--menu-->
<script type="text/javascript" src="js/style-img.js"></script>
<script type="text/javascript" src="js/jssor.core.js"></script>
<script type="text/javascript" src="js/jssor.slider.js"></script>
<script type="text/javascript" src="js/jssor.utils.js"></script>
<script type="text/javascript" src="js/slider_full.js"></script>
<!-- slider_full -->
<!--<script type="text/javascript" src="js/related_product.js"></script>-->