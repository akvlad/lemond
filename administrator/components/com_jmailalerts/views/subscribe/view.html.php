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
class jmailalertsViewsubscribe extends JView
{
	/**
	 * display method of Alertype view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//Get the model
		$model = $this->getModel(); 
			
		//get the hello
		$alert_nm		=& $this->get('Data');
		$this->assignRef('alert_nm', $alert_nm);
		
		$subscript		=& $this->get('Data');
		$isNew		= ($subscript->id < 1);
		$this->assignRef('subscript', $subscript);
		
		$text = $isNew ? JText::_( 'JMA_NEW' ) : JText::_( 'JMA_EDIT' );
		
		//GET THE CSS
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/mail_alert_general.css'); 
		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(   JText::_( 'SUBSCRIBE' ).': <small><small>[ ' . $text.' ]</small></small>','manageuser' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('subscribe',		$subscribe);
		
		$alert_nm = $model->getalert_nm();
        $this->assignRef('alert_nm',$alert_nm);
        //get frequences
		$rows=$model->getFreq();
        $this->assignRef('rows',$rows);
        	
        	//get allowed freq
        	$allowfreq=$model->getAllowedfreq();
        	$this->assignRef('allowfreq',$allowfreq);
  
		parent::display($tpl);
	}
}
