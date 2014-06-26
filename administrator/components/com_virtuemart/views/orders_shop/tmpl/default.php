<?php
defined('_JEXEC') or die('Restricted access');
AdminUIHelper::startAdminArea();
$document = JFactory::getDocument();
vmJsApi::JvalideForm();?>
    <form method="post" name="adminForm" action="index.php" enctype="multipart/form-data" id="adminForm">
        <?php // Loading Templates in Tabs
        $tabarray = array();
        $tabarray['purchases'] = 'COM_VIRTUEMART_SHOP_ITEM_PURCHASES';
        $tabarray['applications'] = 'COM_VIRTUEMART_SHOP_ITEM_APPLICATIONS';
        AdminUIHelper::buildTabs ( $this, // VirtuemartViewProduct
            $tabarray,
            $this->product->virtuemart_product_id );
        ?>
        <?php echo $this->addStandardHiddenToForm(); ?>
        <input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id; ?>" />
        <input type="hidden" name="product_parent_id" value="<?php echo JRequest::getInt('product_parent_id', $this->product->product_parent_id); ?>" />
    </form>
<?php AdminUIHelper::endAdminArea();?>
<script>
$(function(){
    $('.cmd_apply, .cmd_cancel').on('click', function(){
        var Tr = $(this).parent('tr');
        var cell = this;
        var altName = (this.className=='cmd_apply')?
                'cmd_cancel' : 'cmd_apply';
        $.post( "index.php?option=com_virtuemart&view=orders_shop&task=handlePurchase",
            {
                virtuemart_product_id: $(Tr).attr('data-product_id')
            },
            function (data){
                $(Tr).fadeOut(300, function(){
                    $('#tbody_'+altName+' tr:first-child').after(Tr);
                    $(Tr).fadeIn();
                    cell.className = altName;
                }); //console.log(data);
            }
        );
    });
});
</script>