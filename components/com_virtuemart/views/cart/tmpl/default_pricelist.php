<?php defined ('_JEXEC') or die('Restricted access');
/**
 *
 * Layout for the shopping cart
 *
 * @package    VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 * @author Patrick Kohl
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 *
 */

// Check to ensure this file is included in Joomla!

// jimport( 'joomla.application.component.view');
// $viewEscape = new JView();
// $viewEscape->setEscape('htmlspecialchars');

?> <?/*
<div class="billto-shipto">
	<div class="width50 floatleft">

		<span><span class="vmicon vm2-billto-icon"></span>
			<?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_LBL'); ?></span>
		<?php // Output Bill To Address ?>
		<div class="output-billto">
			<?php

			foreach ($this->cart->BTaddress['fields'] as $item) {
				if (!empty($item['value'])) {
					if ($item['name'] === 'agreed') {
						$item['value'] = ($item['value'] === 0) ? JText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_NO') : JText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_YES');
					}
					?><!-- span class="titles"><?php echo $item['title'] ?></span -->
					<span class="values vm2<?php echo '-' . $item['name'] ?>"><?php echo $this->escape ($item['value']) ?></span>
					<?php if ($item['name'] != 'title' and $item['name'] != 'first_name' and $item['name'] != 'middle_name' and $item['name'] != 'zip') { ?>
						<br class="clear"/>
						<?php
					}
				}
			} ?>
			<div class="clear"></div>
		</div>

		<a class="details" href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT', $this->useXHTML, $this->useSSL) ?>">
			<?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL'); ?>
		</a>

		<input type="hidden" name="billto" value="<?php echo $this->cart->lists['billTo']; ?>"/>
	</div>

	<div class="width50 floatleft">

		<span><span class="vmicon vm2-shipto-icon"></span>
			<?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_SHIPTO_LBL'); ?></span>
		<?php // Output Bill To Address ?>
		<div class="output-shipto">
			<?php
			if (empty($this->cart->STaddress['fields'])) {
				echo JText::sprintf ('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_EXPLAIN', JText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL'));
			} else {
				if (!class_exists ('VmHtml')) {
					require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'html.php');
				}
				echo JText::_ ('COM_VIRTUEMART_USER_FORM_ST_SAME_AS_BT');
				echo VmHtml::checkbox ('STsameAsBTjs', $this->cart->STsameAsBT) . '<br />';
				?>
				<div id="output-shipto-display">
					<?php
					foreach ($this->cart->STaddress['fields'] as $item) {
						if (!empty($item['value'])) {
							?>
							<!-- <span class="titles"><?php echo $item['title'] ?></span> -->
							<?php
							if ($item['name'] == 'first_name' || $item['name'] == 'middle_name' || $item['name'] == 'zip') {
								?>
								<span class="values<?php echo '-' . $item['name'] ?>"><?php echo $this->escape ($item['value']) ?></span>
								<?php } else { ?>
								<span class="values"><?php echo $this->escape ($item['value']) ?></span>
								<br class="clear"/>
								<?php
							}
						}
					}
					?>
				</div>
				<?php
			}
			?>
			<div class="clear"></div>
		</div>
		<?php if (!isset($this->cart->lists['current_id'])) {
		$this->cart->lists['current_id'] = 0;
	} ?>
		<a class="details" href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=ST&virtuemart_user_id[]=' . $this->cart->lists['current_id'], $this->useXHTML, $this->useSSL) ?>">
			<?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL'); ?>
		</a>

	</div>

	<div class="clear"></div>
</div> */?>

<fieldset>
<table
	class="cart-summary"
	cellspacing="0"
	cellpadding="0"
	border="1"
	width="100%">
<tr>
        <th> № п/п </th>
        <th align="left"><?php echo JText::_ ('COM_VIRTUEMART_CART_SKU') ?></th>
        <th align="left"><?php echo JText::_ ('COM_VIRTUEMART_CART_NAME') ?></th>
	<th
		align="right"
		width="140px"><?php echo JText::_ ('COM_VIRTUEMART_CART_QUANTITY') ?>	</th>
	<th
		align="right"
		width="140px"><?php echo JText::_ ('Кол-во леманий') ?>	</th>
	<th
		align="center"
		width="60px"><?php echo JText::_ ('COM_VIRTUEMART_CART_PRICE') ?></th>

		 


	<th align="right" width="60px"><?php echo "<span  class='priceColor2'>" . JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?></th>
	<th align="right" width="70px"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?></th>
</tr>



<?php
$i = 1; $j=1;
// 		vmdebug('$this->cart->products',$this->cart->products);
foreach ($this->cart->products as $pkey => $prow) {
	?>
<tr valign="top" class="sectiontableentry<?php echo $i; ?>">
    <td> <?php echo $prow->images[0]->displayMediaThumb("",false); ?>
    <td align="left"><?php  echo $prow->product_sku ?></td>
	<td align="left">
		<?php echo JHTML::link ($prow->url, $prow->product_name) . $prow->customfields; ?>

	</td>
       
        <td>
            <?php echo $prow->quantity ?>
        </td> <?php if($j==1) { ?>
        <td rowspan="<?= count($this->cart->products); ?>">
            <?php $lemanies=(string)(round($this->cart->pricesUnformatted['salesPrice']/5));
             for($i=0;$i<strlen($lemanies);++$i) { $c=$lemanies[$i]; ?> 
                <img src="<?= JURI::base(true)."components/com_virtuemart/assets/images/numbers/$c.png" ?>" /> <?php } ?>
        </td> <?php } ?>
        
	<td align="right">
            <?= $this->currencyDisplay->createPriceDiv ('priceWithoutTax', '', $this->cart->pricesUnformatted[$pkey],'', FALSE, FALSE, $prow->quantity) ?>
	</td>
        
        <? //var_dump($this->cart->pricesUnformatted[$pkey]['discountAmount']); die(); ?>

	<td align="right"><?php echo "<span class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('discountAmount', '', $this->cart->pricesUnformatted[$pkey],'', FALSE, FALSE, $prow->quantity) . "</span>" ?></td>
	<td colspan="1" align="right">
		<?php
		if (VmConfig::get ('checkout_show_origprice', 1) && !empty($this->cart->pricesUnformatted[$pkey]['basePriceWithTax']) && $this->cart->pricesUnformatted[$pkey]['basePriceWithTax'] != $this->cart->pricesUnformatted[$pkey]['salesPrice']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceWithTax', '', $this->cart->pricesUnformatted[$pkey], TRUE, FALSE, $prow->quantity) . '</span><br />';
		}
		echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->pricesUnformatted[$pkey], FALSE, FALSE, $prow->quantity) ?></td>
