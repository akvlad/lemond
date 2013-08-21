<?php 
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();


class JElementCbfields extends JElement {
	

  	var   $_name = 'Cbfields';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		//include(JPATH_SITE . '/components/com_comprofiler/plugin/language/default_language/default_language.php');
		$db = &JFactory::getDBO();
		//$type = "'predefined','status','datetime','formatname','primaryemailaddress','password','userparams',' connections','forumstats','image','connections','forumsettings'";
    
    $type = "'text','textarea','select','multiselect','checkbox','multicheckbox','radio'";
		
		$db->setQuery("SELECT name AS value, title AS text 
		FROM #__comprofiler_fields 
		WHERE `table` LIKE '#__comprofiler' AND published = 1 AND type IN ({$type})");
		$options = $db->loadObjectList();
		
		return JHTML::_('select.genericlist', $options, $control_name.'['.$name.'][]', 'multiple="true" size="5"', 'value', 'text', $value);

	}
}
?>

