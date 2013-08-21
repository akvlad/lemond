<?php
/**
 * Subscribe Model for Subscribe user World Component
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
class jmailalertsModelSubscribe extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	var $is_edit= null;
	 
	function __construct()
	{//die("in subscribe model");
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
	
	/**
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
			$query = ' SELECT e.* , a.alert_name FROM #__email_alert AS e '.
                     'LEFT JOIN #__email_alert_type AS a ON a.id = e.alert_id '.			
					 'WHERE e.id = '.$this->_id;
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
	 * Method to get a alert
	 * @return object with data
	 */
	function getalert_nm()
{
	    $mainframe			=& JFactory::getApplication();
		$this->_db->setQuery("SELECT alert_name,id FROM #__email_alert_type ");
		$resultstate= $this->_db->loadResultArray();
		
		//$alert_nm 		= $mainframe->getUserStateFromRequest( 'com_jmailalerts.subscribe.alert_nm', 'alert_nm', '', 'word' );
		
		$alert_nm='';
		$sel_alert = $this->getData();
		$this->assign($sel_alert->id);
		if($sel_alert->id){
		$alert_nm = $sel_alert->alert_name;
	     }
		//print_r($alert_nm); die("in the getalert_nm");
		$options = array();
		$options[] = JHTML::_('select.option', $resultstate, JText::_( 'SEL_ALT' ).' -');
	
		foreach($resultstate AS $key=>$val )
		{
		  $key++;
		  $options[]= JHTML::_('select.option',$val,$val);		
	    }

     	return JHTML::_('select.genericlist',   $options, 'alert_nm', 'class="inputbox" size="1" onchange=""', 'value', 'text', $alert_nm );

}
	function assign($val=0)
	{
	  $this->is_edit = $val;
	  //return $val;	
	}
	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{	
		
		$db = JFactory::getDBO();
		
		$data = JRequest::get( 'post' );
		
        $alertid = $this->_db->loadAssocList($this->_db->setQuery("SELECT id,allowed_freq FROM #__email_alert_type WHERE alert_name LIKE '%{$data['alert_nm']}%' "));      
        $this->_db->setQuery("SELECT id FROM #__users ");
		$alluser = $this->_db->loadResultArray();
		
		//check existence of user
		if(in_array($data['user_id'],$alluser) and !$data['id'])
		{  
					    
		    $qry = ("SELECT el.id,el.option ,at.id AS alert_id , at.alert_name FROM #__email_alert as el 
	                LEFT JOIN #__email_alert_type AS at ON at.id = el.alert_id 
	                WHERE el.user_id = ".$data['user_id']."  "); 
	               
			$this->_db->setQuery($qry);
			$alerts_data = $this->_db->loadAssocList();
			//print_r($alerts_data);die("in store() of subscribe");
			foreach($alerts_data AS $a_data)
			{ 
			if($a_data['alert_name']==$data['alert_nm'])
			{
			    return "Alert is already subscribed";
			}
			           	
			}
				
			$myobj = new stdclass;
			$myobj->user_id = $data['user_id'];
			$myobj->alert_id =$alertid[0];         
			$myobj->option  = $data['frequency'];
			//$myobj->date = date(" Y-m-d H:i:s ");
			// substract the value of frequency from current date
			$myobj->date = date ( 'Y-m-d H:i:s' , strtotime ( '-'.$data['frequency'].'day' , strtotime ( date(" Y-m-d H:i:s") ) ) );
			
			//to get plugin params
			$plugnamecompair =$this->getPluginName();
			$entry ="";
			//to get template
			if($alertid[0]){	
			$query="SELECT template FROM #__email_alert_type WHERE id = ".$alertid[0];
			}else{
			$query="SELECT template FROM #__email_alert_type ";
			}
			$db->setQuery($query);
			$tmpl=$db->loadObjectList();
	        foreach($tmpl as $tmpl)
	        {
			for($i=0;$i<count($plugnamecompair);$i++)
              {
					if (strstr($tmpl->template,$plugnamecompair[$i]))
						$plugin_name_string[] =$plugnamecompair[$i];
           	   }
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
				
			//
			$myobj->plugins_subscribed_to = $entry;
			$db->insertObject('#__email_alert',$myobj);	
			
			return true;
				
		}
		elseif($data['id'])
		{   
		  	    $this->_db->setQuery("UPDATE #__email_alert AS e SET e.option=".$data['frequency']." WHERE e.id=".$data['id']." ");
			    $alerts_data = $this->_db->loadAssocList();
			    return "one user subscription updated ";
			
		}
		elseif($alertid['allowed_freq'] != $data['frequency'])
		{
		   return "Select allowed frequency -".$data['frequency'];
		}
		else
		{
			//die("in else of store()");
			return "User Not Exist ";
		}
           
		//allowed frequencies
		$selected_freq=JRequest::getVar('frequency');
		//$selected_freq = implode(',',$selected_freq);
		$row->allowed_freq = $selected_freq;

		
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

		if (count( $cids )) {
			foreach($cids as $cid) {
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
			 
		$frequency=0;
		$mainframe			=& JFactory::getApplication();
		//$frequency 		= $mainframe->getUserStateFromRequest( 'com_jmailalerts.subscribe.frequency', 'frequency', '', 'int' );	
		$sel_freq = $this->getData();
		if($sel_freq->id)
		 {
			  $frequency = $sel_freq->option;  
		 }
		
		$options = array();
		$options[] = JHTML::_('select.option', '', JText::_( 'SEL_FREQ' ).' -');
		$options[] = JHTML::_('select.option', '1', JText::_('FREQUENCY_DAILY'));	
		$options[] = JHTML::_('select.option', '7', JText::_('FREQUENCY_WEEKLY'));	
		$options[] = JHTML::_('select.option', '15', JText::_('FREQUENCY_FORTNIGHTLY'));
		$options[] = JHTML::_('select.option', '30', JText::_('FREQUENCY_MONTHLY'));
		
		return  JHTML::_('select.genericlist', $options, 'frequency', 'class="inputbox" size="1" onchange=""' , 'value', 'text', $frequency);
		
  }
   
  //get allowed fequencies   	
 	function getAllowedfreq()
  {
  	$query = ' SELECT allowed_freq FROM #__email_alert_type';
    							
	  $this->_db->setQuery( $query );
		$allowed_freq= $this->_db->loadResult();
		$allowed_freq =explode(',',$allowed_freq);
		//print_r($this->_id);die("in allowed frq");
    	
    return $allowed_freq;
   }
	//function for description of plugins
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
	
	//function for get plugin data
	 function getPluginName()
	 {
		//FIRST GET THE EMAIL-ALERTS RELATED PLUGINS FRM THE `jos_plugins` TABLE
		if(JVERSION < '1.6.0')
			$this->_db->setQuery('SELECT element FROM #__plugins WHERE folder = \'emailalerts\'  AND published = 1');
		else
			$this->_db->setQuery('SELECT element FROM #__extensions WHERE folder = \'emailalerts\'  AND enabled = 1');
			$email_alert_plugins_array = $this->_db->loadResultArray();//Get the plugin names and store in an array
		    
		return  $email_alert_plugins_array;
	}//getPluginTags() ends	

}
