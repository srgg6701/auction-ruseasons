<hr/>
<h3 id="header_bid" class="clearfix">
    <img src="<?php echo JUri::base();?>templates/auction/images/auction_hammer_gavel_big.png">
    <span>Сделать ставку</span>
</h3>
<form method="post" action="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.makeBid'); ?>" onsubmit="return checkFormFields()">
	<section id="bid_step_1">
    <?php
    $options = HTML::buildBidsSelect($this->product->virtuemart_product_id,$this->product->prices['basePriceVariant']);
    ?>
    <select name="bids" id="sel_bids">
        <option value="0">-Укажите свою ставку-</option>
    <?php   echo $options;
    ?>
    </select>
        &nbsp;
    <button id="bid_next" type="button" class="buttonSandCool">Продолжить</button>
    </section>
	<section id="bid_step_2" style="display: none;">
    <p>Чтобы начать торг за интересующий лот, нажмите кнопку «Сделать ставку».
        Участник имеет возможность сделать заочный бид, указав свою максимальную цену
        (данная цена не включает комиссионный сбор).
        В этом случае система автоматически будет делать ставки за покупателя пошагово
        до указанной им суммы. При одинаковых ставках выигрывает тот, кто сделал ставку
        раньше.  Заочный бид можно поставить только с момента начала аукциона. Делать
        ставки возможно как напрямую из каталога (после ставки данный предмет
        автоматически переносится в «Ваш кабинет»), так и из «кабинета», предварительно
        отсортировав туда интересующие лоты. Информация обо всех совершенных сделках
        хранится в «кабинете» и при необходимости может быть удалена.</p>
    <p>Ваша ставка: <b id="my_bid"></b> руб.
        <a href="javascript:void(0)" id="bid_cancel" class="floatright">^ Вернуться к лоту</a>
    </p>
<!--<input type="checkbox" id="bid_agree" name="bid_agree"/>я согласен с правилами аукциона.-->
<?php echo JHtml::_('form.token');
?>
<input type="hidden" name="option" value="com_auction2013"/>
<input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id;?>"/>
<input type="hidden" name="task" value="auction2013.makeBid"/>
<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>"/>
<input type="hidden" name="virtuemart_category_id" value="<?php echo $virtuemart_category_id;?>"/>
<button type="submit" class="buttonSandCool">Сделать ставку</button>
	</section>
</form>
<script>
function switchSections(step){
    var alt_step = (step==1)? 2:1;
    jQuery('#bid_step_'+step).fadeOut(300, function(){
        jQuery('#bid_step_'+alt_step).fadeIn(300);
    });
}
(function($){
	$('#bid_next').on('click', function(){
        var my_bid=$('#sel_bids option:selected').val();
        if(my_bid==0){
            alert('Вы не указали ставку!');
            return false;
        }else{
            $('#my_bid').html(my_bid);
        }
        switchSections(1);
    });
    $('#bid_cancel').on('click', function(){
        switchSections(2);
    });
}(jQuery));
</script>