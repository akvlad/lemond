<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_showprices.php 6556 2012-10-17 18:15:30Z kkmediaproduction $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
?>
<div class="product-price" id="productPrice<?php echo $this->product->virtuemart_product_id ?>">
	<?php

        if (round($this->product->prices['salesPrice'],0) != round($this->product->prices['priceWithoutTax'],0)){
            //echo $this->currency->createPriceDiv ('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $this->product->prices); ?>
            <canvas id="DiscountCanvas<?= $this->product->virtuemart_product_id ?>" width="185" height="30">
            </canvas>
            <script type="text/javascript">
                 var cnv=document.getElementById('DiscountCanvas<?= $this->product->virtuemart_product_id ?>');
                 var ctx=cnv.getContext("2d");
                 var gradient=ctx.createLinearGradient(0,0,0,25);
                 gradient.addColorStop(0.0, '#ff0000');
                 gradient.addColorStop(0.9, '#9A0027');
                 ctx.fillStyle = gradient;
                 ctx.font = "bold 21px Microsoft YaHei";
                 ctx.fillText("<?= round($this->product->prices['salesPrice'],2)?> грн.", 0, 25);
            </script>
           <?php echo $this->currency->createPriceDiv ('priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $this->product->prices);}
        else {
            echo $this->currency->createPriceDiv ('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $this->product->prices);
            //echo $this->currency->createPriceDiv ('priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $this->product->prices);
        }
	?>
</div>
