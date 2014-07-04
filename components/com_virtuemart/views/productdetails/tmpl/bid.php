<hr/>
<h3 id="header_bid" class="clearfix">
    <img src="<?php echo JUri::base();?>templates/auction/images/auction_hammer_gavel_big.png">
    <span>Сделать ставку</span>
</h3>
<form method="post" action="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.makeBid'); ?>" onsubmit="return checkFormFields()">
<?php 
    // получить шаг ставки
    $step=AuctionStuff::getPricesRange(
                AuctionStuff::getBidSum(
                        array(  'product_price'=>$this->product->prices['basePriceVariant'],
                                'virtuemart_product_id'=>$this->product->virtuemart_product_id  )
                    ));
    //echo "<div>price, step = ".$this->product->prices['basePriceVariant'].", $step</div>";
?>
<p id="bid-sum-p"><span style="font-size:2em; color:orange;">&bull;</span> Минимальная ставка составляет <?php 
    echo ((int)$this->product->prices['basePriceVariant']+(int)$step);?> руб.</p>
<p>Ваша ставка: <input id="bid_sum" type="text" name="bid_sum" size="12" value=""/></p>    
<input type="checkbox" id="bid_agree" name="bid_agree"/>я согласен с правилами аукциона.
<?php echo JHtml::_('form.token');
?>
<input type="hidden" name="option" value="com_auction2013"/>
<input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id;?>"/>
<input type="hidden" name="task" value="auction2013.makeBid"/>
<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>"/>
<input type="hidden" name="virtuemart_category_id" value="<?php echo $virtuemart_category_id;?>"/>
<button type="submit" class="buttonSandCool">Сделать ставку</button>
</form>
<script>
function checkFormFields(){
    var element, err=[];
    console.log();
    if(!document.getElementById('bid_sum').value)
        err.push('Вы не указали сумму ставки');
    if(document.getElementById('bid_agree').checked==false)
        err.push('Вы не согласились с правилами аукциона');
    if(err.length){
        var mss = err.join(';\n')+'.';
        alert(mss);
        return false;
    }
    return true;
}
</script>