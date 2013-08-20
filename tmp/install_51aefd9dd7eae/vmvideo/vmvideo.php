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
jimport( 'joomla.form.fields.media' );

class plgVmCustomVmVideo extends vmCustomPlugin {


	function __construct(& $subject, $config) {

		parent::__construct($subject, $config);

		$this->_tablepkey = 'id';
		$this->tableFields = array_keys($this->getTableSQLFields());
		$this->varsToPush = array(
			'is_local'=> array('', 'int'),
			'link'=> array('', 'string'),
		);

		$this->setConfigParameterable('custom_params',$this->varsToPush);

	}
	/**
	 * Create the table for this plugin if it does not yet exist.
	 * @author 
	 */
	public function getVmPluginCreateTableSQL() {
		return $this->createTableSQL('Product Specification Table');
	}

	function getTableSQLFields() {
		$SQLfields = array(
	    'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
	    'virtuemart_product_id' => 'int(11) UNSIGNED DEFAULT NULL',
	    'virtuemart_custom_id' => 'int(11) UNSIGNED DEFAULT NULL',
	    'is_local' => 'int(1) NOT NULL DEFAULT \'\' ',
	    'link' => 'text NOT NULL DEFAULT \'\' ',
	    'icon' => 'varchar(255)  NOT NULL DEFAULT \'\' ',
		);

		return $SQLfields;
	}

	// get product param for this plugin on edit
	function plgVmOnProductEdit($field, $product_id, &$row,&$retValue) {
		if ($field->custom_element != $this->_name) return '';
		// $this->tableFields = array ( 'id', 'virtuemart_custom_id', 'custom_specification_default1', 'custom_specification_default2' );
		$this->getCustomParams($field);
		$this->getPluginCustomData($field, $product_id);

		// 		$data = $this->getVmPluginMethod($field->virtuemart_custom_id);
		// 		VmTable::bindParameterable($field,$this->_xParams,$this->_varsToPushParam);
		// 		$html  ='<input type="text" value="'.$field->custom_title.'" size="10" name="custom_param['.$row.'][custom_title]"> ';
		$html ='<div>';
		$html .='Локальный файл ';
		$html .='<input type="checkbox" value="1" name="plugin_param['.$row.']['.$this->_name.'][is_local]">';
		$html .='Ссылка на файл (код для встаки в страницу) ';
		$html .='<input type="text" value="'.$this->params->link.'" size="10" name="plugin_param['.$row.']['.$this->_name.'][link]">';
		$html .='<input type="text" value="'.$this->params->link.'" size="10" name="plugin_param['.$row.']['.$this->_name.'][link]">';
		$formXML='<form>
    <fields name="myGroupOfFields">
        <fieldset name="myFieldSet">
            <field
                    type="media"
                    name="myTextField"
                    id="mediaField"
                    label="Видео" 
                    directory="videos"/>
        </fieldset>
    </fields>
</form>'
		$videoInp=new JForm::getInstance($formXML,'video',false);
		$videoInp=$videoInp->getFields('myFieldSet');
		$html.=s$videoInp->input();
		$retValue .= $html  ;
		$row++;
		return true;
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
		$this->getCustomParams($group);
		$this->getPluginCustomData($group, $product->virtuemart_product_id);

		// Here the plugin values
		//$html =JTEXT::_($group->custom_title) ;

		$group->display .=  $this->renderByLayout('default',array($this->params,&$idx,&$group ) );

		return true;
	}

	function plgVmOnStoreProduct($data,$plugin_param){
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
