<?php
defined('_JEXEC') or 	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' ) ;
/**
 *
 */

if (!class_exists('vmCustomPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');

class plgVmCustomVmActions extends vmCustomPlugin {
    
    private  static $deadlock;
            
	function __construct(& $subject, $config) {

		parent::__construct($subject, $config);

		$this->_tablepkey = 'id';
		$this->tableFields = array_keys($this->getTableSQLFields());
		$this->varsToPush = array(
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
		);

		return $SQLfields;
	}


	protected function my_getPluginCustomData (&$field, $product_id) 
	{
		$query='SELECT * FROM 
			#__virtuemart_product_custom_plg_vmactions  
			WHERE compl_product_id='.$product_id;
		$db->setQuery($query);
		$this->params=$db->loadObject();
	}

	// get product param for this plugin on edit
	function plgVmOnProductEdit($field, $product_id, &$row,&$retValue) {
		if ($field->custom_element != $this->_name) return '';
		$retValue .= ''  ;
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
 
		return true;
	}
        
        function plgVmOnDisplayProductVariantFE($field,&$row,&$group) {
            if ($field->custom_element != $this->_name) return '';
            ++$row;
            //var_dump($field);die();
            $this->parseCustomParams($field);
            $this->my_getPluginCustomData($field, $field->virtuemart_product_id);
            $name='customPlugin['.$field->virtuemart_customfield_id.']['.$this->_name.'][second_prod_id]';
            $group->display='<input type="hidden" name="'.$name.'" value="'.$this->params->default_product_id.'" />';
            //die($group->display);
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
