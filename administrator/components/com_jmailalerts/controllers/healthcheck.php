<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
defined('_JEXEC') or die('Restricted access');
require_once( JPATH_COMPONENT.DS.'views'.DS.'config'.DS.'view.html.php' );

jimport('joomla.application.component.controller');

class jmailalertsControllerHealthcheck extends jmailalertsController
{
	/**
	 * Calls the model method to return email address
	 */
	

	$this->setRedirect('index.php?option=com_jmailalerts&view=healthcheck&layout=default');

}?>
