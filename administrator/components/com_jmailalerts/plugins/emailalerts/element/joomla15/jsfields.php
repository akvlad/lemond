<?php 
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();


class JElementJsfields extends JElement {
	

  	var   $_name = 'Jsfields';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		
		$db = &JFactory::getDBO();
		
		$db->setQuery("SELECT id AS value, name AS text 
		FROM #__community_fields 
		WHERE published = 1 ");
		$options = $db->loadObjectList();
		
		return JHTML::_('select.genericlist', $options, $control_name.'['.$name.'][]', 'multiple="true" size="5"', 'value', 'text', $value);

	}
}
?>

