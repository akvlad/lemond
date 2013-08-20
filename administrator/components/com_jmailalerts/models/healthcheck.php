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

class jmailalertsModelHealthcheck extends JModel
{
 
    
	
 	function healthcheck()
 	{  
		jimport('joomla.filesystem.file');
		require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");	
		//require_once(JPATH_SITE . DS .'components'. DS .'com_jmailalerts'. DS .'models'. DS .'emails.php');		
       
        $db = JFactory::getDBO();
        $data = array(); 
        
        $data['installed'] = $this->installedPlugins($db); 
        $data['enable'] = $this->enabledPlugins($db);
        $data['plgname'] = $this->getPlugnames($db);    
        $data['alerts'] = $this->createdAlert($db);
        $data['published'] = $this->publishedAlert($db); 
        $data['defaults'] = $this->defoultAlert($db);
        $data['synced'] = $this->syncedAlert($db); 
		
		return $data;
	}
  
	//get installed plugin
	function installedPlugins($db)
	{
		if(JVERSION < '1.6.0')
     	$this->_db->setQuery('SELECT COUNT(p.id)  AS number FROM #__plugins AS p WHERE folder=\'emailalerts\' ');
     	else
     	$this->_db->setQuery('SELECT COUNT(e.extension_id)  AS number FROM #__extensions AS e WHERE folder=\'emailalerts\' ');
		
		//$db->setQuery("SELECT COUNT(p.id)  AS number FROM #__plugins AS p WHERE p.folder = 'emailalerts' ");
		$installplg	= $this->_db->loadResult(); 
		return $installplg;
		
	}
	
	//get enable plugin
	function enabledPlugins($db)
	{   
		if(JVERSION < '1.6.0')
     	$this->_db->setQuery('SELECT COUNT(p.id) AS number FROM #__plugins AS p WHERE p.folder = \'emailalerts\' AND p.published = \'1\' ');
     	else
     	$this->_db->setQuery('SELECT COUNT(e.extension_id) AS number FROM #__extensions AS e WHERE folder=\'emailalerts\' AND e.enabled = \'1\' ');
	
		
		//$db->setQuery("SELECT COUNT(p.id) AS number FROM #__plugins AS p WHERE p.folder = 'emailalerts' AND p.published = '1' ");
		$enableplg	= $this->_db->loadResult(); 
		return $enableplg;
	}
	
	//get created alert
	function createdAlert($db)
	{
		$db->setQuery("SELECT COUNT(al.id) AS number FROM #__email_alert_type AS al ");
		$created	= $db->loadResult();
		return $created;
	}
	
	//get names of plugin
	function getPlugnames()
	 {
	    if(JVERSION < '1.6.0')
     	$this->_db->setQuery('SELECT name, published FROM #__plugins WHERE folder=\'emailalerts\' ');
     	else
     	$this->_db->setQuery('SELECT name, enabled FROM #__extensions WHERE folder=\'emailalerts\' ');
	 
	    $plugname= $this->_db->loadObjectList();//return the plugin data array
	 
	    return $plugname =(!empty($plugname)) ? $plugname : JText::_('NO_PLUGINS_ENABLED_OR_INSTALLED') ;
	    	
	    
	 }
	
	//get published alert
	function publishedAlert($db)
	{
		$db->setQuery("SELECT COUNT(al.id) AS number FROM #__email_alert_type AS al WHERE al.published = '1' ");
		$created	= $db->loadResult();
		return $created;
	}
    
    //set defoult alert
    function defoultAlert($db)
    {//need change here
	    $db->setQuery("SELECT at.id FROM #__email_alert_type AS at WHERE at.isdefault = 1 ");
		$defoult	= $db->loadResultArray();
				
		$defoult =(!empty($defoult['0'])) ? COUNT($defoult) : 0 ; 
		
		return $defoult; 	
	}
	
	//set synced alert
    function syncedAlert($db)
    {
		$db->setQuery("SELECT COUNT(DISTINCT(ea.alert_id)) AS number FROM #__email_alert AS ea ");
		$synced	= $db->loadResult();
		return $synced;
	}
}//class mailalertModelHealthcheck ends
?>
