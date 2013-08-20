<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage Manufacturer
* @author Patrick Kohl
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 3617 2011-07-05 12:55:12Z enytheme $
*/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>
<?php echo $this->langList; ?>
<div class="col50">
	<fieldset>
	<legend><?php echo JText::_('COM_VIRTUEMART_MANUFACTURER_DETAILS'); ?></legend>
	<table class="admintable">

		<?php echo VmHTML::row('input','COM_VIRTUEMART_MANUFACTURER_NAME','mf_name',$this->manufacturer->mf_name); ?>
	    	<?php echo VmHTML::row('booleanlist','COM_VIRTUEMART_PUBLISH','published',$this->manufacturer->published); ?>
		<?php echo VmHTML::row('input',$this->viewName.' '. JText::_('COM_VIRTUEMART_SLUG'),'slug',$this->manufacturer->slug); ?>
		<?php echo VmHTML::row('select','COM_VIRTUEMART_MANUFACTURER_CATEGORY_NAME','virtuemart_manufacturercategories_id',$this->manufacturerCategories,$this->manufacturer->virtuemart_manufacturercategories_id,'','virtuemart_manufacturercategories_id', 'mf_category_name',false); ?>
		<?php echo VmHTML::row('input','COM_VIRTUEMART_MANUFACTURER_URL','mf_url',$this->manufacturer->mf_url); ?>
		<?php echo VmHTML::row('input','COM_VIRTUEMART_MANUFACTURER_EMAIL','mf_email',$this->manufacturer->mf_email); ?>
		<?php echo VmHTML::row('editor','COM_VIRTUEMART_MANUFACTURER_DESCRIPTION','mf_desc',$this->manufacturer->mf_desc); ?>
            
            <fieldset style="background-color:#F9F9F9;">
				<legend><?php echo JText::_('COM_VIRTUEMART_RELATED_PRODUCTS'); ?></legend>
				<?php echo JText::_('COM_VIRTUEMART_PRODUCT_RELATED_SEARCH'); ?>
				<div class="jsonSuggestResults" style="width: auto;">
					<input type="text" size="40" name="search" id="relatedproductsSearch" value="" />
					<button class="reset-value"><?php echo JText::_('COM_VIRTUEMART_RESET') ?></button>
				</div>
				<div id="custom_products">
                                     <? $i=0; foreach($this->accessories as $accessory) { ?>        
                                
                                    <div class="vm_thumb_image">
				
                               
                                <span><input type="hidden" value="<?= $accessory['custom_value'] ?>" name="field[<?= $i ?>][custom_value]">
                                    <a href="/administrator/index.php?option=com_virtuemart&amp;view=product&amp;task=edit&amp;virtuemart_product_id=<?= $accessory['custom_value'] ?>" title="">
                                        <?= $accessory['prod']->images[0]->displayMediaThumb('class="prod-image"',false); ?>
                                        <br> <?= $accessory['prod']->product_name ?>
                                    </a>
                                </span>
				
                                <input type="hidden" value="<?= $accessory['field_type'] ?>" name="field[<?= $i ?>][field_type]">
                                <input type="hidden" value="<?= $accessory['virtuemart_custom_id'] ?>" name="field[<?= $i ?>][virtuemart_custom_id]">
                                <input type="hidden" value="<?= $accessory['virtuemart_customfield_id'] ?>" name="field[<?= $i ?>][virtuemart_customfield_id]">
                                <input type="hidden" value="0" checked="checked" name="field[<?= $i ?>][admin_only]">
				<div class="vmicon vmicon-16-remove"></div></div>
                                <? ++$i; } ?>
                                    
                                    
                                    
                                </div>
			</fieldset>
            <fieldset style="background-color:#F9F9F9;">
				<legend><?php echo JText::_('Подарок'); ?></legend>
				<?php echo JText::_('COM_VIRTUEMART_PRODUCT_RELATED_SEARCH'); ?>
				<div class="jsonSuggestResults" style="width: auto;">
					<input type="text" size="40" name="search" id="giftSearch" value="" />
					<button class="reset-value"><?php echo JText::_('COM_VIRTUEMART_RESET') ?></button>
				</div>
				<div id="gift">
                                    <? if($this->gift) { ?>
                                 <div class="vm_thumb_image">
				
                               
                                <span><input type="hidden" value="<?= $this->gift->virtuemart_product_id ?>" name="gift[]">
                                    <a href="/administrator/index.php?option=com_virtuemart&amp;view=product&amp;task=edit&amp;virtuemart_product_id=<?= $this->gift->virtuemart_product_id ?>" title="">
                                        <?= $this->gift->images[0]->displayMediaThumb('class="prod-image"',false); ?>
                                        <br> <?= $this->gift->product_name ?>
                                    </a>
                                </span>
				
				<div class="vmicon vmicon-16-remove"></div></div>
                                    <? } ?>
                                    
                                    <?php echo  $tables['products']; ?>
                                </div>
			</fieldset>

	</table>
	</fieldset>
</div>

<script type="text/javascript">
var nextCustom =<?= $i ?>;
jQuery(document).ready(function(){
jQuery('input#relatedproductsSearch').autocomplete({

		source: 'index.php?option=com_virtuemart&view=product&task=getData&format=json&type=relatedproducts&row='+nextCustom,
		select: function(event, ui){
			jQuery("#custom_products").append(ui.item.label);
			nextCustom++;
			jQuery(this).autocomplete( "option" , 'source' , 'index.php?option=com_virtuemart&view=product&task=getData&format=json&type=relatedproducts&row='+nextCustom )
			jQuery('input#relatedproductsSearch').autocomplete( "option" , 'source' , 'index.php?option=com_virtuemart&view=product&task=getData&format=json&type=relatedproducts&row='+nextCustom )
		},
		minLength:1,
		html: true
	});
jQuery('input#giftSearch').autocomplete({

		source: 'index.php?option=com_virtuemart&view=product&task=getData&format=json&type=gift&row='+nextCustom,
		select: function(event, ui){
			jQuery("#gift").append(ui.item.label);
			nextCustom++;
			jQuery(this).autocomplete( "option" , 'source' , 'index.php?option=com_virtuemart&view=product&task=getData&format=json&type=gift&row='+nextCustom )
			jQuery('input#giftSearch').autocomplete( "option" , 'source' , 'index.php?option=com_virtuemart&view=product&task=getData&format=json&type=gift&row='+nextCustom )
		},
		minLength:1,
		html: true
	});
});
</script>