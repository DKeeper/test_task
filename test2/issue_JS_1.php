<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 12.08.14
 * @time 10:43
 * Created by JetBrains PhpStorm.
 */
$WYSIWYG = "
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eu vestibulum nisi. Pellentesque fermentum lectus ipsum, eget facilisis sem ultrices in. <img src='/i/stop-sign.gif' width='100' height='100' alt='Some text' />Curabitur et elit ut odio cursus accumsan non eget mi. Praesent nec leo vel mauris lobortis venenatis id vitae velit. Integer fermentum, felis sed laoreet sagittis, lectus sem ultricies massa, sit amet lobortis nunc arcu ac libero. Sed sed condimentum mauris. Pellentesque eget mattis eros. Suspendisse vulputate pretium lorem eget euismod.</p>
<p>Nulla vitae nisi eu tortor rhoncus ornare. Duis ultrices urna eu tellus imperdiet mollis. Nulla venenatis bibendum felis. Duis vestibulum risus eget est sagittis congue. Maecenas sagittis blandit molestie. Nullam rhoncus ligula sit amet <img src='/i/stop-sign.gif' width='200' height='200' alt='Some text' />vulputate tincidunt. Nam auctor dictum fermentum.</p>
<p>In congue, ante ultrices auctor feugiat, neque urna iaculis orci, vel luctus nulla nisl eu diam. Fusce at lobortis odio, eu molestie dolor. Maecenas consequat scelerisque diam at posuere. Pellentesque id lacinia nulla. Sed ante lacus, auctor vitae quam vitae, fringilla ornare risus. Pellentesque blandit quam enim, vel consequat mi blandit ac. Nulla faucibus eu est sit amet tempus. <img src='/i/stop-sign.gif' width='300' height='300' alt='Some text' />Mauris mattis velit egestas lectus fermentum, et vulputate sapien sodales. Proin eleifend, nunc quis porta luctus, libero justo facilisis libero, blandit porta enim mauris ac lectus. Maecenas ornare id erat eu hendrerit. Aenean nec tincidunt nisl. Nunc ut lorem euismod, aliquam diam eget, scelerisque metus. Morbi dui magna, scelerisque at congue id, molestie vel lorem. Ut id mi et nunc dictum dictum.</p>
<p>Duis aliquet ante ac lacus semper lacinia. Pellentesque pulvinar tortor sit amet mi tristique, ut dictum lacus convallis. Phasellus feugiat felis sit amet sodales luctus. In hac habitasse platea dictumst. Aenean dui diam, cursus non nisl ac, bibendum hendrerit urna. In vitae augue enim. Ut et tellus vel nibh cursus suscipit nec nec mi. Nunc tempor sodales mi, id molestie orci auctor ut. Integer pretium justo pretium libero dictum varius. Aenean cursus dolor urna, et consectetur enim dictum dapibus. Nam eu odio ac mauris hendrerit aliquet <img src='/i/stop-sign.gif' width='400' height='400' alt='Some text' />eget feugiat turpis. Curabitur et nisi at augue tincidunt lobortis id sed nunc. Nulla turpis nunc, gravida ultrices ornare sit amet, volutpat sed libero. Nunc sed vestibulum dui, vel malesuada est. In faucibus tristique dapibus. Cras viverra eros et tellus ornare, et varius magna pulvinar.</p>
<p>Pellentesque euismod eu felis vitae sollicitudin. Vestibulum laoreet, lacus pulvinar iaculis tempor, nibh quam pulvinar nisi, eget pellentesque leo ligula nec dolor. Suspendisse potenti. Donec lobortis eros magna, non dictum felis bibendum vestibulum. Phasellus tincidunt metus eu erat dictum, sed interdum nunc eleifend. Pellentesque lobortis fringilla nisl, in gravida enim interdum in. Aenean suscipit lacinia tellus, blandit tristique nibh congue id. Vivamus sed libero ornare sem pretium varius<img src='/i/stop-sign.gif' width='500' height='500' alt='Some text' /> nec quis eros. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
";
?>
<html>
<head>
    <title>Test page</title>
</head>
<body>
<div id="content">
    <?= $WYSIWYG; ?>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(function(){
        $("#content img").wrap(function(){
            var _w = $(this).width();
            return $("<div>").addClass("imageStyle").css("width",_w+4);
        }).after(function(){
            return $("<p>").append($(this).attr("alt"));
        });
    });
</script>
</body>
</html>