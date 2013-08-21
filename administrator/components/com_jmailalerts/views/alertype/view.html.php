<?php
/**
 * Alertype View for Alertype World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Alertype View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class jmailalertsViewalertype extends JView
{
	/**
	 * display method of Alertype view
	 * @return void
	 **/
	function display($tpl = null)
	{
		
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
		
		
		//get the hello
		$alertype		=& $this->get('Data');
		$isNew		= ($alertype->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		
		//GET THE CSS
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/mail_alert_general.css'); 
		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(   JText::_( 'ALERTYPE' ).': <small><small>[ ' . $text.' ]</small></small>','alerttypes' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('alertype',		$alertype);
		
		//get frequences
		$rows=$model->getFreq();
        	$this->assignRef('rows',$rows);
        	
        	//get allowed freq
        	$allowfreq=$model->getAllowedfreq();
        	$this->assignRef('allowfreq',$allowfreq);
  
		parent::display($tpl);
	}
}
