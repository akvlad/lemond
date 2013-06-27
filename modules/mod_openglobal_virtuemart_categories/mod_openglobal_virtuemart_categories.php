<?php
defined('_JEXEC') or  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
 * Category menu module
 *
 * @package VirtueMart
 * @subpackage Modules
 * @copyright Copyright (C) OpenGlobal. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @author OpenGlobal
 *
 */
require_once('helper.php');
defined('JPATH_VM_ADMINISTRATOR') or define('JPATH_VM_ADMINISTRATOR', JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart');
if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
vmJsApi::jQuery();
vmJsApi::cssSite();

/* Setting */
$categoryModel = VmModel::getModel('Category');
$category_id = $params->get('Parent_Category_id', 0);
$class_sfx = $params->get('class_sfx', '');
$moduleclass_sfx = $params->get('moduleclass_sfx','');
$layout = $params->get('layout','default');
$active_category_id = JRequest::getInt('virtuemart_category_id', '0');
$vendorId = '1';
$cache = JFactory::getCache('com_virtuemart','callback');
$categories = $cache->call('getRecursiveCategories', $vendorId, $category_id);
$itemID=$params->get('item_id');
$doc=JFactory::getDocument();
$doc->addScript('/modules/mod_openglobal_virtuemart_categories/piptik.js' );

if(empty($categories)) return false;

$parentCategories = $categoryModel->getCategoryRecurse($active_category_id,0);


/* Load tmpl default */
require(JModuleHelper::getLayoutPath('mod_openglobal_virtuemart_categories',$layout));
?>