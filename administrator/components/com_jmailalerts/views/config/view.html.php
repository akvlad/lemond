<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class jmailalertsViewconfig extends JView
{
	function display($tpl = null)
	{
		$this->_setToolBar();
		
		//Get the model
		$model = $this->getModel();
		//Get the plugin names under email-alerts
		$email_alert_plugin_names = $model->getPluginNames();
		//Assign a ref	to the array
		$this->assignRef('email_alert_plugin_names', $email_alert_plugin_names);	
		
		//Get the description of the plugins from XML file
		$plugin_description_array = $model->getPluginDescriptionFromXML($email_alert_plugin_names);
		//Assign a ref	to the array
		$this->assignRef('plugin_description_array', $plugin_description_array);			
		
		parent::display();
	}
	
	function _setToolBar()
	{
		//GET THE CSS
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/mail_alert_general.css'); 

		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
		JToolBarHelper::title(JText::_('CONFIG_MGR'), 'config');
		if(JVERSION >='1.6.0')
     JToolBarHelper::save('save','Save');
    else
     JToolBarHelper::save();
    
	}
}

?>
