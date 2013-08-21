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

class jmailalertsControllerMailsimulate extends jmailalertsController
{
	/**
	 * Calls the model method to return email address
	 */

	function simulate(){   
		// Check for request forgeries
		//JRequest::checkToken() or jexit( 'Invalid Token' ); //debug
		$model =& $this->getModel('mailsimulate');

		$target_user_id 	   = JRequest::getInt('user_id_box');
	
		if($target_user_id == '') {
			$msg = JText::_( 'ENTR_ID' ); 
			$this->setRedirect( 'index.php?option=com_jmailalerts&view=mailsimulate', $msg );
		}
		else{	
		
		  $val = $model->simulate();
		
			if ($val == 1) {
				$msg = JText::_( 'MAIL_SENT' );
				$this->setRedirect( 'index.php?option=com_jmailalerts&view=mailsimulate', $msg );
			}
			elseif($val == 2){
				$msg = JText::_( 'NO_USER' );
				$this->setRedirect( 'index.php?option=com_jmailalerts&view=mailsimulate', $msg );
			}
			elseif($val == 3){
				$msg = JText::_( 'NO_MAIL_SENT' );
				$this->setRedirect( 'index.php?option=com_jmailalerts&view=mailsimulate', $msg );
			}
			 else {
				$msg = JText::_( 'ERROR_SENDING_EMAIL');
				$this->setRedirect( 'index.php?option=com_jmailalerts&view=mailsimulate', $msg, 'error' );
			}
		}
	}//simulate() ends

}?>
