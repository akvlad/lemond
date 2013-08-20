<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');

/**
 * J!MailAlerts Component Controller
 *
 * @package		Joomla
 * @subpackage	J!MailAlerts
 */
class jmailalertsController extends JController
{
	/**
	 * Method to show a alters view
	 *
	 * @access	public
	 * @since	1.5
	 */
	function display()
	{
		$vName = JRequest::setVar('view', 'emails' );
		$vLayout = 'default';
		$mName = JRequest::setVar('view', 'emails' );
		parent::display();
	}
	
	/*
		Function called when the user saves the email preferences(Daily, monthly, weekly, etc) from the frontend 
	*/	
	function savePref()
	{
		//Get the model
		$model = & $this->getModel('emails');
		//call the mailto() function
		$model->savePref();
	}
	
	function processMailAlerts()
	{
		$model = & $this->getModel('emails');
		$model->processMailAlerts();
	}
	
}
