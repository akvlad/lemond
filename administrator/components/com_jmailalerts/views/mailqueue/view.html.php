<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class jmailalertsViewmailqueue extends JView
{
	function display($tpl = null)
	{
		$this->_setToolBar();

		//Get the model
		$model = $this->getModel();
		
		//Get the mail queue
		$mail_queue_array = $model->getMailQueue();
		//Assign a ref	to the array
		$this->assignRef('mail_queue_array', $mail_queue_array);			
		
		parent::display();
	}

	function _setToolBar()
	{
		//GET THE CSS
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/mail_alert_general.css'); 

		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
		JToolBarHelper::title(JText::_('MAIL_QUEUE'), 'mailqueue');
	}
	
}

?>
