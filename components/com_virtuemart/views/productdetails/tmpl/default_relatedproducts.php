<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen

 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_relatedproducts.php 6431 2012-09-12 12:31:31Z alatak $
 */

$doc=JFactory::getDocument();
$doc->addStyleSheet('/components/com_virtuemart/assets/js/jcarousel/skins/lemond-horiz-slide/skin.css');
$doc->addScriptDeclaration('
jQuery(document).ready(function() {
    jQuery("#related-pruducts-carousel").jcarousel();
});
');

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );
?>
        <div class="product-related-products">
    	<h4><?php echo JText::_('COM_VIRTUEMART_RELATED_PRODUCTS'); ?></h4>
        <ul id="related-pruducts-carousel" class="jcarousel-skin-lemond-access-slide">
    <?php foreach($this->product->customfieldsRelatedProducts as $_product){ $product=$_product->product; ?>
    <li> 
        <div class="relative-wrapper">
        <div class="img-wrapper">    
        <?= $product->images[0]->displayMediaThumb('class="slider-catalogue-imgs"', false); ?>
        </div>
        <?php echo JHTML::link ($product->link, $product->product_name, array('class'=>'calalogue-ref')); ?>
        <?php echo $this->currency->createPriceDiv ('salesPrice', ';kl', $product->prices,'relProd'); ?>
        <?php echo $this->getBuyForm($product); ?>
        </div>
    </li>
    <?php } ?>


    <?php /*
    
    
    foreach ($this->product->customfieldsRelatedProducts as $field) {
	    if(!empty($field->display)) {
	?><li >
		    <span class="product-field-display"><?php echo $field->display ?></span>
		</li>
	<?php }
	    } ?> */?>
        </ul>
        </div>
