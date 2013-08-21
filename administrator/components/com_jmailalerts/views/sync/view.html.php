<?php
/*
 * @package J!MailALerts
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
require_once(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");

class jmailalertsViewsync extends JView
{
    function display($tpl = null)
    {
        $this->_setToolBar();

        //Get the model
        $model=$this->getModel();

        //Get enables plugin names and element 
        $plugin_data=$model->getPluginData();
        $this->assignRef('plugin_data',$plugin_data);
	
				//get alert name
				$alertname = $model->getAlertname();
				$this->assignRef('alertname',$alertname);
	
        
       	//Get the plugin names under email-alerts
				$email_alert_plugin_names = $model->getPluginNames();
				//Assign a ref	to the array
				$this->assignRef('email_alert_plugin_names', $email_alert_plugin_names);
						  

        parent::display();
    }

    function _setToolBar()
    {
        //GET THE CSS
        $document =& JFactory::getDocument();
        $document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/mail_alert_general.css');

        // Get the toolbar object instance
        $bar =& JToolBar::getInstance('toolbar');
        JToolBarHelper::title(JText::_('SYNC'), 'sync');
        if(JVERSION >='1.6.0')
            JToolBarHelper::save('save','Save');
        else
            JToolBarHelper::save();
        
        JToolBarHelper::cancel();
    }
}
?>
