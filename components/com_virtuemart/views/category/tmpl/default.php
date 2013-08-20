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
        jQuery(\"a[rel=facebox]\").facebox(function() {jQuery(\".product-carousel\").jcarousel(); jQuery.facebox().reveal() });
        jQuery(document).bind('reveal.facebox', function() {
            jQuery(\"#facebox .product-carousel\").jcarousel({visible: 1, scroll: 1,
                itemFirstInCallback: function(c,li,i,s) {
                    jQuery('.popup .popup-thumbs img').removeClass('popup-active-thumb');
                    jQuery('.popup .popup-thumbs img:nth-child('+i+')').addClass('popup-active-thumb');
                } 
            });
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
<div id="breadcrambs-action">
<?php $modules = JModuleHelper::getModules('breadcrambs-action'); ?>
<?php if (!empty($modules)) : ?>
    <?php foreach ($modules as $module) : ?>
        <?php JModuleHelper::renderModule($module); ?>
        <?php echo $module->content; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<div id="action-blue-fon">
<?php $modules = JModuleHelper::getModules('action-blue-fon'); ?>
<?php if (!empty($modules)) : ?>
    <?php foreach ($modules as $module) : ?>
        <?php JModuleHelper::renderModule($module); ?>
        <?php echo $module->content; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<div class="browse-view">
<!--<h1><?php echo $this->category->category_name;?> </h1>-->
<?php if($this->parent_category){ ?>
<h1 class="cat-name<?= $this->parent_category->virtuemart_category_id ?>">
	<?php echo $this->parent_category->category_name;?></h1>
<?php } else { ?>
<span class="cat-name<?= $this->category->virtuemart_category_id ?>">
	<?php echo $this->category->category_name;?></span>
<?php } ?>


<?php $showAllUrl=$this->vmPagination->getShowAllUrl();?>
<span class="sort">Упорядочить по: <a href="<?php echo $this->orderByList; ?>">Цене</a> <a <?= !empty($showAllUrl) ? " class=\"show-all\" href=\"$showAllUrl\" " : "class=\"inact\"" ?> > Показать все </a> </span>
<div class="flip-clock">
<?php $modules = JModuleHelper::getModules('flip-clock'); ?>
<?php if (!empty($modules)) : ?>
    <?php foreach ($modules as $module) : ?>
        <?php JModuleHelper::renderModule($module); ?>
        <?php echo $module->content; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<div class="cl"></div>
<?php // Show child categories
if (!empty($this->products)) {
	?>
<div class="orderby-displaynumber">
	<div class="width70 floatleft">


	</div>
	<div class="width30 floatright display-number"><?php echo $this->vmPagination->getResultsCounter ();?><br/><?php echo $this->vmPagination->getLimitBox (); ?></div>
	<div class="vm-pagination">
		<?php echo $this->vmPagination->getPagesLinks (); ?>
		<span style="float:right"><?php echo $this->vmPagination->getPagesCounter (); ?></span>
	</div>

	<div class="clear"></div>
</div> <!-- end of orderby-displaynumber -->



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
        
        $j=0;

	// Start the Output
        foreach($this->products as $product) { 	
            if ($iBrowseCol == 1 && $iBrowseProduct > $BrowseProducts_per_row) { ?>
		<div class="horizontal-separator"></div>
            <?php }
            if ($iBrowseCol == 1) { ?>
                    <div class="row">
            <?php }
            if ($iBrowseProduct == $BrowseProducts_per_row or $iBrowseProduct % $BrowseProducts_per_row == 0) {
                $show_vertical_separator = ' ';
            } else {
                $show_vertical_separator = $verticalseparator;
            }?>

            <div class="ourproduct product floatleft<?php echo $Browsecellwidth . $show_vertical_separator ?> <?= $iBrowseProduct % $BrowseProducts_per_row == 0 ? 'last-in-row' : '' ?> ">
                <div class="relative-wrapper">
                    <?php if(isset ($product->displayPlugins['action'])) 
                        echo $product->displayPlugins['action'];
                    else include (JPATH_VM_SITE.'/views/category/tmpl/default_product.php');  ?>
                </div>                
            </div> <!-- end of product -->
            <?php   // Do we need to close the current row now?
		if ($iBrowseCol == $BrowseProducts_per_row || $iBrowseProduct == $BrowseTotalProducts) { ?>
			<div class="clear"></div>
                    </div> <!-- end of row -->
                    <?php   $iBrowseCol = 1; } 
                else { $iBrowseCol++; }
            $iBrowseProduct++;
         }
        // Do we need a final closing row tag?
	if ($iBrowseCol != 1) { ?>
            <div class="clear"></div>
        <?php   }   ?>
        <?php } elseif ($this->search !== NULL) {
	echo JText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : ''); } ?>
</div>
<div class="pagination"><?php echo $this->vmPagination->getPagesLinks (); ?><span style="float:right"></span></div>
<!-- end browse-view -->
