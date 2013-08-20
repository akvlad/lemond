
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

require dirname(__FILE__).'/vmloader.php';

JFactory::getLanguage()->load('com_virtuemart');
if(!class_exists('sj_minicart_pro_helper')){
	class sj_minicart_pro_helper  {
		protected static $helper = null;

		public static function getList( $params, $module){
			$helper = & self::getInstance();
			return $helper->_getList( $params, $module );
		}

		protected function _getList( $params, $module ){
			if(class_exists('VirtueMartCart')){
				$productModel = VmModel::getModel('product');
				$calculator = calculationHelper::getInstance();
				$customfields = VmModel::getModel ('Customfields');
				$cart = VirtueMartCart::getCart(false);
				$cart->pricesUnformatted = $calculator->getCheckoutPrices($cart, true);
				$productModel->addImages($cart->products);
			return $cart;
			}
		}
		
		public static function getInstance(){
			if (is_null(self::$helper)){
				$classname = __CLASS__;
				self::$helper = new $classname();
			}
			return self::$helper;
		}

	}
}

