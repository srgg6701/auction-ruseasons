<?php
defined ('_JEXEC') or die('Restricted access');

$zoom=false;
if($zoom):?>
<link href="http://auction.auction-ruseasons.ru/geo_templates/Lemon/external/css/style.css" rel="stylesheet" type="text/css" />
<link href="http://auction.auction-ruseasons.ru/geo_templates/Lemon/external/magiczoomplus/magiczoomplus.css" rel="stylesheet" type="text/css" media="screen"/>
<script src="http://auction.auction-ruseasons.ru/geo_templates/Lemon/external/magiczoomplus/magiczoomplus.js" type="text/javascript"></script>
<script type='text/javascript' src='http://auction.auction-ruseasons.ru/js/prototype.js'></script>
<script type='text/javascript' src='http://auction.auction-ruseasons.ru/geo_templates/Lemon/external/js/main.js'></script>
<div class="gallery_lot">
    <div id="galleryContainer" style="clear: both;">
        <div class="main_im_lot">
            <div id="galleryBigImage">
                <div id="bigLeadImage">
                    <!--<script type="text/javascript">
                        //<![CDATA[
                        images[0] = new galleryAddImage(
                            '',
                            // http://auction-ruseasons.ru/items_images/mini_devyshka_v_shali_01_143_09_2.jpg
                            'http://auction-ruseasons.ru/items_images/mini_devyshka_v_shali_01_143_09_1.jpg',
                            'http://auction-ruseasons.ru/items_images/preview_mini_devyshka_v_shali_01_143_09_1.jpg',
                            334,
                            334,
                            '',
                            0);
                        //]]>
                    </script>-->
                    <?php // большая картинка для фрагментарного просмотра ?>
                    <a href="http://localhost/auction-ruseasons/images/stories/virtuemart/product/03_241_11_01.jpg" class="MagicZoomPlus" id="Zoomer" rel="zoom-width:450px;zoom-border:2px;zoom-height:293px;" title="" style="position: relative; display: inline-block; text-decoration: none; outline: 0px; margin: auto; width: 334px;">
                        <img src="http://auction-ruseasons.ru/items_images/preview_mini_devyshka_v_shali_01_143_09_2.jpg" width="334" height="334" alt="" style="opacity: 1;">
                        <div class="MagicZoomBigImageCont" style="overflow: hidden; z-index: 100; top: -10000px; position: absolute; width: 450px; height: 293px; opacity: 1; left: 349px;">
                            <div class="MagicZoomHeader" style="position: relative; z-index: 10; left: 0px; top: 0px; padding: 3px; display: none; visibility: hidden;"></div>
                            <div style="overflow: hidden;">
                                <?php // большая картинка для фрагментарного просмотра ?>
                                <img src="http://localhost/auction-ruseasons/images/stories/virtuemart/product/03_241_11_01.jpg" style="padding: 0px; margin: 0px; border: 0px; position: relative; left: -527.2px; top: -421.8px;">
                            </div>
                        </div>
                        <div class="MagicZoomPup" style="z-index: 10; position: absolute; overflow: hidden; display: none; visibility: hidden; width: 125px; height: 82px; opacity: 0.5; left: 177.230133056641px; top: 121.193206787109px;"></div>
                    </a>
                </div>
            </div>
        </div>
        <div id="galleryThumbs">
            <div style="padding-left: 0px; margin-left: 0px;">
                <div class="th_imgage">
                    <div class="inside_image_preview">
                        <a href="http://auction-ruseasons.ru/items_images/mini_devyshka_v_shali_01_143_09_1.jpg" rel="zoom-id:Zoomer" rev="http://auction-ruseasons.ru/items_images/preview_mini_devyshka_v_shali_01_143_09_1.jpg" class="MagicThumb-swap" style="outline: 0px;">
                            <img src="http://auction-ruseasons.ru/items_images/preview_mini_devyshka_v_shali_01_143_09_1.jpg" height="82" width="82" alt="" title="">
                        </a>
                    </div>
                </div>
                <div class="th_imgage">
                    <div class="inside_image_preview">
                        <?php // http://auction-ruseasons.ru/items_images/preview_mini_devyshka_v_shali_01_143_09_2.jpg
                        // картинка, загружающаюся по клику ?>
                        <a href="http://localhost/auction-ruseasons/images/stories/virtuemart/product/03_241_11_01.jpg" rel="zoom-id:Zoomer"<?php
                            /*  Картинка, заполняющая блок основного изображения.
                                Должна иметь размеры, точно соответствующие его параметрам */
                            ?>rev="http://localhost/auction-ruseasons/images/stories/virtuemart/product/resized/03_241_11_01_226x226.jpg" id="mt-990251389441" class="MagicThumb-swap" style="outline: 0px;"><?php
                            // миниматюра
                            ?><img src="http://localhost/auction-ruseasons/images/stories/virtuemart/product/resized/03_241_11_01_226x226.jpg" height="82" width="82" alt="" title="">
                        </a>
                    </div>
                </div>

            </div>
        </div>


        <div class="clr"></div>
    </div>
</div>
<?php
else:?>
<?php

endif;