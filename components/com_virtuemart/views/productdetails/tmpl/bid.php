<hr/>
<h3 id="header_bid" class="clearfix">
    <img src="<?php echo JUri::base();?>templates/auction/images/auction_hammer_gavel_big.png">
    <span>Сделать ставку</span>
</h3>
<form onsubmit="return checkFormFields()">
<p id="bid-sum-p">Сумма вашей ставки: <input id="bid_sum" type="text" name="bid_sum" size="12"/></p>
<input type="checkbox" id="bid_agree" name="bid_agree"/>я согласен с правилами аукциона.
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