</tr>
	<?php ++$j;
	$i = ($i==1) ? 2 : 1;
} ?>
<!--Begin of SubTotal, Tax, Shipment, Coupon Discount and Total listing -->
<?php if (VmConfig::get ('show_tax')) {
	$colspan = 3;
} else {
	$colspan = 2;
} ?>

<tr class="sectiontableentry1">
	<td colspan="7" align="right"><?php echo JText::_ ('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL'); ?></td>

	<td align="right"><?php echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->pricesUnformatted, FALSE) ?></td>
</tr>


<? /*
<tr class="sectiontableentry1">
	<?php if (!$this->cart->automaticSelectedShipment) { ?>
				<td colspan="4" align="left">
				<?php echo $this->cart->cartData['shipmentName']; ?>
	<br/>
	<?php
	if (!empty($this->layoutName) && $this->layoutName == 'default' && !$this->cart->automaticSelectedShipment) {
		echo JHTML::_ ('link', JRoute::_ ('index.php?view=cart&task=edit_shipment', $this->useXHTML, $this->useSSL), $this->select_shipment_text, 'class=""');
	} else {
		JText::_ ('COM_VIRTUEMART_CART_SHIPPING');
	}
} else {
	?>
	<td colspan="4" align="left">
		<?php echo $this->cart->cartData['shipmentName']; ?>
	</td>
	<?php } ?>

	<?php if (VmConfig::get ('show_tax')) { ?>
	<td align="right"><?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('shipmentTax', '', $this->cart->pricesUnformatted['shipmentTax'], FALSE) . "</span>"; ?> </td>
	<?php } ?>
	<td></td>
	<td align="right"><?php echo $this->currencyDisplay->createPriceDiv ('salesPriceShipment', '', $this->cart->pricesUnformatted['salesPriceShipment'], FALSE); ?> </td>
</tr>
<?php if ($this->cart->pricesUnformatted['salesPrice']>0.0 ) { ?>
<tr class="sectiontableentry1">
	<?php if (!$this->cart->automaticSelectedPayment) { ?>

	<td colspan="4" align="left">
		<?php echo $this->cart->cartData['paymentName']; ?>
		<br/>
		<?php if (!empty($this->layoutName) && $this->layoutName == 'default') {
		echo JHTML::_ ('link', JRoute::_ ('index.php?view=cart&task=editpayment', $this->useXHTML, $this->useSSL), $this->select_payment_text, 'class=""');
	} else {
		JText::_ ('COM_VIRTUEMART_CART_PAYMENT');
	} ?> </td>

	</td>
	<?php } else { ?>
	<td colspan="4" align="left"><?php echo $this->cart->cartData['paymentName']; ?> </td>
	<?php } ?>
	<?php if (VmConfig::get ('show_tax')) { ?>
	<td align="right"><?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('paymentTax', '', $this->cart->pricesUnformatted['paymentTax'], FALSE) . "</span>"; ?> </td>
	<?php } ?>
	<td align="right"><?php // Why is this commented? what is with payment discounts? echo "<span  class='priceColor2'>".$this->cart->pricesUnformatted['paymentDiscount']."</span>"; ?></td>
	<td align="right"><?php  echo $this->currencyDisplay->createPriceDiv ('salesPricePayment', '', $this->cart->pricesUnformatted['salesPricePayment'], FALSE); ?> </td>
</tr>
<?php } ?>
<tr>
	<td colspan="4">&nbsp;</td>
	<td colspan="<?php echo $colspan ?>">
		<hr/>
	</td>
</tr>
<tr class="sectiontableentry2">
	<td colspan="4" align="right"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?>:</td>

	<?php if (VmConfig::get ('show_tax')) { ?>
	<td align="right"> <?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('billTaxAmount', '', $this->cart->pricesUnformatted['billTaxAmount'], FALSE) . "</span>" ?> </td>
	<?php } ?>
	<td align="right"> <?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('billDiscountAmount', '', $this->cart->pricesUnformatted['billDiscountAmount'], FALSE) . "</span>" ?> </td>
	<td align="right"><strong><?php echo $this->currencyDisplay->createPriceDiv ('billTotal', '', $this->cart->pricesUnformatted['billTotal'], FALSE); ?></strong></td>
</tr>
<?php
if ($this->totalInPaymentCurrency) {
?>

<tr class="sectiontableentry2">
	<td colspan="4" align="right"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL_PAYMENT') ?>:</td>

	<?php if (VmConfig::get ('show_tax')) { ?>
	<td align="right"></td>
	<?php } ?>
	<td align="right"></td>
	<td align="right"><strong><?php echo $this->totalInPaymentCurrency;   ?></strong></td>
</tr>
	<?php
}
 */?>


</table>
</fieldset>
