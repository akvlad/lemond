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

if (!class_exists('vmUserfieldPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmuserfieldtypeplugin.php');
jimport( 'joomla.form.fields.media' );

class plgVmUserfieldChildren extends vmUserfieldPlugin {


	function __construct(& $subject, $config) {

		parent::__construct($subject, $config);

	}
        
        private function getUserData($_userData){
            if($_userData!=0){
                $res=unserialize($_userData['children']);
                if(is_array($res) && count($res) > 0)                    
                    return $res;
                else return array(array('birthdate'=>'','name'=>'','gender'=>''));
            }
            return array(array('birthdate'=>'','name'=>'','gender'=>''));
        }
        
        function prepareJS()
        {
            ob_start();
            include JPATH_SITE.'/plugins/vmuserfield/children/tmpl/children_js.php';
            return ob_get_clean();
        }
	
	function plgVmOnUserfieldDisplay ($_prefix,$_fld,$_userData,&$_return)
	{
            if($_fld->type!='pluginchildren')
                return;

                $field=array('name'=>$_fld->name,
                    'value'=>'',
                    'title'=>$_fld->title,
                    'type'=>$_fld->type,
                    'required'=>$_fld->required,
                    'hidden'=>false,
                    'formcode'=>'bla-bla-bla',
                    'description'=>$_fld->description);
                $children=$this->getUserData($_userData);
                $this->userData=$children;
                $field['formcode']=$this->prepareJS();
                ob_start();
                include JPATH_SITE.'/plugins/vmuserfield/children/tmpl/default.php';
                $field['formcode'].=ob_get_clean();
                $_return['fields'][$_fld->name]=$field;
            
  
            
	}
        private function getEntryNum(){
            return count($this->userData);
        }
        private function getSingleEntry(){
            $single_entry=true;
            $children=array(array('birthdate'=>'','name'=>'','gender'=>''));
            $children=$this->getUserData($_userData);
                ob_start();
                include JPATH_SITE.'/plugins/vmuserfield/children/tmpl/default.php';
            $res=ob_get_clean();
            $begin=strpos($res,'<!--SINGLE ENTRY BEGIN-->')+strlen('<!--SINGLE ENTRY BEGIN-->');
            $len=strpos($res,'<!--SINGLE ENTRY END-->')-$begin;
            return substr($res, $begin, $len);              
                    
        }
        function plgVmDeclarePluginParamsUserfield($type,$plgName,$userfield_jplugin_id,&$_data){

        }
        
        function plgVmOnBeforeUserfieldDataSave(&$valid,$id,&$data,$user){
            return true;
            var_dump($valid);
            var_dump($id);
            var_dump($data);
            var_dump($user);
            die();
        }
        function plgVmPrepareUserfieldDataSave($fieldType, $fieldName, &$data, &$value, $params){
            if($fieldType != 'pluginchildren') return;
            //die(serialize($data['children']));
            $value=serialize($data['children']);
        }


}

// No closing tag
