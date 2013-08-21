<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );

class jmailalertsModelMailsimulate extends JModel
{
    /*
	*
	*
	*/
 	function simulate()
 	{
		jimport('joomla.filesystem.file');
		require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");	
		require_once(JPATH_SITE . DS .'components'. DS .'com_jmailalerts'. DS .'models'. DS .'emails.php');		

		$nofrm=0; 
		//$today=date('Y-m-d H:i:s');  previous code
		
		//get date selected in simulate
		$today=JRequest::getString('select_date_box'); 
		$email_status=0;
		$target_user_id=JRequest::getInt('user_id_box');
		$alert_type_id=JRequest::getInt('altypename');
		$destination_email_address=JRequest::getVar('send_mail_to_box');
		$flag=JRequest::getVar('flag');
		$query="SELECT  u.id,u.name,u.email,a.template, a.email_subject, e.date, e.alert_id ,a.template_css, e.plugins_subscribed_to,a.respect_last_email_date" 
				. " FROM #__users AS u, #__email_alert AS e , #__email_alert_type AS a"
				. " WHERE e.user_id = ".$target_user_id
				. " AND e.alert_id = ".$alert_type_id
				. " AND u.id = e.user_id"
				. " AND a.id = e.alert_id";
	
		$this->_db->setQuery($query);
		$target_user_data = $this->_db->loadObjectList(); 
	
		$i=0;
		foreach($target_user_data as $data)
		{
			
			if($data->date)
			{
			   //$data[$i]->date = $today; 
				
			    $data->date = ($today) ? $today:$data->date; 
			    
		     }
		    else
		    {
				$data[$i]->date = ($today) ? $today:$data[$i]->date;
				
			}
		$i++;
	    }

		if($target_user_data)
		{	
			$target_user_data[0]->email = $destination_email_address;
			//get template from alert type
			$query ="SELECT template FROM #__email_alert_type WHERE id =$alert_type_id";
			$this->_db->setQuery($query);
			$msg_body= $this->_db->loadResult();
			$skip_tags=array('[SITENAME]','[NAME]','[SITELINK]','[PREFRENCES]', '[mailuser]');
			$tmpl_tags=jmailalertsModelEmails::get_tmpl_tags($msg_body,$skip_tags);
			$remember_tags=jmailalertsModelEmails::get_original_tmpl_tags($msg_body,$skip_tags); 		
			return jmailalertsModelEmails::getMailcontent($target_user_data[0],$flag,$tmpl_tags,$remember_tags);
				
			/*end added by manoj version 2.4*/
		}
		else{ 
			return 2;
		}
	}

   /*
	*Function to call plugins and return the output
	*This function is called from the addtomailq() function above
	*
	*/	
	function getPlugins($id,$date)
	{
		JPluginHelper::importPlugin('emailalerts');
		$dispatcher =& JDispatcher::getInstance();
		$results = $dispatcher->trigger('onBeforeAlertEmail',array($id,$date));//Call the plugin and get the result
		return $results;
	}//getPlugins() ends
	
	//get alerttype
	function getAlertypename()
	{
		$db	= JFactory::getDBO();
		$db->setQuery("SELECT id  AS val, alert_name AS text FROM #__email_alert_type ");
		$altypename	= $db->loadObjectList();
		return JHTML::_('select.genericList', $altypename, 'altypename', 'class="inputbox"', 'val', 'text', '');
	}

}//class mailalertModelMailsimulate ends
?>
