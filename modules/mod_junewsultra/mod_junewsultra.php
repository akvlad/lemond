<?php
/**
* @package Joomla! 2.5
* @version 4.x
* @author 2008-2012 (c)  Denys Nosov
* @author web-site: www.joomla-ua.org
* @copyright This module is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 License.
**/

defined('_JEXEC') or die;
define('DS', '/');

require_once(dirname(__FILE__). DS .'helper.php');

$list = modJUNewsUltraHelper::getList($params);

if($params->get('empty_mod', 0) == 1) if(!count($list)) return;

$template       = str_replace('_:', '', $params->def('template'));
$layoutpath     = JModuleHelper::getLayoutPath('mod_junewsultra', $template );

$all_in         = $params->def('all_in');
$custom_heading = $params->def('custom_heading');
$link_all_in    = trim( $params->get( 'link_all_in' ) );
$text_all_in    = trim( $params->get( 'text_all_in' ) );
$text_all_in2   = trim( $params->get( 'text_all_in2' ) );
$item_heading   = trim( $params->get( 'item_heading' ) );
$link_menuitem  = trim( $params->get( 'link_menuitem' ) );
$class_all_in   = trim( $params->get( 'class_all_in' ) );
$copy           = $params->def('copy', 1);

if($params->get('cssstyle') == 1) {

    $document =& JFactory::getDocument();
    $document->addCustomTag('<link rel="stylesheet" href="'. JURI::base() .'modules/mod_junewsultra/tmpl/'.$template.'/css/style.css" type="text/css" />');

}

if( file_exists($layoutpath) )
{
    if ($all_in == 1)
    {
        if($custom_heading == 1)
        {
            $heading = $text_all_in;
            $heading_link = $link_all_in;
        } else {
            $application = JFactory::getApplication();
            $menu = $application->getMenu();

            $heading = ($text_all_in2 ? $text_all_in2 : JRoute::_($menu->getItem( $params->get('link_menuitem') )->title) );
            $heading_link = JRoute::_($menu->getItem( $params->get('link_menuitem') )->link .'&amp;Itemid='. $params->get('link_menuitem'));
        }

        if($heading_link) {
            $heading_link = '<a href="'. $heading_link .'">'. $heading .'</a>';
        } else {
            $heading_link = $heading;
        }

    	$read_all = '<'. $item_heading . ($class_all_in ? ' class="'.$class_all_in.'"' : '') .'>'. $heading_link .'</'. $item_heading .'>';

    }

    if ($all_in == 1 && $params->def('all_in_position') == 0) {
        echo $read_all;
    }

	require($layoutpath);

    if ($all_in == 1 && $params->def('all_in_position') == 1) {
        echo $read_all;
    }

    if( $copy == 1 ) {
    	echo '<span style="clear:both;text-align:right;display:block;line-height:10px;width:100%;font-size:9px;"><a href="http://www.joomla-ua.org" style="color:#ccc;text-decoration:none;" title="Joomla! Україна" target="_blank" >Joomla! Україна</a></span>';
    }

} else {

    echo JText::_("<strong>Template <span style=\"color: green;\">$template</span> do is not found!</strong><br />Please, upload new template to <em>modules/mod_junewsultra/tmpl</em> folder or select other template from back-end!");

}