<?php
/**
 * Alertype Model for Alertype World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Alertype Alertype Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class jmailalertsModelAlertype extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();
        $array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function getPluginData()
  {
  	if(JVERSION >= '1.6.0')
    $this->_db->setQuery("SELECT name,element FROM #__extensions WHERE enabled=1 AND folder='emailalerts'");
    else
    $this->_db->setQuery("SELECT name,element FROM #__plugins WHERE published=1 AND folder='emailalerts'");
    
    return $this->_db->loadObjectList();//return the plugin data array
  }//getPluginData() ends


	function getPluginNames()
	{
		//FIRST GET THE EMAIL-ALERTS RELATED PLUGINS FRM THE `jos_plugins` TABLE
		if(JVERSION < '1.6.0')
     	$this->_db->setQuery('SELECT element FROM #__plugins WHERE folder = \'emailalerts\'  AND published = 1');
		else
			$this->_db->setQuery('SELECT element FROM #__extensions WHERE folder = \'emailalerts\'  AND enabled = 1');
      $email_alert_plugins_array = $this->_db->loadResultArray();//Get the plugin names and store in an array
		//return the array 
    
		return  $email_alert_plugins_array;
	}//getPluginTags() ends	
	
	
	/**
	 * 
	 *
	 * @access	public
	 * @param	int Alertype identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a alert
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__email_alert_type'.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->greeting = null;
		}
		return $this->_data;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{	
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );
		//allowed frequencies
		$selected_freq=JRequest::getVar('c');
		$selected_freq = implode(',',$selected_freq);
		$row->allowed_freq = $selected_freq;
		
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		//$row->template = JRequest::getVar( 'template', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$tmpl = JRequest::getVar( 'template', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		    $img_path = 'img src="'.JURI::root(); 
	    	$file_contents=str_replace( 'img src="', $img_path, $tmpl);
		$row->template = $file_contents;
		
		$row->template_css = JRequest::getVar( 'template_css', '', 'post', 'string', JREQUEST_ALLOWRAW );
		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids ))
		 {		
			foreach($cids as $cid)
			 { 
				 //delete all records from table when alert deleted
				 $this->_db->setQuery("DELETE e.* FROM #__email_alert AS e WHERE e.alert_id =".$cid );
			     $this->_data = $this->_db->loadObject();
			     
			     if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}
	
	//setting frequencies
	function getFreq()
  {
		$rows = array(  array('value'=>'1','text'=>JText::_('FREQUENCY_DAILY')),
		array('value'=>'7','text'=>JText::_('FREQUENCY_WEEKLY')),
		array('value'=>'15','text'=>JText::_('FREQUENCY_FORTNIGHTLY')),
		array('value'=>'30','text'=>JText::_('FREQUENCY_MONTHLY')),
		);
	       
		return $rows;
  }
   
  //get allowed fequencies   	
 	function getAllowedfreq()
  {
  	$query = ' SELECT allowed_freq FROM #__email_alert_type'.
    							'  WHERE id = '.$this->_id;
	  $this->_db->setQuery( $query );
		$allowed_freq= $this->_db->loadResult();
		$allowed_freq =explode(',',$allowed_freq);
    	
    return $allowed_freq;
   }
	
	function getPluginDescriptionFromXML($plugin_array)
	{
		
		$plugin_description_array =  array();
	
		$i = 0; //set array index to 0
    		if($plugin_array)
		foreach($plugin_array as $emailalert_plugin)
		{
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

}
