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

defined('_YTOOLS') or include_once 'core' . DS . 'sjimport.php';
YTools::setModule($module);

if(class_exists('vmJsApi')){
	vmJsApi::jQuery();
}else {
	if (!defined('SMART_JQUERY')){
		YTools::script('jquery-1.5.min.js');
		define('SMART_JQUERY', 1);
	}
} 

if (!defined('SMART_NOCONFLICT')){
	YTools::script('jsmart.noconflict.js');
	define('SMART_NOCONFLICT', 1);
}

YTools::script('jquery.jscrollpane.js');
YTools::script('jquery.mousewheel.js');

YTools::stylesheet('style.css');
YTools::stylesheet('jquery.jscrollpane.css');
YTools::stylesheet('style-scrollbar.css'); 

include_once dirname(__FILE__).'/core/helper.php';
$cart = sj_minicart_pro_helper::getList($params, $module->module);
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
$is_ajax_from_minicart_pro = (int)JRequest::getVar('minicart_ajax', 0);
if ($is_ajax && $is_ajax_from_minicart_pro){
	if( JRequest::getCmd('minicart_task')=='refresh' ){
		require JModuleHelper::getLayoutPath($module->module,'list');
                die();
	}	
}else{
	if($cart){
		$vm_currency_display = &CurrencyDisplay::getInstance();
		$lang = JFactory::getLanguage();
		$extension = 'com_virtuemart';
		$lang->load($extension);
		if ($cart->_dataValidated == true) {
			$taskRoute = '&task=confirm';
			$linkName = JText::_('GO_TO_CART');
		} else {
			$taskRoute = '';
			$linkName = JText::_('GO_TO_CART');
		}
		$cart->cart_show = '<a class="mc-gotocart" href="' . JRoute::_("index.php?option=com_virtuemart&view=cart" . $taskRoute) . '">' . $linkName . '</a>';
		$cart->billTotal = $lang->_('COM_VIRTUEMART_CART_TOTAL').' : <strong>'. $vm_currency_display->priceDisplay($cart->pricesUnformatted['billTotal']) .'</strong>';
		$cart->checkout = JRoute::_('index.php?option=com_virtuemart&view=cart&task=checkout'); 
		require JModuleHelper::getLayoutPath($module->module);
		require JModuleHelper::getLayoutPath($module->module, 'default_js');
	}
}

 ?>