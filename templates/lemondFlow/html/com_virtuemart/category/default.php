<?php
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 6556 2012-10-17 18:15:30Z kkmediaproduction $
 */
//vmdebug('$this->category',$this->category);
vmdebug ('$this->category ' . $this->category->category_name);
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
JHTML::_ ('behavior.modal');
vmJsApi::js ('facebox');
vmJsApi::css ('facebox');
/* javascript for list Slide
  Only here for the order list
  can be changed by the template maker
*/

$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	);
        jQuery('.product').mouseenter(function(e){
            jQuery(e.target).closest('.product').find('.showable-medias').show();
        });
        jQuery('.product').mouseleave(function(e){
            jQuery(e.target).closest('.product').find('.showable-medias').hide();
        });
        jQuery(\"a[rel=facebox]\").facebox(function() {jQuery(\".product-carousel\").jcarousel();});
        jQuery(document).bind('reveal.facebox', function() {
            jQuery(\"#facebox .product-carousel\").jcarousel({visible: 1, scroll: 1});
        });
});

function imageClick(e,i){
    var carousel=jQuery(e.target).closest('.popup-imgs').find('.product-carousel').data('jcarousel');
    carousel.scroll(i);
}

";

$document = JFactory::getDocument ();
$document->addStyleSheet('/components/com_virtuemart/assets/js/jcarousel/skins/milkbox/skin.css');
$document->addScript('/components/com_virtuemart/assets/js/jcarousel/jquery.jcarousel.min.js');
$document->addScriptDeclaration ($js);


$js="
    jQuery(document).ready
"

/*$edit_link = '';
if(!class_exists('Permissions')) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'permissions.php');
if (Permissions::getInstance()->check("admin,storeadmin")) {
	$edit_link = '<a href="'.JURI::root().'index.php?option=com_virtuemart&tmpl=component&view=category&task=edit&virtuemart_category_id='.$this->category->virtuemart_category_id.'">
		'.JHTML::_('image', 'images/M_images/edit.png', JText::_('COM_VIRTUEMART_PRODUCT_FORM_EDIT_PRODUCT'), array('width' => 16, 'height' => 16, 'border' => 0)).'</a>';
}

echo $edit_link; */?>
<script src="/components/com_virtuemart/assets/js/fancybox/jquery.fancybox-1.3.4.js" type="text/javascript"></script>
<div class="browse-view">
<!--<h1><?php echo $this->category->category_name;?> </h1>-->
<?php if($this->parent_category){ ?>
<h1 class="cat-name<?= $this->parent_category->virtuemart_category_id ?>">
	<?php echo $this->parent_category->category_name;?></h1>
<?php } else { ?>
<span class="cat-name<?= $this->category->virtuemart_category_id ?>">
	<?php echo $this->category->category_name;?></span>
<?php } ?>
<span class="sort">Упорядочить по: <a href="<?php echo $this->orderByList; ?>">Цене</a> <a <?= $this->needShowAll ? " class=\"show-all\" href=\"$this->show_all_link\" " : "class=\"inact\"" ?> > Показать все </a> </span>

