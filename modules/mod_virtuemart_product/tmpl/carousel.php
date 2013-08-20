<?php

$doc=JFactory::getDocument();
$doc->addStyleSheet('/components/com_virtuemart/assets/js/jcarousel/skins/lemond-horiz-slide/skin.css');
$doc->addScriptDeclaration('
    jQuery(document).ready(function(){
        jQuery(".cat-product-carousel").jcarousel();
    });
');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//print('<xmp>');var_dump($products[0]);print('</xmp>'); die();
?>
<h3><?= JHtml::link(JRoute::_(JURI::base()).$category->virtuemart_category_id, Каталог) ?> <img src="<?= JURI::base(true) ?>/templates/lemond/images/prod-point-left.png" />  
    <?= JHtml::link(JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id), $category->category_name) ?> 
                 <img src="<?= JURI::base(true) ?>/templates/lemond/images/prod-point-down.png" /> </h3>
<ul class="cat-product-carousel  jcarousel-skin-lemond-horiz-slide">
    <?php foreach($products as $product){ ?>
    <li> 
        <div class="relative-wrapper">
        <div class="img-wrapper">    
        <?= $product->images[0]->displayMediaThumb('class="slider-catalogue-imgs"', false); ?>
        </div>
        <?php echo JHTML::link ($product->link, $product->product_name, array('class'=>'calalogue-ref')); ?>
        <?php echo $currency->createPriceDiv ('salesPrice', ';kl', $product->prices,'noRed'); ?>
        <?php echo $productModel->getBuyForm(array($product)); ?>
        </div>
    </li>
    <?php } ?>
</ul>