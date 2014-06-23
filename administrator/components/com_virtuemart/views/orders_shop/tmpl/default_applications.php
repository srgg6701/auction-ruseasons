<?php
// Секция Заявки на покупку предметов (магазина)
defined('_JEXEC') or die('Restricted access'); ?>
<?php echo $this->langList;
// include_once JPATH_SITE.DS.'tests.php';
commonDebug(__FILE__,__LINE__,AuctionStuff::getPurchases());?>
<fieldset>
	<legend>Неподтверждённые заявки</legend>
    <table width="100%" class="adminform">
    <?php
        foreach(range(1,10) as $i=>$y):
    ?>
        <tr class="row<?php echo $i%2?>">
            <td><?php echo $i;?></td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
        </tr>
    <?php
        endforeach;?>
    </table>
</fieldset>


