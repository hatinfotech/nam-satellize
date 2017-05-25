<!DOCTYPE html>
<html>
<head lang="en">
    <base href="<?php echo $this->getDefaultTemplatePath(); ?>/"/>
    <meta charset="UTF-8">
    <title>Test</title>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="plugin/jQuery-Inputmask/js/inputmask.js"></script>
    <script type="text/javascript" src="plugin/jQuery-Inputmask/js/inputmask.numeric.extensions.js"></script>
    <script type="text/javascript" src="plugin/jQuery-Inputmask/js/jquery.inputmask.js"></script>
    <script type="text/javascript" src="plugin/number/number.js"></script>
    <script type="text/javascript" src="plugin/jquery.number.js"></script>
</head>
<body>

<input type="tel" class="number">

</body>
<script>
    $('.number').myNumber('numeric', {
        frontEnd: {
            digits: 3,
            groupSeparator: ' ',
            radixPoint: ','
        }
    })
</script>
</html>