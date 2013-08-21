<?php 

	/**
	* @package JomSocial Network Suggest
	* @copyright Copyright (C) 2010-2011 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
	*/ 

	// Check to ensure this file is within the rest of the framework
	defined('JPATH_BASE') or die();
	jimport('joomla.html.html');
	class JFormFieldJsfields extends JFormFieldList
	{
		var $_name = 'Jsfields';
		protected function getOptions() 
		{ 
			// Get the database object and a new query object. 
			$db = JFactory::getDBO(); 
			$query = $db->getQuery(true); 
		
			//Build the query. 
			$query="SELECT id AS value, name AS text 
					FROM #__community_fields 
					WHERE published = 1
					";
			// Set the query and load the options. 
			$db->setQuery($query); 
			$options = $db->loadObjectList(); 
		
			// Check for a database error. 
			if ($db->getErrorNum()){ 
				JError::raiseWarning(500, $db->getErrorMsg()); 
			}  
		
			if($options)
			{
				foreach ($options as $i=>$option) { 
					$options[$i]->text = JText::_($option->text); 
				}
				//Merge any additional options in the XML definition. 
				$options = array_merge(parent::getOptions(), $options);
				return $options; 
			} 
			else{
				return JText::_('NO_FIELDS_FOUND');
			}
		} 
	}
?>
