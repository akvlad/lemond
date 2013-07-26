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

class plgVmCustomcarcolors extends vmCustomPlugin {
    private static  $deadlock;
            function __construct(& $subject, $config) {

		parent::__construct($subject, $config);

		$this->_tablepkey = 'id';
		$this->tableFields = array_keys($this->getTableSQLFields());
		$this->varsToPush = array(
			'color_name'=> array('', 'string'),
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
	    'color_name' => 'varchar(50)  NOT NULL DEFAULT \'\' ',
	    'link' => 'varchar(255)  NOT NULL DEFAULT \'\' ',
		);

		return $SQLfields;
	}
	
	function my_getPluginCustomData($field, $product_id){
		$db=& JFactory::getDBO();
		$query='SELECT color_name, link FROM #__virtuemart_product_custom_plg_carcolors WHERE 
                    virtuemart_product_id='.$product_id;
		$db->setQuery($query);
		$this->params->items=$db->loadObjectList();
		$productModel=VmModel::getModel('product');
		$this->params->product=$productModel->getProduct($product_id);
		$productModel->addImages($this->params->product);
		$this->params->imagesOptions=array();
		$this->params->imagesOptions[]=JHTML::_('select.option', '', 'Нет изображения');
		foreach($this->params->product->images as $image){
			$this->params->imagesOptions[]=JHTML::_('select.option', $image->getUrl(), $image->file_name);
		}
	}

	// get product param for this plugin on edit
	function plgVmOnProductEdit($field, $product_id, &$row,&$retValue) {
		if ($field->custom_element != $this->_name) return '';
		// $this->tableFields = array ( 'id', 'virtuemart_custom_id', 'custom_specification_default1', 'custom_specification_default2' );
		$this->getCustomParams($field);
		$this->my_getPluginCustomData($field, $product_id);
                $doc=&JFactory::getDocument();
                $html='<script type="text/javascript">
function func(e){
        var $e=jQuery(e.target).closest(".colors-opened, .colors-closed");
        if($e.hasClass("colors-opened")){
            $e.removeClass("colors-opened").addClass("colors-closed");
        }else{
            $e.addClass("colors-opened").removeClass("colors-closed");
        }
    }        
</script>';
                $html.='<div class="colors-closed"><input type="button" class="title" onclick="func(event)" value="Цвета"/>';
		for($i=0;$i<10;++$i){
			$html.='<p>Цвет: ';
			$html.='<input type="text" name="plugin_param['.$row.']['.$this->_name.'][colors]['.$i.'][color_name]" 
				 value="'.(isset($this->params->items[$i]) ? $this->params->items[$i]->color_name : '').'"/>';
			$html.='<p> Изображение: ';
			$html.=JHTML::_('select.genericlist', $this->params->imagesOptions, 
				$name = 'plugin_param['.$row.']['.$this->_name.'][colors]['.$i.'][link]', $attribs = null, $key = 'value', 
				$text = 'text', $selected = ( isset($this->params->items[$i]) ? $this->params->items[$i]->link : '' ) );
		}
		$html .='<input type="hidden" value="'.$this->virtuemart_custom_id.'" name="plugin_param['.$row.']['.$this->_name.'][virtuemart_custom_id]">';
		$html .='</div>';
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
		if ($group->custom_element != $this->_name || self::$deadlock==true) return '';

		/*$this->_tableChecked = true;
		$this->getCustomParams($group);
		$this->getPluginCustomData($group, $product->virtuemart_product_id);

		// Here the plugin values
		//$html =JTEXT::_($group->custom_title) ;

		$group->display .=  $this->renderByLayout('default',array($this->params,&$idx,&$group ) );*/
                
                
                
                self::$deadlock = true;
                
                $this->my_getPluginCustomData($group, $product->virtuemart_product_id);
                ob_start();

                include JPATH_SITE.'/plugins/vmcustom/carcolors/tmpl/default.php';
                $group->display.=ob_get_clean();
                
                self::$deadlock = false;
                
		return true;
	}

	function plgVmOnStoreProduct($data,$plugin_param){
		// $this->tableFields = array ( 'id', 'virtuemart_product_id', 'virtuemart_custom_id', 'custom_specification_default1', 'custom_specification_default2' );
//		
//            
            if (key ($plugin_param) !== $this->_name) {
                
			return;
		}
                $key = key ($plugin_param);
                $query='DELETE FROM #__virtuemart_product_custom_plg_carcolors WHERE virtuemart_custom_id='.$plugin_param[$key]['virtuemart_custom_id'].
                        ' AND virtuemart_product_id='.$data['virtuemart_product_id'];
                $db=  JFactory::getDbo();
                $db->setQuery($query);
                $db->query();		
		foreach($plugin_param[$key]['colors'] as $color){
                    if(empty($color['color_name'])) break;
                        $color=(object) $color;
			$color->virtuemart_custom_id=$plugin_param[$key]['virtuemart_custom_id'];
			$color->virtuemart_product_id=$data['virtuemart_product_id'];
			$db->insertObject('#__virtuemart_product_custom_plg_carcolors', $color); 			
		}
		return ;//$this->OnStoreProduct($data,$plugin_param);
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