<?php // Show child categories
if (!empty($this->products)) {
	?>


	<?php
	// Category and Columns Counter
	$iBrowseCol = 1;
	$iBrowseProduct = 1;

	// Calculating Products Per Row
	$BrowseProducts_per_row = $this->perRow;
	$Browsecellwidth = ' width' . floor (100 / $BrowseProducts_per_row);

	// Separator
	$verticalseparator = " vertical-separator";

	$BrowseTotalProducts = count($this->products);
?>		<div class="horizontal-separator"></div> <?php
	// Start the Output
	foreach ($this->products as $product) {
            $needPlash=false;
            if(count($product->customfields) > 0 ){
            foreach($product->customfields as $customfield){
                if($customfield->virtuemart_custom_id==5){
                    $needPlash= true;
                    $plashClass=$customfield->custom_value;
                }
            }
            }

		// Show the horizontal seperator
		if ($iBrowseCol == 1 && $iBrowseProduct > $BrowseProducts_per_row) {
			?>

			<?php
		}

		// this is an indicator wether a row needs to be opened or not
		if ($iBrowseCol == 1) {
			?>
	<?php
		}

		// Show the vertical seperator
		if ($iBrowseProduct == $BrowseProducts_per_row or $iBrowseProduct % $BrowseProducts_per_row == 0) {
			$show_vertical_separator = ' ';
		} else {
			$show_vertical_separator = $verticalseparator;
		}

		// Show Products
		?>
		<div class="product floatleft">
                    <div class="relative-wrapper">
                        <?php /*var_dump($product->displayPlugins); */ $needVideo=isset($product->displayPlugins['vmvideo']);
                        $maxImages=4;
                        if($needVideo) $maxImages=3;
                        
                        if(count($product->images)>1 || $needVideo ){ ?> 
                        <div class="showable-medias">
                            <?php for($i=1;$i<min(count($product->images),$maxImages); ++$i) { ?>
                            <div class="showable-image">
                                <a rel="facebox" href="#images<?= $product->virtuemart_product_id ?>" >
                                <?php echo $product->images[$i]->displayMediaThumb('', false);	?>
                                </a>
                            </div>
                            <?php }?>
                            <?php if($needVideo){ ?>
                             <div class="showable-image">
                                <?= $product->displayPlugins['vmvideo']['thumb'] ?>
                            </div>                               
                            <? } ?>
                        </div>
                        <?php } 
                        if ($needPlash) { ?>
                        <div class="plash <?=$plashClass ?>"></div>
                        <?php } ?>
                        
                        <div class="spacer">
				<div class="prod-img">
				    <a title="<?php echo $product->link ?>" rel="vm-additional-images" href="<?php echo $product->link; ?>">
						<?php echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false);	?>
					 </a>

					<!-- The "Average Customer Rating" Part -->
					<?php if ($this->showRating) { ?>
					<span class="contentpagetitle"><?php echo JText::_ ('COM_VIRTUEMART_CUSTOMER_RATING') ?>:</span>
					<br/>
					<?php
					// $img_url = JURI::root().VmConfig::get('assets_general_path').'/reviews/'.$product->votes->rating.'.gif';
					// echo JHTML::image($img_url, $product->votes->rating.' '.JText::_('COM_VIRTUEMART_REVIEW_STARS'));
					// echo JText::_('COM_VIRTUEMART_TOTAL_VOTES').": ". $product->votes->allvotes;
					?>
					<?php } ?>
 					<?php
						if ( VmConfig::get ('display_stock', 1)) { ?>
						<!-- 						if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none')){?> -->
						<div class="paddingtop8">
							<span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
							<span class="stock-level"><?php echo JText::_ ('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_TITLE_TIP') ?></span>
						</div>
						<?php } ?>
				</div>

				<div class="prod-name">

					<?php echo JHTML::link ($product->link, $product->product_name); ?>

					<?php // Product Short Description
					if (!empty($product->product_s_desc)) {
						?>
						<p class="product_s_desc">
							<?php echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 40, '...') ?>
						</p>
						<?php } ?>

					<div class="product-price marginbottom12" id="productPrice<?php echo $product->virtuemart_product_id ?>">
						<?php
						if ($this->show_prices == '1') {
							if ($product->prices['salesPrice']<=0 and VmConfig::get ('askprice', 1) and  !$product->images[0]->file_is_downloadable) {
								echo JText::_ ('COM_VIRTUEMART_PRODUCT_ASKPRICE');
							}
							//todo add config settings
							if ($this->showBasePrice) {
								echo $this->currency->createPriceDiv ('basePrice', 'COM_VIRTUEMART_PRODUCT_BASEPRICE', $product->prices);
								echo $this->currency->createPriceDiv ('basePriceVariant', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_VARIANT', $product->prices);
							}
							//echo $this->currency->createPriceDiv ('variantModification', 'COM_VIRTUEMART_PRODUCT_VARIANT_MOD', $product->prices);
                                                        //echo round($product->prices['basePrice'],$this->currency->_priceConfig['salesPrice'][1]).' '.$product->prices['salesPrice'];
							if (round($product->prices['basePrice'],2) != round($product->prices['salesPrice'],2)) {?>
                                                            <canvas id="DiscountCanvas<?= $product->virtuemart_product_id ?>" width="95" height="20">
                                                            </canvas>
                                                            <script type="text/javascript">
                                                                var cnv=document.getElementById('DiscountCanvas<?= $product->virtuemart_product_id ?>');
                                                                var ctx=cnv.getContext("2d");
                                                                var gradient=ctx.createLinearGradient(0,0,0,15);
                                                                gradient.addColorStop(0.0, '#ff0000');
                                                                gradient.addColorStop(0.9, '#9A0027');
                                                                ctx.fillStyle = gradient;
                                                                ctx.font = "bold 15px Microsoft YaHei";
                                                                ctx.fillText("<?= round($product->prices['salesPrice'],2)?> грн.", 0, 15);
                                                            </script>
                                                            <? //echo $this->currency->createPriceDiv ('salesPrice', 'redprice', $product->prices);
                                                             echo $this->currency->createPriceDiv ('priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $product->prices);
							}
                                                        else echo $this->currency->createPriceDiv ('salesPrice', ';kl', $product->prices,'noRed');
							if (round($product->prices['salesPriceWithDiscount'],$this->currency->_priceConfig['salesPrice'][1]) != $product->prices['salesPrice']) {
								echo $this->currency->createPriceDiv ('salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $product->prices);
							}
							
							
							echo $this->currency->createPriceDiv ('discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $product->prices);
							echo $this->currency->createPriceDiv ('taxAmount', 'COM_VIRTUEMART_PRODUCT_TAX_AMOUNT', $product->prices);
							$unitPriceDescription = JText::sprintf ('COM_VIRTUEMART_PRODUCT_UNITPRICE', $product->product_unit);
							echo $this->currency->createPriceDiv ('unitPrice', $unitPriceDescription, $product->prices);
						} ?>

					</div>


				</div>	
                        
                        <form method="post" class="product js-recalculate" action="<?php echo JRoute::_ ('index.php'); ?>">
                <input name="quantity" type="hidden" value="1" />
		<?php // Product custom_fields
		if (!empty($product->customfieldsCart)) {
			?>
			<div class="product-fields">
				<?php foreach ($product->customfieldsCart as $field) { ?>
				<div class="product-field product-field-type-<?php echo $field->field_type ?>">
					<span class="product-fields-title-wrapper"><span class="product-fields-title"><strong><?php echo JText::_ ($field->custom_title) ?></strong></span>
					<?php if ($field->custom_tip) {
					echo JHTML::tooltip ($field->custom_tip, JText::_ ($field->custom_title), 'tooltip.png');
				} ?></span>
					<span class="product-field-display"><?php echo $field->display ?></span>

					<span class="product-field-desc"><?php echo $field->custom_field_desc ?></span>
				</div><br/>
				<?php
			}
				?>
			</div>
			<?php
		}
		/* Product custom Childs
			 * to display a simple link use $field->virtuemart_product_id as link to child product_id
			 * custom_value is relation value to child
			 */

		if (!empty($product->customsChilds)) {
			?>
			<div class="product-fields">
				<?php foreach ($product->customsChilds as $field) { ?>
				<div class="product-field product-field-type-<?php echo $field->field->field_type ?>">
					<span class="product-fields-title"><strong><?php echo JText::_ ($field->field->custom_title) ?></strong></span>
					<span class="product-field-desc"><?php echo JText::_ ($field->field->custom_value) ?></span>
					<span class="product-field-display"><?php echo $field->display ?></span>

				</div><br/>
				<?php } ?>
			</div>
			<?php }

		if (!VmConfig::get('use_as_catalog', 0) and !empty($product->prices['salesPrice'])) {
		?>

		<div class="addtocart-bar">

<script type="text/javascript">
<?php /*		function check(obj) {
 		// use the modulus operator '%' to see if there is a remainder
		remainder=obj.value % <?php echo $step?>;
		quantity=obj.value;
 		if (remainder  != 0) {
 			alert('<?php echo $alert?>!');
 			obj.value = quantity-remainder;
 			return false;
 			}
 		return true;
 		} */ ?>
</script> 

			<?php // Display the quantity box

			$stockhandle = VmConfig::get ('stockhandle', 'none');
			if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) {
				?>
				<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="notify"><?php echo JText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a>

				<?php } else { ?>
				<!-- <label for="quantity<?php echo $product->virtuemart_product_id; ?>" class="quantity_box"><?php echo JText::_ ('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label> -->
				<span class="quantity-box">
		<input type="hidden" class="quantity-input js-recalculate" name="quantity[]" onblur="check(this);" value="<?php if (isset($product->step_order_level) && (int)$product->step_order_level > 0) {
			echo $product->step_order_level;
		} else if(!empty($product->min_order_level)){
			echo $product->min_order_level;
		}else {
			echo '1';
		} ?>"/>
	    </span>
				<span class="quantity-controls js-recalculate">
	    </span>
				<?php // Display the quantity box END ?>

				<?php
				// Display the add to cart button
				?>
		<?php if(!$product->isInCart) { ?> <span class="addtocart-button">
                <?php echo shopFunctionsF::getAddToCartButton ($product->orderable); ?>
                
                </span> <?php  } else {?>
                               <span class="addtocart-button-inactive" ></span> 
                    <?php } ?>
				<?php } ?>

		</div>
		<?php }
		 // Display the add to cart button END  ?>
		<input type="hidden" class="pname" value="<?php echo htmlentities($product->product_name, ENT_QUOTES, 'utf-8') ?>"/>
		<input type="hidden" name="option" value="com_virtuemart"/>
		<input type="hidden" name="view" value="cart"/>
		<noscript><input type="hidden" name="task" value="add"/></noscript>
		<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
	</form>
                        
                        
                        
                        
                        
                        
                        </div>
                        
                        <div class="carousel-wrapper" style="display:none">
                            <div id="images<?= $product->virtuemart_product_id ?>">
                                <div class="popup-imgs">
                                    <p class="popup-thumbs">
                                
                              <?php $i=1; foreach ($product->images as $image) { ?>
                                      <?= $image->displayMediaThumb('onclick="imageClick(event,'.$i.')"', false); ?>
                                <?php ++$i; } if($needVideo){  ?> 
                                    <img src="/images/video_icon.jpg" onclick="imageClick(event,<?= $i ?>)" />
                                <?php } ?>
                                    </p>
                            <ul  class="product-carousel jcarousel-skin-tango">
                                <?php foreach ($product->images as $image) {?>
                                <li><div class="popup-img">
                                            <?= $image->displayMediaFull('', false); ?>
                                </div></li>
                                <?php } ?>
                                <?php if($needVideo){ ?>
                                <li class="showable-image"><div class="popup-img">
                                    <?= $product->displayPlugins['vmvideo']['full'] ?>
                                </div></li>                               
                                <? } ?>
                            </ul>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        
                        
			<!-- end of spacer -->
                    </div>
		</div> <!-- end of product -->
		<?php

		// Do we need to close the current row now?
		if ($iBrowseCol == $BrowseProducts_per_row || $iBrowseProduct == $BrowseTotalProducts) {
			?>
			<?php
			$iBrowseCol = 1;
		} else {
			$iBrowseCol++;
		}

		$iBrowseProduct++;
	} // end of foreach ( $this->products as $product )
	// Do we need a final closing row tag?
	if ($iBrowseCol != 1) {
		?>
	<div class="clear"></div>

		<?php
	}
	?>



	<?php
} elseif ($this->search !== NULL) {
	echo JText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}
?>
</div>
<div class="pagination"><?php echo $this->vmPagination->getPagesLinks (); ?><span style="float:right"></span></div>
<!-- end browse-view -->