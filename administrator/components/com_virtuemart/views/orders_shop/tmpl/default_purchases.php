<?php
// Секция Информация / Информация о товаре id: $товар_id
defined('_JEXEC') or die('Restricted access'); ?>
<?php echo $this->langList;?>
<fieldset>
<?php

$this->purchases->makePurchasesTable(true);

?>
</fieldset>