<?php
/**
*
* Order items view
*
* @package	VirtueMart
* @subpackage Orders
* @author Oscar van Eijk, Valerie Isaksen
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: details_items.php 5836 2012-04-09 13:13:21Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if($this->format == 'pdf'){
	$widthTable = '100';
	$widtTitle = '27';
} else {
	$widthTable = '100';
	$widtTitle = '49';
}

?>
<table width="<?php echo $widthTable ?>%" cellspacing="0" cellpadding="0" border="0" class="see_order_table">
	<tr align="left" class="sectiontableheader">
        <th class="th_number" >№ П/П</th>
		<th class="th_article" ><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SKU') ?></th>
		<th class="th_name"colspan="2"<?php echo JText::_('COM_VIRTUEMART_PRODUCT_NAME_TITLE') ?></th>
        <th class="th_count"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_QTY') ?></th>
		<th class="th_price"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PRICE') ?></th>
		<th class="th_discount"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SUBTOTAL_DISCOUNT_AMOUNT') ?></th>
		<th class="th_price"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') ?></th>
	</tr>
<?php $i=0;
	foreach($this->orderdetails['items'] as $item) { ++$i;
		$qtt = $item->product_quantity ;
		$_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $item->virtuemart_category_id . '&virtuemart_product_id=' . $item->virtuemart_product_id);
?>
		<tr valign="top">
            <td align="left" class="td_number" >
                <?php echo $i; ?>
            </td>
			<td align="left" class="td_article" >
				<?php echo $item->order_item_sku; ?>
			</td>
			<td align="left" colspan="2" class="td_name">
				<a href="<?php echo $_link; ?>"><?php echo $item->order_item_name; ?></a>
				<?php
// 				vmdebug('tmpl details_item $item',$item);
					if (!empty($item->product_attribute)) {
							if(!class_exists('VirtueMartModelCustomfields'))require(JPATH_VM_ADMINISTRATOR.DS.'models'.DS.'customfields.php');
							$product_attribute = VirtueMartModelCustomfields::CustomsFieldOrderDisplay($item,'FE');
						echo $product_attribute;
					}
				?>
			</td>
            <td align="right"  class="th_count">
                <?php echo $qtt; ?>
            </td>
			<td align="right"   class="priceCol td_price"  >
			    <?php echo '<span >'.$this->currency->priceDisplay($item->product_item_price,$this->currency,
                        $quantity = 1.0,$inToShopCurrency = false,$nb= 0) .'</span><br />'; ?>
			</td>
			<td align="right" class="priceCol th_discount" >
				<?php echo  $this->currency->priceDisplay( $item->product_subtotal_discount ,$this->currency,
                    $quantity = 1.0,$inToShopCurrency = false,$nb= 0);  //No quantity is already stored with it ?>
			</td>
			<td align="right"  class="priceCol  th_price">
				<?php
				$item->product_basePriceWithTax = (float) $item->product_basePriceWithTax;
				$class = '';
				if(!empty($item->product_basePriceWithTax) && $item->product_basePriceWithTax != $item->product_final_price ) {
					echo '<span class="line-through" >'.$this->currency->priceDisplay($item->product_basePriceWithTax,$this->currency,$qtt,
                            $inToShopCurrency = false,$nb= 0) .'</span><br />' ;
				}

				echo $this->currency->priceDisplay(  $item->product_subtotal_with_tax ,$this->currency,
                    $quantity = 1.0,$inToShopCurrency = false,$nb= 0); //No quantity or you must use product_final_price ?>
			</td>
		</tr>
<?php
	}
?>
    <tr>
        <td colspan="7" class="total_order_amount_text">Всего:</td>
        <td id="total_order_amount"> <?php echo $this->currency->priceDisplay(  $this->orderdetails['details']['BT']->order_total,
                $this->currency,$quantity = 1.0,$inToShopCurrency = false,$nb= 0);?></td>
    </tr>
</table>