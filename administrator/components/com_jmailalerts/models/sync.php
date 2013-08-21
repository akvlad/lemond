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
jimport( 'joomla.html.parameter' );
jimport('joomla.form.form');

/**
 * class will contain function to store
 * alerts records
 */
class jmailalertsModelsync extends JModel
{
    
    /*
     * 
     * Function to get the plugin data(names, elements) related to ejmailalerts
     * 
     */
    function getPluginData()
    {
        if(JVERSION >= '1.6.0')
     	    $this->_db->setQuery("SELECT name,element FROM #__extensions WHERE enabled=1 AND folder='emailalerts'");
     	else
     	    $this->_db->setQuery("SELECT name,element FROM #__plugins WHERE published=1 AND folder='emailalerts'");
     	
     	return $this->_db->loadObjectList();//return the plugin data array
    }//getPluginData() ends

    
    /*
     * 
     * Function called from view.html.php of sync. It returns the alert name with default selected alert id
     * 
     */
     
     function getAlertname()
     {
				//get default alert ids 
				// old code ->  $this->_db->setQuery("SELECT alert_id  FROM #__email_alert_Default");
				
				$this->_db->setQuery("SELECT et.id FROM #__email_alert_type AS et WHERE et.isdefault = 1 ");
				$defaultselected= $this->_db->loadResult();
				$defaultselected= explode(',',$defaultselected);
				//get alert type name
				$this->_db->setQuery("SELECT id  AS val, alert_name AS text FROM #__email_alert_type ");
				$altypename	= $this->_db->loadObjectList();
				return JHTML::_('select.genericList', $altypename, 'altypename[]', 'class="inputbox" multiple', 'val', 'text', $defaultselected);
					 
     }
    
    /*
     * 
     * Get the default preferences from jmail alerts table 
     * 
     */
     //plugin name----------
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
     * 
     * THis functions saves default parameters in jmail alert default table
     */
    function store()
    {
        $mainframe=JFactory::getApplication();
   		$alertypeid=JRequest::getVar('alertypeid');
        $alertypeid=explode(",",$alertypeid);
        $query = "UPDATE #__email_alert_type SET isdefault = 0";
        $this->_db->setQuery($query);
        $this->_db->query();
        $email_alert_entry_object=new stdClass;
        
        //old code
        /*$email_alert_entry_object->id = 0;
        $email_alert_entry_object->isdefault =$alertypeid;

        $this->_db->insertObject('#__email_alert_Default', $email_alert_entry_object);*/
        
        //set synced alerts to default 
        for($i=0;$i<COUNT($alertypeid);$i++)
        {
			$this->_db->setQuery("UPDATE #__email_alert_type SET isdefault = 1 WHERE id = ".$alertypeid[$i]." ");
            $this->_db->query();
		}
        
        $msg = JText::_('CONFIG_SAVED');
        $mainframe->redirect('index.php?option=com_jmailalerts&view=sync', $msg);
    }
}
