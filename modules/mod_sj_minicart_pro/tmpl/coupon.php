<?php
/**
 * @package Sj MiniCart Pro
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die; ?>

<?php 
	if(class_exists('VmConfig') && VmConfig::get('coupons_enable')== 1){
	$display[0] = "";
	$display[1] = "";

	if ( !empty($cart->couponCode) ){
		$display[0] = 'style="display:none;"';
	} else {
		$display[1] = 'style="display:none;"';
	}

?>

	<div class="mc-coupon">
		<?php if($options->coupon_label_display==1) { ?>
		<div class="coupon-title">
			<?php echo JText::_('COUPON_LABEL'); ?>
		</div>
		<?php } ?>
		<div class="coupon-input" <?php echo $display[0]; ?> >
			<input type="text" size="20"  name="coupon-code" onfocus="if(this.value=='Enter your Coupon code') this.value='';" onblur="if(this.value=='') this.value='Enter your Coupon code';" value="Enter your Coupon code" class="coupon-code"  >
			<div class="coupon-button-add">Add</div>
		</div>	
		<div class="coupon-label" <?php echo $display[1]; ?>>
			<span class="coupon-text"><?php echo $cart->couponCode; ?></span>
			<span class="coupon-close"></span>
		</div>
		<div class="coupon-message" >Coupon code invalid! Please re-enter!</div>
		<div class="preloader" ><img src="<?php echo JURI::base() .'modules/'.$module->module.'/assets/images/89.gif'; ?>" alt="AJAX loader" title="AJAX loader" /></div>
	</div>

<?php } ?>