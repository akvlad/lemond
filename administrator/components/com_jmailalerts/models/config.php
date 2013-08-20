<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');

class jmailalertsModelConfig extends JModel
{
	/*
		Function saves configuration data to a file
	*/
	function store(){

		$mainframe=JFactory::getApplication(); 
		$config	= JRequest::getVar('data', '', 'post', 'array', JREQUEST_ALLOWRAW );
		$file = 	JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php";

		if ($config)
		{
			$file_contents="<?php \n\n";
			$file_contents.="\$emails_config=array(\n".$this->row2text($config)."\n);\n";
			$file_contents.="\n?>";
			$img_path = 'img src=\"'.JURI::root(); 
	    	$file_contents=str_replace( 'img src=\"', $img_path, $file_contents );
			if (JFile::write($file, $file_contents)) 
				$msg = JText::_('CONFIG_SAVED');
			else
				$msg = JText::_('CONFIG_SAVE_PROBLEM');
		}
		$mainframe->redirect('index.php?option=com_jmailalerts&view=config', $msg);
	}//store() ends

	
	/*
		This function returns the plugin names under the email-alerts component 
	*/	
	function getPluginNames(){
		//FIRST GET THE EMAIL-ALERTS RELATED PLUGINS FRM THE `jos_plugins` TABLE
		if(JVERSION < '1.6.0')
     	$this->_db->setQuery('SELECT element FROM #__plugins WHERE folder = \'emailalerts\'  AND published = 1');
		else
			$this->_db->setQuery('SELECT element FROM #__extensions WHERE folder = \'emailalerts\'  AND enabled = 1');
      $email_alert_plugins_array = $this->_db->loadResultArray();//Get the plugin names and store in an array
		//return the array 
    
		return  $email_alert_plugins_array;
	}//getPluginTags() ends	

	/*
		Function to return description of the 'emailalert' plugins from the XML file
		Input: plugin_array which contains names of the 'emailalert' plugins
		This function is called only from the view.html.php file 
	*/	
	function getPluginDescriptionFromXML($plugin_array){
		
		$plugin_description_array =  array();
	
		$i = 0; //set array index to 0
    if($plugin_array)
		foreach($plugin_array as $emailalert_plugin){
			//Get the description of the plugin from the XML file	
     	if(JVERSION < '1.6.0')
				$data = JApplicationHelper::parseXMLInstallFile(JPATH_SITE . DS . 'plugins'. DS ."emailalerts" . DS . $emailalert_plugin.'.xml');
      else
				$data = JApplicationHelper::parseXMLInstallFile(JPATH_SITE . DS . 'plugins'. DS ."emailalerts" . DS . $emailalert_plugin. DS . $emailalert_plugin.'.xml');
			//Store it in the array
			$plugin_description_array[$i++] =  $data['description'];
		}//foreach ends
		
		//Return the array
		return $plugin_description_array;
	}//getPluginDescriptionFromXML() ends
	
	/*
		This formats the data to be stored in the config file
	*/
	function row2text($row,$dvars=array())
	{
		
		reset($dvars);
		while(list($idx,$var)=each($dvars))
			unset($row[$var]);
		$text='';
		reset($row);
		$flag=0;
		$i=0;
		while(list($var,$val)=each($row))
		{
			if($flag==1)
				$text.=",\n";
			elseif($flag==2)
				$text.=",\n";
			$flag=1;

			if(is_numeric($var))
				if($var{0}=='0')
					$text.="'$var'=>";
				else
					{
					if($var!==$i)
						$text.="$var=>";
					$i=$var;
					}
			else
				$text.="'$var'=>";
			$i++;

			if(is_array($val))
				{
				$text.="array(".$this->row2text($val,$dvars).")";
				$flag=2;
				}
			else
				$text.="\"".addslashes($val)."\"";
			}
		
		return($text);
	}		
}
?>
