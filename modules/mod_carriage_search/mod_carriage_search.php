<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.event.dispatcher' );
$doc=&JFactory::getDocument();
$doc->addScript('modules/mod_carriage_search/clapping_vols.js');

$db=& JFactory::getDBO();
$db->setQuery('SELECT DISTINCT c.virtuemart_custom_id FROM `#__virtuemart_product_categories` AS pc '.
		'LEFT JOIN `#__virtuemart_product_customfields` AS pcf '.
		'ON pcf.virtuemart_product_id = pc.virtuemart_product_id '.
		'LEFT JOIN #__virtuemart_customs AS c '.
		'ON c.virtuemart_custom_id=pcf.virtuemart_custom_id '.
		'WHERE pc.virtuemart_category_id='.JRequest::getVar('virtuemart_category_id',0).' '.
		'AND c.custom_jplugin_id <> 0');
$plugins=$db->loadObjectList();
$db->setQuery('SELECT DISTINCT `virtuemart_custom_id`, `custom_title` FROM `#__virtuemart_customs` WHERE `field_type` ="P"');
$options = $db->loadAssocList();
$res='';
$db->setQuery('SELECT `category_name` FROM `#__virtuemart_categories_ru_ru` WHERE ' .
			  '`virtuemart_category_id` ="'.JRequest::getVar('virtuemart_category_id',0).'"');
$keyword=$db->loadResult();
foreach($plugins as $plugin)
{
	$dispatcher=JDispatcher::getInstance();
	$dispatcher->trigger('plgVmSelectSearchableCustom',array( &$options,&$res,$plugin->virtuemart_custom_id ) );
}
if($res!=''){
?>
<form method="GET" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&' .
			 'virtuemart_category_id='.JRequest::getVar('virtuemart_category_id',0)) ?>">
<?php echo $res; ?>
<input type="hidden" name="keyword" value="<?php echo $keyword?>" />
<input type="submit" name="submit" value="Применить"/> 
</form>
<?php } ?>

