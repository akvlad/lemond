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
			'compl_product_id'=> array('', 'int'),
			'default_product_id'=> array('', 'int'),
			'places'=> array('', 'string'),
  //                  'virtuemart_custom_id'=>array(0, 'int'),
                    'virtuemart_product_id'=>array(0,'int')
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
	    'virtuemart_custom_id' => 'int(11) UNSIGNED DEFAULT NULL',
            'compl_product_id' => 'int(11) unsigned NOT NULL ',
            'default_product_id' => 'int(11) unsigned NOT NULL ',
            'places' => 'text NOT NULL ',
            'virtuemart_product_id' => 'int(11) UNSIGNED DEFAULT NULL',
                    );

		return $SQLfields;
	}


	protected function my_getPluginCustomData (&$field, $product_id) 
	{
		
		/*$db=& JFactory::getDBO();
                $query='SELECT p.virtuemart_product_id as product_id, IF ( c.category_name IS NULL, p.product_name, CONCAT (c.category_name," - ",p.product_name) ) as product_name
				FROM #__virtuemart_products_ru_ru as p
				LEFT JOIN #__virtuemart_product_categories as pc ON p.virtuemart_product_id = pc.virtuemart_product_id
				LEFT JOIN #__virtuemart_categories_ru_ru as c ON pc.virtuemart_category_id = c.virtuemart_category_id';
		$db->setQuery($query);
		$this->params->products=$db->loadObjectList();
                //die("#1");
		$query='SELECT DISTINCT action_id FROM 
			#__virtuemart_product_custom_plg_vmactions_products  
			WHERE product_id='.$product_id.' AND place_num=1';
		$db->setQuery($query);
		$action_id=$db->loadResult();
		if(!$action_id){
			$this->params->action_id=0;
			return;
		}
                $this->params->action_id = $action_id;
		$query='SELECT compl_product_id, default_product_id FROM #__virtuemart_product_custom_plg_vmactions WHERE id='.$action_id;
		$db->setQuery($query);
		$action=$db->loadAssoc();
		foreach($action as $k => $v)
			$this->params->$k = $v;
                $this->params->places=array();
 		$query='SELECT product_id FROM #__virtuemart_product_custom_plg_vmactions_products 
			WHERE action_id='.$action_id.' AND place_num=1'; 
                $db->setQuery($query);
		$this->params->places[1]->product_id=$db->loadResult();
		$query='SELECT product_id FROM #__virtuemart_product_custom_plg_vmactions_products 
			WHERE action_id='.$action_id.' AND place_num=2';
		$db->setQuery($query);
		$products=$db->loadObjectList();
                $productModel=VmModel::getModel('product');
		foreach($products as $product){
                    $this->params->places[2]->products[$product->product_id]=$productModel->getProduct($product->product_id);
                    $productModel->addImages($this->params->places[2]->products[$product->product_id], 1);
                }*/
	}
        
        private function  initAllProducts(){
            $db=& JFactory::getDBO();
            $query='SELECT p.virtuemart_product_id as product_id, IF ( c.category_name IS NULL, p.product_name, CONCAT (c.category_name," - ",p.product_name) ) as product_name
				FROM #__virtuemart_products_ru_ru as p
				LEFT JOIN #__virtuemart_product_categories as pc ON p.virtuemart_product_id = pc.virtuemart_product_id
				LEFT JOIN #__virtuemart_categories_ru_ru as c ON pc.virtuemart_category_id = c.virtuemart_category_id';
            $db->setQuery($query);
            $this->params->products=$db->loadObjectList();
        }

        // get product param for this plugin on edit
	function plgVmOnProductEdit($field, $product_id, &$row,&$retValue) {
		if ($field->custom_element != $this->_name) return '';
                $this->getCustomParams($field);
		$this->getPluginCustomData($field,$product_id);
                $arr_places=unserialize($this->params->places);
                //var_dump($arr_places); die();
                
                $productModel=VmModel::getModel('product');
                if(isset($arr_places[1]))
                    $this->params->places=array(1=>$productModel->getProduct($arr_places[1]));
                else $this->params->places=array(1=>null);
                if(isset($arr_places[2])){
                    foreach ($arr_places[2] as $prod_id){
                        $this->params->places[2][$prod_id]=$productModel->getProduct($prod_id);
                    }
                }
                else $this->params->places[2]=array();
                $this->initAllProducts();
                ob_start();
                include(JPATH_SITE.'/plugins/vmcustom/vmactions/tmpl/backend_xml.php');
		$formXML=  ob_get_clean();
                //print('<xmp>');var_dump($formXML);print('</xmp>'); die();
		$actionsForm=JForm::getInstance('actionsform', $formXML);

                $actionsSet=$actionsForm->getFieldSet('myFieldSet');
                $html='';
                foreach ($actionsSet as $field){
                        //print('<xmp>');var_dump($field);print('</xmp>'); die();
			$html.='<p>'.$field->label.' '.$field->input.'</p>';
			$html.='<div style="clear: both"></div>';
		}
		$html .='<input type="hidden" value="'.$this->virtuemart_custom_id.'" name="plugin_param['.$row.']['.$this->_name.'][virtuemart_custom_id]">';
                $retValue .= $html  ;
		$row++;
		return true  ;
	}

        function getNewPrice(){
            /*switch($this->params->places[$place]->discount_type){
                case 1:
                    return round($oldPrice - $this->params->places[$place]->discount,0);
                case 2:
                    return round($oldPrice * (1 - $this->params->places[$place]->discount),0);
                case 3:
                    return round($this->params->places[$place]->discount,0);
            }*/
            //
            return round((float)$this->params->compl_prod->prices['salesPrice'] - ((int)$this->params->places[1]->prices['salesPrice']),0);
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
                //die('#1');
                
                self::$deadlock=true;
                
		$this->getCustomParams($group);
		$this->getPluginCustomData($group, $product->virtuemart_product_id);
                $arr_places=unserialize($this->params->places);
                $productModel=VmModel::getModel('product');
                //var_dump($arr_places); die();
                if(isset($arr_places[1]))
                    $this->params->places=array(1=>$productModel->getProduct($arr_places[1]));
                else return '';
                if(isset($arr_places[2])){
                    foreach ($arr_places[2] as $prod_id){
                        $this->params->places[2][$prod_id]=$productModel->getProduct($prod_id);
                        $productModel->addImages($this->params->places[2][$prod_id],1);
                    }
                }
                else return '';
                
                
               
                $productModel->addImages($this->params->places[1],1);

                $this->currency = CurrencyDisplay::getInstance();
                $this->params->compl_prod=$productModel->getProduct($this->params->compl_product_id);
                $this->params->default_prod=$productModel->getProduct($this->params->default_product_id);
                $productModel->addImages($this->params->default_prod,1);
                ob_start();
                include JPATH_SITE.'/plugins/vmcustom/vmactions/tmpl/frontend_js.php';
                $js=ob_get_clean();
                $doc=& JFactory::getDocument();
                $doc->addScriptDeclaration($js);
                ob_start();

                include JPATH_SITE.'/plugins/vmcustom/vmactions/tmpl/default_3places.php';
                $group->display.=ob_get_clean();
                //die($group->display);//.='assssssssssssssssssssddffffff';
                self::$deadlock==false;

		return true;
	}
        
        function deleteAction($actionID){
            $db=& JFactory::getDBO();
            
            $query='DELETE FROM #__virtuemart_product_custom_plg_vmactions_products
                WHERE action_id='.$actionID;
            $db->setQuery($query);
            $db->query();
            $query='DELETE FROM #__virtuemart_product_custom_plg_vmactions
                WHERE id='.$actionID;
            $db->setQuery($query);
            $db->query();
        }

	function plgVmOnStoreProduct($data,$plugin_param){
            if (key ($plugin_param) !== $this->_name) return;
            
            $key = key ($plugin_param);
            //$plugin_param[$key]['virtuemart_custom_id']=$this->virtuemart_custom_id;
            $places=array(1=>$data['virtuemart_product_id'], 2=>$plugin_param[$key]['place2'] );
            $plugin_param[$key]['places']=  serialize($places);
            //var_dump($plugin_param[$key]); die();
            /*$db=& JFactory::getDBO();
            $params=(object) $plugin_param[$this->_name];
            
            if($params->action_id!=0)
               $this->deleteAction ($params->action_id);
            
            $query='INSERT INTO #__virtuemart_product_custom_plg_vmactions (virtuemart_custom_id,compl_product_id,default_product_id)
                VALUES ("'.$params->virtuemart_custom_id.'","'.$params->compl_product_id.'","'.$params->default_product_id.'")';
            $db->setQuery($query);
            $db->query();
            $action_id=$db->insertid();
            
            $product=(object) array('action_id' => $action_id, 'product_id' => $params->place1,
                'place_num'=>1);
            $db->insertObject('#__virtuemart_product_custom_plg_vmactions_products', $product);
            
            foreach($params->place2 as $product_id){
                $product=(object) array('place_num'=>2,'product_id'=>$product_id, 'action_id'=> $action_id);
                $db->insertObject('#__virtuemart_product_custom_plg_vmactions_products', $product);    
            }*/

            return $this->OnStoreProduct($data,$plugin_param);
	}
        
        function plgVmOnDisplayProductVariantFE($field,&$row,&$group) {
            /*if ($field->custom_element != $this->_name) return '';
            ++$row;
            //var_dump($field);die();
            $this->parseCustomParams($field);
            $this->my_getPluginCustomData($field, $field->virtuemart_product_id);
            $name='customPlugin['.$field->virtuemart_customfield_id.']['.$this->_name.'][second_prod_id]';
            $group->display='<input type="hidden" name="'.$name.'" value="'.$this->params->default_product_id.'" />';*/
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
