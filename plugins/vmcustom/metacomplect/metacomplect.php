<?php
defined('_JEXEC') or 	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' ) ;
/**
 *
 */

if (!class_exists('vmCustomPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');

class plgVmCustommetacomplect extends vmCustomPlugin {
    
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
                
                $db=& JFactory::getDbo();
		$db->setQuery($query);
		$this->params=$db->loadObject();
                if(!$this->params->id) return false;
                $query='SELECT * FROM 
			#__virtuemart_product_custom_plg_vmactions_products  
			WHERE action_id='.$this->params->id;
                $db->setQuery($query);
                $products=$db->loadObjectList();
                
                foreach ($products as $product){
                    $this->params->places[$product->place_num][]=$product->product_id;
                }
                return true;
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
            $group->display='<input id="second-product" type="hidden" name="'.$name.'" value="'.$this->params->default_product_id.'" />';
            //die($group->display);
        }
        
        function plgVmOnAddToCart(&$product){
            $customPlugin=JRequest::getVar('customPlugin',null);
            $query='SELECT vpc.virtuemart_customfield_id 
                FROM #__virtuemart_product_customfields as vpc 
                LEFT JOIN #__virtuemart_customs as vc 
                ON vpc.virtuemart_custom_id=vc.virtuemart_custom_id
                WHERE vc.custom_element="metacomplect" 
                AND vpc.virtuemart_product_id='.$product->virtuemart_product_id;
            $db=  &JFactory::getDbo();
            $db->setQuery($query);
            $id=$db->loadResult();
            $second_product_id=intval($customPlugin[$id]['metacomplect']['second_prod_id']);
            
            if(!$customPlugin || !isset($customPlugin[$id])) return array(false);
            
            
            if(!$this->my_getPluginCustomData($product,$product->virtuemart_product_id)) return array(false);
            
            $this->params->places=  unserialize($this->params->places);
            
            if(!in_array($second_product_id, $this->params->places[2])) return array(false);
            $product->second_product=$second_product_id;
            //die($product->second_product);
            return array(true)/*array('second_product'=>$second_product_id)*/;
        }
        
        function plgVmProductName(&$product,&$product_name){
            if(!isset($product->second_product)) return;
            $productModel=  VmModel::getModel('product');
            $second_product=$productModel->getProduct($product->second_product);
            $product_name=  str_replace('{ТОВАР2}', $second_product->product_name,
                    $product_name);
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
