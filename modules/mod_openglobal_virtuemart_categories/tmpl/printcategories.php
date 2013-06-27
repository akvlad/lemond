<?php // no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * Category menu module
 *
 * @package VirtueMart
 * @subpackage Modules
 * @copyright Copyright (C) OpenGlobal E-commerce. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL V3, see LICENSE.php
 * @author OpenGlobal E-commerce
 *
 */
 
 class CatPrintHelper
 {
 	private $firstLevelCats='';
 	private $secondLevelCats=array();
 	private $scripts=array();
        private $activecat=0;
        private $itemID=0;
        function CatPrintHelper($params, $virtuemart_categories, $class_sfx, $parentCategories,$itemID)
 	{
            $this->itemID=$itemID;
            $this->compileCats($params, $virtuemart_categories, $class_sfx, $parentCategories);
 	}
 	function compileCats($params, $virtuemart_categories, $class_sfx, $parentCategories)
 	{
        $this->firstLevelCats.= '<ul class="menu'.$class_sfx.'" >';
        $i=1;
        $module_class='virtuemartcategories'.$params->get('moduleclass_sfx');
        foreach ($virtuemart_categories as $category) {
        		$currScript='';
                $active_menu = '';
                $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
                $images = $category->images;
                if (1 == $params->get('show_images')) {
                        $image = $images[0]->file_url_thumbnail;
                } else if (2 == $params->get('show_images')) {
                        $image = $images[0]->file_url;
                }
                $cattext = ($image ? '<img src="'.$image.'" alt="'.$category->category_name.'" />' : '').'<span>'.$category->category_name.'</span>';

                if (is_array($parentCategories)) {// Need this check because $parentCategories will be null if we're at category 0
                        if (in_array( $category->virtuemart_category_id, $parentCategories)) {
                                $active_menu = 'class="active"';
                        }
                }
                $attrs=null;
                $this->firstLevelCats.= '<li id="cat'.$category->virtuemart_category_id.'-li"'.
                	$active_menu.'><div>'.JHTML::link($caturl, $cattext,
                		array('class'=>'header-'.$i)).'</div>';
                $currScript='jQuery(".'.$module_class.
									' #cat'.$category->virtuemart_category_id.'-li").mouseover(function(){
										jQuery(".'.$module_class.
											' .subcat").hide();';
                if ($category->childs) {
                        $this->compileSecondLevelCats($params, $category->childs, 
                        	$class_sfx, $parentCategories,'cat'.$category->virtuemart_category_id.'-subcat');
                        
                        $currScript.='jQuery(".'.$module_class.
											' #cat'.$category->virtuemart_category_id.'-subcat").show();';
						
                        
                }
                $currScript.='});';
                $this->scripts[]=$currScript;
                $this->firstLevelCats.= '</li>';
                ++$i;
        }
        $this->firstLevelCats.= '</ul>';
 	}
 	function compileSecondLevelCats($params, $virtuemart_categories, $class_sfx, $parentCategories,$subcatID)
 	{
             
        $i=1;
        $module_class='virtuemartcategories'.$params->get('moduleclass_sfx');
        foreach ($virtuemart_categories as $category) {
        		$currScript='';
        		$classes=array();
                $active_menu = '';
                $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id/*.
                        '&Itemid='.$this->itemID*/);
                $images = $category->images;
                if (1 == $params->get('show_images')) {
                        $image = $images[0]->file_url_thumbnail;
                } else if (2 == $params->get('show_images')) {
                        $image = $images[0]->file_url;
                }
                $cattext = ($image ? '<img src="'.$image.'" alt="'.$category->category_name.'" />' : '').'<span>'.$category->category_name.'</span>';
                $isActive=false;
                if (is_array($parentCategories)) {// Need this check because $parentCategories will be null if we're at category 0
                        if (in_array( $category->virtuemart_category_id, $parentCategories)) {
                                $isActive=true;
                                $this->activecat=$subcatID;
                                $classes[] = "active";
                        }
                }
                if($i==1)
                	$classes[] = "first-item";
                if(count($classes)>0)
                	$classes='class="'.implode(' ',$classes).'"';
                else
                	$classes='';
                $attrs=null;
                if($isActive){
                    $categories.= '<li id="cat'.$category->virtuemart_category_id.'-li"'.
                            $classes.'><div>'.$cattext.'</div>';
                }else
                    $categories.= '<li id="cat'.$category->virtuemart_category_id.'-li"'.
                            $classes.'><div>'.JHTML::link($caturl, $cattext,$attrs).'</div>';
                $categories.= '</li>';
                ++$i;
        }
        $isDisplay=($this->activecat === $subcatID);
        $categories= '<div id='.$subcatID.' class="subcat" '.(!$isDisplay ? 'style="display:none"' : '').'>
                <div class="subwrapper"><div class="piptik"></div>
                <ul class="submenu'.$class_sfx.'">'.$categories;
        $categories.= '<div class="cl"></div></ul></div></div>';
        $this->secondLevelCats[]=$categories;
 	}
 	function printFirstLevel()
 	{
 		echo $this->firstLevelCats;
 	}
 	function printSecondLevel()
 	{
 		foreach ($this->secondLevelCats as $secondLevelCat)
 			echo $secondLevelCat;
 	}
 	function applyScripts($moduleclass_sfx)
 	{
 		$script='';
 		foreach($this->scripts as $currScript){
 			$script.=$currScript;
 		}
 		$doc=&JFactory::getDocument();
 		$doc->addScriptDeclaration('jQuery(document).ready(function(){'.$script.'})');
 		$doc->addScriptDeclaration(
			'var activecat="'.($this->activecat!==0 ? $this->activecat : '0' ) .'";'.
                            'jQuery(document).ready(' .
				'function(){
					jQuery(".virtuemartcategories'.$moduleclass_sfx.'").mouseleave(' .
						'function(){
							jQuery(".virtuemartcategories'.$moduleclass_sfx.' .subcat").hide();
                                                        jQuery(".virtuemartcategories'.$moduleclass_sfx.' .subcat").height(0);' .
							'if(activecat!=0) {
                                                            jQuery(".virtuemartcategories'.$moduleclass_sfx.' #"+activecat).show();
                                                            jQuery(".virtuemartcategories'.$moduleclass_sfx.' #"+activecat).height("auto");
                                                         } })})');
 	}
 	//,$divClass,$divId)
 		
 }

 function printCategories() {
        
}
