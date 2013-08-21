<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
jimport('joomla.application.component.model');

class jmailalertsViewajaxsync extends JView
{
    function display($tpl = null)
    {
		//Get the instance to joomla database
		$db	= JFactory::getDBO();
		$alertid = JRequest::getVar('alertid');
		$alertid_string = explode(',',$alertid);
		$alertqry=NULL;
		for($i=0; $i<count($alertid_string);$i++)
		{
			$alertqry.="id=".$alertid_string[$i];
			if($i != (count($alertid_string) - 1))
				$alertqry.=" OR ";
		}
		if($alertqry){	
			$query="SELECT id,default_freq,template FROM #__email_alert_type WHERE $alertqry";
		}else{
			$query="SELECT id,default_freq,template FROM #__email_alert_type ";
		}
		$db->setQuery($query);
		$result=$db->loadObjectList();
		
		$plugnamecompair =JRequest::getVar('plugname');
	    $plugnamecompair =explode(',',$plugnamecompair);

		$reset=JRequest::getVar('chk');
				
		if($reset=="1" && (!JRequest::getVar('flag')))
		{
			/*Changed in 2.4.3*/
			//$query= "TRUNCATE TABLE `#__email_alert`";
			//do not touch the alert preferences of those users who have unsubscribed from at least 1 alert type
			$query = "SELECT user_id FROM  #__email_alert WHERE `option` = 0";
			$db->setQuery($query);
			$safe=$db->loadResultArray();
			$safe=implode(',',$safe);
			$resync = JRequest::getVar('rsync');
            
            if($safe){
            	$query = "DELETE FROM #__email_alert 
						  WHERE user_id NOT IN (". $safe ." ) ";
            }//code resync unsubscribe users
            else
            {
            	$query="DELETE FROM #__email_alert";
            }
            if($resync =="1" && (!JRequest::getVar('flag')) )
		    {
			    $query="DELETE FROM #__email_alert";
			}
			     $db->setQuery($query);
		         $db->query();
		   }
		
		if(!JRequest::getVar('flag')){
			//if the flag is zero, then this is the frst call to the raw view. So count the number of users to be synced and store it.
			//The count will be sent back for percentage calculation
			$db->setQuery("SELECT count(id) FROM #__users WHERE id NOT IN (SELECT user_id FROM  #__email_alert ) AND block=0" );
			$no_of_users	=	$db->loadResult();			
		}//if ends

		//Get limited (decided by the batch_size) users from the joomla users table 
		$db->setQuery("SELECT id FROM #__users WHERE id NOT IN (SELECT user_id FROM  #__email_alert ) AND block=0 ORDER BY id LIMIT  0, ".JRequest::getInt('batch_size'));
		$rows=$db->loadResultArray();
       
        //old code
		/*$db->setQuery("UPDATE `#__email_alert_Default` SET `alert_id`= '".$alertid."' WHERE `id` = 0 LIMIT 1;");
		$db->query();*/
		
		 $alertypeid = explode(",",$alertid);
		 for($i=0;$i<COUNT($alertypeid);$i++)
        {
			$db->setQuery("UPDATE #__email_alert_type SET  `isdefault`= 1  WHERE `id`= ".$alertypeid[$i]." ");
            $db->query();
           // echo $db->stderr();
		}
		
		//if there are no rows, then all users ar synced; return 'No rows'
		if(count($rows) == 0){
			echo "No rows";
			exit;
		}

		//Start of the mega-loop to insert data into the `email_alert` table
		foreach($rows as $id)
		{	
			foreach($result as $key)
			{	
				$email_alert_entry_object = new stdClass;
				$email_alert_entry_object->user_id = $id;
				$email_alert_entry_object->alert_id = $key->id;				
				$email_alert_entry_object->option =  $key->default_freq;
				$email_alert_entry_object->date = date("Y-m-d H:i:s",mktime(0, 0, 0, date("m"), date("d")- $key->default_freq, date("Y")));//Get the current date and time
				//try another code start ----
				$entry ="";
				for($i=0;$i<count($plugnamecompair);$i++)
                {
					if (strstr($key->template,$plugnamecompair[$i]))
						$plugin_name_string[] =$plugnamecompair[$i];
           		}
              
				if(JVERSION < '1.6.0')
				{
				    foreach($plugin_name_string as $plug)
				    {
						$plugin = JPluginHelper::getPlugin( 'emailalerts',$plug);
						$pluginParams = new JParameter( $plugin->params );
						$pluginParamsDefault= $pluginParams->_raw;
						$newlin = explode("\n",$pluginParamsDefault);
						foreach($newlin as $v)
						{
							if(!empty($v))
							{
							$v=str_replace('|',',',$v);
							$entry.=$plug.'|'.$v."\n";
							}
						}
				    }
				}
				else
				{
				    foreach($plugin_name_string as $plug)
				    {
						$query= "select params from #__extensions where element='".$plug."' && folder='emailalerts'";
						$db->setQuery($query);
						$plug_params=$db->loadResult();

						if(preg_match_all('/\[(.*?)\]/',$plug_params,$match))
						{
							foreach($match[1] as $mat)
							{
							$match=str_replace(',','|',$mat);
							$plug_params= str_replace($mat,$match,$plug_params);
							}
						}
						$newlin = explode(",",$plug_params);
						foreach($newlin as $v)
						{
							if(!empty($v))
							{
							$v=str_replace('{','',$v);$v=str_replace(':','=',$v);$v=str_replace('"','',$v);$v=str_replace('}','',$v);$v=str_replace('[','',$v);$v=str_replace(']','',$v);$v=str_replace('|',',',$v);
							$entry.=$plug.'|'.$v."\n";
							}
							if($plug == 'jma_latestnews_js')
							$entry=  str_replace('category','catid',$entry);
						}
				    }
				}
				unset($plugin_name_string);
				unset($match);
				//try another code end ----
				//$email_alert_entry_object->plugins_subscribed_to = $key->plugin;
				$email_alert_entry_object->plugins_subscribed_to = $entry;	
				if(!$db->insertObject('#__email_alert', $email_alert_entry_object)){
					echo "Insertion error";
					//echo $db->stderr();
					exit;
				}	
			//print_r($email_alert_entry_object);//debug
			}
       }//end of the mega-for loop
		if(!JRequest::getVar('flag'))
			echo "1,".$no_of_users;
		else
			echo "1"; 
    }//display() ends
}//class JphplistViewajaxsync ends
