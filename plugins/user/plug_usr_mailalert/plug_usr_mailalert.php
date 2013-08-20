<?php
/**
 * @package Mail Alert User Plugin
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Example system plugin
 */
class plgUserplug_usr_mailalert extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	 
	 
	
	function onUserAfterSave($user, $isnew, $succes, $msg)
  {
  	
				if($isnew){
				
				$db			= 	JFactory::getDBO();
				
				$userid			= 	$user['id'];
				
				//recieve array of alert id where set to defaoult
				$query = 'SELECT id  FROM #__email_alert_type WHERE isdefault = 1';
				$db->setQuery($query);
				$alertid = $db->loadResultArray();
				$alertid_string = $alertid;
				
				//edit start ---old code--
				/*$query= 'SELECT alert_id  FROM #__email_alert_Default WHERE id=0';
				$db->setQuery($query);
				$alertid = $db->loadResult();
				$alertid_string = explode(',',$alertid);*/
				
				$alertqry ="";
				for($i=0; $i<count($alertid_string);$i++)
					{
						$alertqry.="id=".$alertid_string[$i];
						if($i != (count($alertid_string) - 1))
						$alertqry.=" OR ";
			
					}
				
				
	     	$query='SELECT element FROM #__extensions WHERE folder = \'emailalerts\'  AND enabled = 1';
	     	$db->setQuery($query);
	      $plugnamecompair = $db->loadResultArray();
	      $plugnamesend = implode(',',$plugnamecompair);
	      $plugnamecompair =explode(',',$plugnamesend);
	      
	    
	      $cnt=0;$rnt=7;	      
	      $query="SELECT id,default_freq,template FROM #__email_alert_type WHERE $alertqry";
				$db->setQuery($query);
				$result=$db->loadObjectList();
	      
	      foreach($result as $key)
				{	
					$email_alert_entry_object = new stdClass;
					$email_alert_entry_object->user_id = $userid;
					$email_alert_entry_object->alert_id = $key->id;				
					$email_alert_entry_object->option =  $key->default_freq;
					$email_alert_entry_object->date = date("Y-m-d H:i:s",mktime(0, 0, 0, date("m"), date("d")- $key->default_freq, date("Y")));//G
					$entry ="";
					for($i=0;$i<count($plugnamecompair);$i++)
          {
						if (strstr($key->template,$plugnamecompair[$i]))
						$plugin_name_string[] =$plugnamecompair[$i];
          }
                		
         
				    
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
							 if($plug == 'jma_latestnews_js')
							 { $cnt++;}
							 if(!($cnt>$rnt))
							  $entry.=$plug.'|'.$v."\n";
							  }
							  if($plug == 'jma_latestnews_js')
							  {$entry=  str_replace('category','catid',$entry);
							   $entry=  str_replace('sections','secid',$entry);
							  }
							  
						}
						$cnt = 0;
				  }
				    
	   
				 unset($plugin_name_string);
				 unset($match);
		
				$email_alert_entry_object->plugins_subscribed_to = $entry;	

					if(!$db->insertObject('#__email_alert', $email_alert_entry_object)){
						echo "Insertion error";

						exit;
					}	   
		
				}
				
			}//if($isnew)
	}
				
}	
