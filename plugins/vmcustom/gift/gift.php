<?php
defined('_JEXEC') or 	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' ) ;
/**
 * @version $Id: standard.php,v 1.4 2005/05/27 19:33:57 ei
 *
 * a special type of 'product specification':
 * its fee depend on total sum
 * @author Max Milbers
 * @version $Id: standard.php 3681 2011-07-08 12:27:36Z alatak $
 * @package VirtueMart
 * @subpackage payment
 * @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.org
 */

if (!class_exists('vmCustomPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');

class plgVmCustomGift extends vmCustomPlugin {
    protected static $deadlock;

	function __construct(& $subject, $config) {

		parent::__construct($subject, $config);

		$this->_tablepkey = 'id';
		$this->tableFields = array_keys($this->getTableSQLFields());
		$this->varsToPush = array(
			'gift_id'=> array(0, 'int'),
		);

		$this->setConfigParameterable('custom_params',$this->varsToPush);

	}
	/**
	 * Create the table for this plugin if it does not yet exist.
	 * @author Val�rie Isaksen
	 */
	public function getVmPluginCreateTableSQL() {
		return $this->createTableSQL('Product Specification Table');
	}

	function getTableSQLFields() {
		$SQLfields = array(
	    'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
	    'virtuemart_product_id' => 'int(11) UNSIGNED DEFAULT NULL',
	    'virtuemart_custom_id' => 'int(11) UNSIGNED DEFAULT NULL',
	    'gift_id' => 'int(11) NOT NULL DEFAULT 0 ',
		);

		return $SQLfields;
	}
	
	function getAllProductOptions(){
		$query='SELECT p.virtuemart_product_id as product_id, IF ( c.category_name IS NULL, p.product_name, CONCAT (c.category_name," - ",p.product_name) ) as product_name
				FROM #__virtuemart_products_ru_ru as p
				LEFT JOIN #__virtuemart_product_categories as pc ON p.virtuemart_product_id = pc.virtuemart_product_id
				LEFT JOIN #__virtuemart_categories_ru_ru as c ON pc.virtuemart_category_id = c.virtuemart_category_id';
		$db =& JFactory::getDBO();
                $db->setQuery($query);
		$products=$db->loadObjectList();
		foreach($products as $product){
			$this->params->ProductOptions[]=JHTML::_('select.option', $product->product_id, $product->product_name);	
		}
	}

	// get product param for this plugin on edit
	function plgVmOnProductEdit($field, $product_id, &$row,&$retValue) {
		if ($field->custom_element != $this->_name) return '';
		// $this->tableFields = array ( 'id', 'virtuemart_custom_id', 'custom_specification_default1', 'custom_specification_default2' );
		$this->getCustomParams($field);
		$this->getPluginCustomData($field, $product_id);
		$productOptions=$this->getAllProductOptions();

		$html ='<div>';
		$html .='Подарок: ';
		$html.=JHTML::_('select.genericlist', $this->params->ProductOptions, 
				$name = 'plugin_param['.$row.']['.$this->_name.'][gift_id]', $attribs = null, $key = 'value', 
				$text = 'text', $selected = $this->params->gift_id );
		$html .='<input type="hidden" value="'.$this->virtuemart_custom_id.'" name="plugin_param['.$row.']['.$this->_name.'][virtuemart_custom_id]">';
		$html .='</div>';
		// 		$field->display =
		$retValue .= $html  ;
		$row++;
		return true  ;
	}

	/**
	 * @ idx plugin index
	 * @see components/com_virtuemart/helpers/vmCustomPlugin::onDisplayProductFE()
	 * @author Patrick Kohl
	 *  Display product
	 */
	function plgVmOnDisplayProductFE($product,&$idx,&$group) {
		// default return if it's not this plugin
		if ($group->custom_element != $this->_name || self::$deadlock) return '';
                
                self::$deadlock=true;

		$this->_tableChecked = true;
		$this->getCustomParams($group);
		$this->getPluginCustomData($group, $product->virtuemart_product_id);
                $productModel=  VmModel::getModel('product');
                $product=$productModel->getProduct($this->params->gift_id);
                $productModel->addImages($product);
                $currency=CurrencyDisplay::getInstance();
                $doc=& JFactory::getDocument();
                $doc->addScriptDeclaration('jQuery(document).ready(function(){
                        jQuery(".gift .birka").click(function(){
                            jQuery.facebox({ div: "#gift-in" });
                        });
                    })');
                ob_start();
                include (JPATH_SITE.'/plugins/vmcustom/gift/tmpl/default.php');
                $group->display.=ob_get_clean();
                        
                self::$deadlock=false;

		return true;
	}

	function plgVmOnStoreProduct($data,$plugin_param){
            		if (key ($plugin_param) !== $this->_name) {
			vmdebug('OnStoreProduct return because key '.key ($plugin_param).'!== '. $this->_name);
			return;
		}
		// $this->tableFields = array ( 'id', 'virtuemart_product_id', 'virtuemart_custom_id', 'custom_specification_default1', 'custom_specification_default2' );
		return $this->OnStoreProduct($data,$plugin_param);
	}
	/**
	 * We must reimplement this triggers for joomla 1.7
	 * vmplugin triggers note by Max Milbers
	 */
	public function plgVmOnStoreInstallPluginTable($psType,$name) {
		return $this->onStoreInstallPluginTable($psType,$name);
	}

	function plgVmSetOnTablePluginParamsCustom($name, $id, &$table){
		return $this->setOnTablePluginParams($name, $id, $table);
	}

	function plgVmDeclarePluginParamsCustom($psType,$name,$id, &$data){
		return $this->declarePluginParams('custom', $name, $id, $data);
	}

	/**
	 * Custom triggers note by Max Milbers
	 */
	function plgVmOnDisplayEdit($virtuemart_custom_id,&$customPlugin){
		return $this->onDisplayEditBECustom($virtuemart_custom_id,$customPlugin);
	}

}

// No closing tag
