<div class="box">
<?php
if(count($product->virtuemart_media_id)>1):?>
    <div>
        <?php
        foreach($product->virtuemart_media_id as $i=> $virtuemart_media_id):?>
            <div data-img-id="<?php echo $virtuemart_media_id;?>"><?php
                echo $i+1;
                ?></div>
        <?php
        endforeach;?>
    </div>
<?php
endif;?>
    <a title="<?=$product->link?>" rel="vm-additional-images" href="<?=$product->link?>"><?php if(isset($test)){?>PRODUCT<?php }?>
        <div class="img"><?php
            echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false)?>
        </div>
        <h2><?php
            // обрабатывается клиентским скриптом
            echo $product->product_name; ?></h2>
    </a>
    <?php
    if (!empty($product->product_s_desc)):?>

        <p class="product_s_desc"><?
            $pdesc =  shopFunctionsF::limitStringByWord ($product->product_desc, 150, '...');
            echo strip_tags($pdesc);
            ?></p>
    <?php
    endif;

    if ($this->show_prices == '1') {

        if ($product->prices['salesPrice']<=0 and VmConfig::get ('askprice', 1) and  !$product->images[0]->file_is_downloadable):
            echo JText::_ ('COM_VIRTUEMART_PRODUCT_ASKPRICE');
        endif;

        if ($this->showBasePrice):
            echo $this->currency->createPriceDiv ('basePrice', 'COM_VIRTUEMART_PRODUCT_BASEPRICE', $product->prices);
            echo $this->currency->createPriceDiv ('basePriceVariant', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_VARIANT', $product->prices);
        endif;

        echo $this->currency->createPriceDiv ('variantModification', 'COM_VIRTUEMART_PRODUCT_VARIANT_MOD', $product->prices);

        if (round($product->prices['basePriceWithTax'],$this->currency->_priceConfig['salesPrice'][1]) != $product->prices['salesPrice']) :
            echo '<span class="price-crossed" >' . $this->currency->createPriceDiv ('basePriceWithTax', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX', $product->prices) . "</span>";
        endif;

        if (round($product->prices['salesPriceWithDiscount'],$this->currency->_priceConfig['salesPrice'][1]) != $product->prices['salesPrice']) :
            echo $this->currency->createPriceDiv ('salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $product->prices);
        endif;
        /**
            * Вывод цены, вариант по умолчанию */
        echo $this->currency->createPriceDiv ('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $product->prices);

        echo $this->currency->createPriceDiv ('priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $product->prices);

        echo $this->currency->createPriceDiv ('discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $product->prices);

        echo $this->currency->createPriceDiv ('taxAmount', 'COM_VIRTUEMART_PRODUCT_TAX_AMOUNT', $product->prices);

        $unitPriceDescription = JText::sprintf ('COM_VIRTUEMART_PRODUCT_UNITPRICE', $product->product_unit);

        echo $this->currency->createPriceDiv ('unitPrice', $unitPriceDescription, $product->prices);

    }
    $show_button=false;
    if ($show_button):// Product Details Button?>
        <p><?=JHTML::link ($product->link, JText::_ ('COM_VIRTUEMART_PRODUCT_DETAILS'), array('title' => $product->product_name, 'class' => 'product-details'))?></p>
    <?php
    endif;?>
</div>
