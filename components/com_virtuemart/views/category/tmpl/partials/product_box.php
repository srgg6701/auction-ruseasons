<div class="box">
<?php
if(count($product->virtuemart_media_id)>1):
    // построить маркеры для пролистывания картинок
    HTML::buildProductImagesQueue($product->virtuemart_media_id);
endif;?>
    <a title="<?=$product->product_name?>" rel="vm-additional-images" href="<?=$product->link?>"><?php if(isset($test)){?>PRODUCT<?php }?>
        <div class="img"><?php
            echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false)?>
        </div>
        <h2><?php
            // обрабатывается клиентским скриптом
            echo $product->product_sku . ' ' . $product->product_name; ?></h2>
    </a>
    <?php
    if (!empty($product->product_s_desc)):?>

        <p class="product_s_desc"><?php
            $pdesc =  shopFunctionsF::limitStringByWord ($product->product_desc, 150, '...');
            echo strip_tags($pdesc);
            ?></p>
    <?php
    endif;

    if ($this->show_prices == '1') {
        // VM
        require_once 'prices_before.php';
        //if($product->virtuemart_product_id=='3827') commonDebug(__FILE__,__LINE__,$product, false);
        /**
         * Вывод цены, вариант по умолчанию */
        //echo "► " . $this->currency->createPriceDiv ('costPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $product->prices/*,    false, false, 1.0, 'shop'*/);

        if(!($product_cost=$product->prices['basePriceVariant'])) $product_cost ='0';
        $product_cost_round=($product->currency_symbol=='руб')? 0:2;
        echo  "Цена: " . round($product_cost, $product_cost_round). ' ' . $product->currency_symbol;

        /*if($product->virtuemart_product_id=='3827') commonDebug(__FILE__,__LINE__,$product->prices, false);
        else echo " ◄skipped► ";*/

        /* $pr=$this->products[0];
        $this->currency->arr_prices=array(
                    'product_price'=>$pr->product_price,
                    'currency_symbol'=>$pr->currency_symbol
                );
        echo $this->currency->createPriceDiv ('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $product->prices);*/
        //-------------------------------------------------

        // VM
        require_once 'prices_after.php';

    }
    $show_button=false;
    if ($show_button):// Product Details Button?>
        <p><?=JHTML::link ($product->link, JText::_ ('COM_VIRTUEMART_PRODUCT_DETAILS'), array('title' => $product->product_name, 'class' => 'product-details'))?></p>
    <?php
    endif;?>
</div>
