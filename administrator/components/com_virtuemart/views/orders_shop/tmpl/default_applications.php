<?php
// Секция Заявки на покупку предметов (магазина)
defined('_JEXEC') or die('Restricted access'); ?>
<?php echo $this->langList;
?>
<fieldset>
<?php

$this->purchases->makePurchasesTable();

?>
</fieldset>


