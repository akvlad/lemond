<?php
/**
 * @package Sj MiniCart Pro
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die;

	$image_config = array(
		'output_width'  => $params->get('item_image_width',  100),
		'output_height' => $params->get('item_image_height', 100),
		'function'		=> $params->get('item_image_function', 'resize_none'),
		'background'	=> $params->get('item_image_background', null)
	); 
	$vm_currency_display = &CurrencyDisplay::getInstance();
	$options = $params->toObject();
        $summ=0;
        foreach ($cart->products as $product){
            $summ+=$product->quantity;
        }
?>
<?= $summ; ?>
<? /*<div class="mc-product-wrap">
	<?php   foreach($cart->products as $cart_item_id => $product) { ?>
	<div class="mc-product mc-product-<?php echo $product->product_sku ?>">
		<div class="mc-product-inner">
			<span style="visibility: hidden;position: absolute;top: -9999em;" class="product-id"><?php echo  $cart_item_id;  ?></span>
			<div class="mc-image" style="width:<?php echo $options->item_image_width ?>px; height:<?php  echo $options->item_image_height?>px;">
				<?php $product_image = !empty($product->images[0]->file_url_thumb) ?$product->images[0]->file_url:'modules/'.$module->module.'/assets/images/nophoto.png';	?>				         
				<a href="<?php echo $product->link ?>" title="<?php echo $product->product_name; ?>">
					<img src="<?php echo Ytools::resize($product_image, $image_config);?>" alt="<?php echo $product->product_name ?>" title="<?php echo $product->product_name ?>" >
				</a>
			</div>
			<div class="mc-attribute">
				<div class="attr-name attr">
					<span class="label">
						<?php echo JText::_('PRODUCT_NAME_LABLE'); ?>
					</span>
					<span class="value">
						<a href="<?php echo $product->link ?>" title="<?php echo $product->product_name; ?>">
						<?php echo  $product->product_name; ?>
						</a>
					</span>
				</div>
				<?php if($options->product_attribute_display==1){
					$match_count = preg_match_all('#(\d+):(\d+);#', $cart_item_id, $matchs);
					$cvp = array(); 
					if ($match_count){
						for($i = 0; $i < $match_count; $i++){
							if(version_compare(vmVersion::$RELEASE, '2.0.7', 'lt')){
								$cvp[ $matchs[1][$i] ] = $matchs[2][$i];
							}else{
								$cvp[ $matchs[2][$i] ] = $matchs[1][$i];
							}
						}
					}
					$customfields = VmModel::getModel ('Customfields');
					$product->customfieldsCart = $customfields->getProductCustomsFieldCart($product);
					foreach($product->customfieldsCart as $f){
						if (array_key_exists($f->virtuemart_custom_id, $cvp) && is_array($f->options)){
							$option_id = $cvp[$f->virtuemart_custom_id];
							 if ( isset($f->options[$option_id]) ){ ?>	
								<div class="attr-<?php echo $f->custom_title ;?> attr">
									 <span class="label">
										<?php echo ucfirst($f->custom_title) ;?> :
									</span>
									
									<span class="value" >
										<?php echo ucfirst($f->options[$option_id]->custom_value);; ?>
									</span>	
										
								</div>		
					<?php	 }
						}
					} 
				} ?>
				<div class="attr-quantity attr">
					<span class="label">
						<?php echo JText::_('PRODUCT_QUANTITY_LABEL'); ?>
					</span>
					<span class="value">
						<input type="text" maxlength="4" size="2" name="mc-quantity" class="mc-quantity" value="<?php echo $product->quantity ?>" />
					</span>
				</div>
				<div class="attr-price attr">
					<span class="label">
						<?php echo JText::_('PRODUCT_PRICE_LABEL'); ?>
					</span>
					
					<span class="value">
					<?php 
						echo $vm_currency_display->priceDisplay($cart->pricesUnformatted[$cart_item_id]['subtotal_with_tax']);
					?>		
					</span>
					
				</div>
				<div class="mc-remove">
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>	
</div> */?>

	
	