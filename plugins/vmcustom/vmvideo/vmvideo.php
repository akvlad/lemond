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
                        'icon'=> array('', 'string'),
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
		
                $this->getCustomParams($field);
		
                $this->getPluginCustomData($field, $product_id);
                $checked='';

                if($this->params->is_local == 1){
                    $link = '';
                    $path = str_replace('/images/videos/', '', $this->params->link);
                    $checked='checked';
                }
                else{
                    $path = '';
                    $link = $this->params->link;
                    
                }
                $videoName='plugin_param['.$row.']['.$this->_name.'][mediaFile]';
                $iconName='plugin_param['.$row.']['.$this->_name.'][icon]';
		$html ='<div>';
		$html .='Локальный файл ';
		$html .='<input type="checkbox" value="1" name="plugin_param['.$row.']['.$this->_name.'][is_local]" '.$checked.'/>';
		$html .='<p>';
		$formXML='<form>
    <fields name="vmVideoPluginFields">
        <fieldset name="myFieldSet">
            <field type="textarea" name="embed" default="'.$link.'" label="Код для встаки в страницу" />
            <field
                    type="filelist"
                    name="mediaFile"
                    id="videoFile"
                    label="Видеофайл на сервере" 
                    directory="images/videos"
                    default="'.$path.'" />
            <field
                    type="media"
                    name="icon"
                    id="mediaFile"
                    label="Иконка для видео" 
                    directory=""
                    default="'.$this->params->icon.'" />
        </fieldset>
    </fields>
</form>';
		$videoInp=JForm::getInstance('videoform', $formXML);
                $videoInp=$videoInp->getFieldSet('myFieldSet');
                foreach ($videoInp as $field){
                    $html.='<p>'.$field->label.'</p><p>'.$field->input.'</p>';
                    $html.='<div style="clear: both"></div>';
                }
		$html .='<input type="hidden" value="'.$this->virtuemart_custom_id.'" name="plugin_param['.$row.']['.$this->_name.'][virtuemart_custom_id]">';
		$retValue .= $html  ;
		$row++;
		return true;
	}
        
        function plgVmOnDisplayCategoryFE($product,&$idx,&$group){
            // default return if it's not this plugin
		if ($group->custom_element != $this->_name) return '';

		$this->_tableChecked = true;
		$this->getCustomParams($group);
		$this->getPluginCustomData($group, $product->virtuemart_product_id);
                $video_tag=($this->params->is_local==1) ? '<object id="videoplayer3875" type="application/x-shockwave-flash"'.
                        ' data="/plugins/vmcustom/vmvideo/uppod-video.swf"'.
                        ' width="500" height="280"><param name="bgcolor" value="#ffffff" /><param name="allowFullScreen"'.
                        'value="true" /><param name="allowScriptAccess" value="always" />'.
                        '<param name="movie" value="/plugins/vmcustom/vmvideo/uppod-video.swf" />'.
                        '<param name="flashvars" value="uid=videoplayer3875&amp;comment=&amp;'.
                        'st=/plugins/vmcustom/vmvideo/video86-1873.txt&amp;file='.$this->params->link.'" />'.
                        '</object>' : $this->params->link ;
                $html='<div class=\"video-icon-wrapper\" ><img src="'.$this->params->icon.'" /> <a class="play-link" rel="facebox" href="#images'.
                        $product->virtuemart_product_id.'">PLAY</a></div> ';
                $group->display['thumb']=$html;
                //$html.="<div class=\"video-wrapper\"><div id=\"video$product->virtuemart_product_id\">$video_tag</div></div></div>";
                
                $group->display['full'].=$video_tag;
                
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
                
                /*$doc=&JFactory::getDocument();
                $doc->addScriptDeclaration('
                jQuery(document).ready( function() {
                jQuery("a[rel=facebox]").facebox(); } ); ');*/

		$video_tag=($this->params->is_local==1) ? '<object id="videoplayer3875" type="application/x-shockwave-flash"'.
                        ' data="/plugins/vmcustom/vmvideo/uppod-video.swf"'.
                        ' width="500" height="280"><param name="bgcolor" value="#ffffff" /><param name="allowFullScreen"'.
                        'value="true" /><param name="allowScriptAccess" value="always" />'.
                        '<param name="movie" value="/plugins/vmcustom/vmvideo/uppod-video.swf" />'.
                        '<param name="flashvars" value="uid=videoplayer3875&amp;comment=&amp;'.
                        'st=/plugins/vmcustom/vmvideo/video86-1873.txt&amp;file='.$this->params->link.'" />'.
                        '</object>' : $this->params->link ;
                $html='<div class="video-icon-wrapper"><img src="'.$this->params->icon.'" /> <a rel="facebox" href="#images'.
                        $product->virtuemart_product_id.'"><span>Play</span></a> ';
                //$html.="<div class=\"video-wrapper\" style=\"display:none;\"><div id=\"video$product->virtuemart_product_id\">$video_tag</div></div></div>";
                
                $group->display.=$html;

		return true;
	}

	function plgVmOnStoreProduct($data,$plugin_param){
		// $this->tableFields = array ( 'id', 'virtuemart_product_id', 'virtuemart_custom_id', 'custom_specification_default1', 'custom_specification_default2' );
                if (key ($plugin_param) !== $this->_name) {
			return;
		}
                
                $key = key ($plugin_param);
                $pluginFields=$data["vmVideoPluginFields"];
                if($plugin_param[$key]['is_local']=='1'){
                   if($pluginFields['mediaFile']!='-1' && $pluginFields['mediaFile']!='-1')  
                        $plugin_param[$key]['link']='/images/videos/'.$pluginFields['mediaFile'];
                } else{
                   $plugin_param[$key]['link']=$pluginFields['embed']; 
                }
                $plugin_param[$key]['icon']=$pluginFields['icon'];
                //print'<xmp>'; var_dump($plugin_param); print'</xmp>'; die(); 

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
