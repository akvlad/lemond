<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');

class jmailalertsModelMailqueue extends JModel{

	/*
		Function to get mail queue. Mail queue is nothing but a list of records containing user info of the users who will be sent emails  
	*/
	function getMailQueue(){
		//Get joomla db instance
		$db = JFactory::getDBO();

		//Include the config file to get some settings - batch size, number of emails
		require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");

		//Set query string to get the users whom mails wud be sent to
		$query 			= "SELECT  u.id,u.name,u.email,e.date, e.plugins_subscribed_to" 
					. " FROM #__users AS u, #__email_alert AS e"
					. " WHERE u.id = e.user_id"
					. " AND (DATEDIFF(CURDATE(), e.date) = e.option OR e.date = '0000-00-00 00:00:00')"
					. " AND e.option <> 0";
		//Get the settings from the config file
		$enable_batch   	=  $emails_config["enb_batch"];
		$numberofmails  	=  $emails_config["inviter_percent"];

		if($enable_batch=='Yes')
			$query .= " LIMIT {$numberofmails}";
		//Execute query
		$db->setQuery($query);
		//Return the resultset
		return $db->loadObjectList();
	}//getMailQueue() ends
	
}//class jmailalertsModelMailqueue ends
?>
