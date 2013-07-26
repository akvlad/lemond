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

class plgVmCustomAction extends vmCustomPlugin {


	function __construct(& $subject, $config) {

		parent::__construct($subject, $config);

		$this->_tablepkey = 'id';
		$this->tableFields = array_keys($this->getTableSQLFields());
		$this->varsToPush = array(
			'product1'=> array(0, 'int'),
			'product2'=> array(0, 'int'),
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
	    'product1' => 'int(11) NOT NULL DEFAULT 0 ',
	    'product2' => 'int(11) NOT NULL DEFAULT 0 '
		);

		return $SQLfields;
	}

	// get product param for this plugin on edit
	function plgVmOnProductEdit($field, $product_id, &$row,&$retValue) {
		if ($field->custom_element != $this->_name) return '';
		//var_dump($field);
		$this->getCustomParams($field);
		$this->getPluginCustomData($field, $product_id);
		
		$db=& JFactory::getDBO();
        $query='SELECT p.virtuemart_product_id as product_id, IF ( c.category_name IS NULL, p.product_name, CONCAT (c.category_name," - ",p.product_name) ) as product_name
				FROM #__virtuemart_products_ru_ru as p
				LEFT JOIN #__virtuemart_product_categories as pc ON p.virtuemart_product_id = pc.virtuemart_product_id
				LEFT JOIN #__virtuemart_categories_ru_ru as c ON pc.virtuemart_category_id = c.virtuemart_category_id';
		$db->setQuery($query);
		$products=$db->loadObjectList();
		foreach($products as $product){
			$ProductOptions[]=JHTML::_('select.option', $product->product_id, $product->product_name);	
		}
		
		
		
		$html ='<div>';
		$html .='<p>Товар1: '.JHTML::_('select.genericlist', $ProductOptions, 
				$name = 'plugin_param['.$row.']['.$this->_name.'][product1]', $attribs = null, $key = 'value', 
				$text = 'text', $selected = $this->params->product1 );;
		$html .='<p>Товар2: '.JHTML::_('select.genericlist', $ProductOptions, 
				$name = 'plugin_param['.$row.']['.$this->_name.'][product2]', $attribs = null, $key = 'value', 
				$text = 'text', $selected = $this->params->product2 );
                $html .='<input type="hidden" value="'.$this->virtuemart_custom_id.'" name="plugin_param['.$row.']['.$this->_name.'][virtuemart_custom_id]">';
		$html .='</div>';
                //die($html);
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
		if ($group->custom_element != $this->_name) return '';

		$this->_tableChecked = true;
		$group->display .=  $this->renderByLayout('default',array($this->params,&$idx,&$group ) );

		return true;
	}

	function plgVmOnStoreProduct($data,$plugin_param){
		// $this->tableFields = array ( 'id', 'virtuemart_product_id', 'virtuemart_custom_id', 'custom_specification_default1', 'custom_specification_default2' );

		return $this->OnStoreProduct($data,$plugin_param);
	}
        
        function plgVmOnDisplayCategoryFE($product,&$idx,&$group){
            if ($group->custom_element != $this->_name) return '';

            $this->_tableChecked = true;
            $this->getCustomParams($group);
                
            $this->getPluginCustomData($group, $product->virtuemart_product_id);
            
            $productModel=  VmModel::getModel('product');
            $product->buyForm=$productModel->getBuyForm(array($product));
            $product1=$productModel->getProduct($this->params->product1);
            $productModel->addImages($product1);
            $product2=$productModel->getProduct($this->params->product2);
            $productModel->addImages($product2);
            
            $product1->displayPlugins=$this->getCategoryPlugins($product1);
            $product2->displayPlugins=$this->getCategoryPlugins($product2);
            ob_start();
            include JPATH_SITE.'/plugins/vmcustom/action/tmpl/default.php';
            $group->display=  ob_get_clean();
            
            return true;
        }
        
        
        function getCategoryPlugins(&$product){
                $query = 'SELECT C.`virtuemart_custom_id` , `custom_element`, `custom_params`, `custom_parent_id` , `admin_only` , `custom_title` , `custom_tip` , C.`custom_value` AS value, `custom_field_desc` , `field_type` , `is_list` , `is_hidden`, `layout_pos`, C.`published` , field.`virtuemart_customfield_id` , field.`custom_value`, field.`custom_param`, field.`custom_price`, field.`ordering`
			FROM `#__virtuemart_customs` AS C
			LEFT JOIN `#__virtuemart_product_customfields` AS field ON C.`virtuemart_custom_id` = field.`virtuemart_custom_id`
			Where `virtuemart_product_id` =' . (int)$product->virtuemart_product_id . ' and `field_type` != "G" and `field_type` != "R" and `field_type` != "Z"';
		$query .= ' and is_cart_attribute = 0 order by field.`ordering`,virtuemart_custom_id';
		$db=&JFactory::getDbo();
                $db->setQuery ($query);
                $retFields=array();
		if ($productCustoms = $db->loadObjectList ()) {
			$row = 0;
			if (!class_exists ('vmCustomPlugin')) {
				require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');
			}
			foreach ($productCustoms as $field) {
                            
				if ($field->field_type == "E") {
					$field->display = '';
					JPluginHelper::importPlugin ('vmcustom'); 
					$dispatcher = JDispatcher::getInstance ();
					$ret = $dispatcher->trigger ('plgVmOnDisplayCategoryFE', array($product, &$row, &$field));
                                        if($ret){
                                                                                        
                                            $retFields[$field->custom_element]=$field->display;
                                        }

				}
				$row++;
			}
                        
			return $retFields;
		}
		else {
			return array();
		}
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
