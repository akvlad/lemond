<?php
/**
 * Alertypes Model for Alertype World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * Alertype Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class jmailalertsModelAlertypes extends JModel
{
	/**
	 * Alertypes data array
	 *
	 * @var array
	 */
	var $_data;
	var $_pagination = null;
	var $_total = null;
	var $_search = null;
	var $_query = null;
   
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
		
		
		if (!$this->_query) {
			$search = $this->getSearch();

		    $this->_query = "SELECT * FROM #__email_alert_type"; 
		    
			if ($search != '') {
				$fields = array('alert_name', 'description');

				$where = array();

				$search = $this->_db->getEscaped( $search, true );

				foreach ($fields as $field) {
					$where[] = $field . " LIKE '%{$search}%'";
				}

				$this->_query .= ' WHERE ' . implode(' OR ', $where);
			}
				
                       //  $this->_query .= " GROUP BY listingid";      
		}
		
		
		return $this->_query;
	}

	/**
	 * Retrieves the names of plugins  
	 * 
	 * @params $tmplate for particular alert
	 * @return array Array of objects containing the data from the database
	 */
	 
	 function getPlugnames($tmplate)
	 {
	    if(JVERSION < '1.6.0')
     	$this->_db->setQuery('SELECT name, element,params FROM #__plugins WHERE folder=\'emailalerts\' AND published = 1');
     	else
     	$this->_db->setQuery('SELECT name, element,params FROM #__extensions WHERE folder=\'emailalerts\' AND enabled = 1');
	 
	    $plugcompair= $this->_db->loadObjectList();//return the plugin data array
	   
	    foreach($plugcompair as $plg)
	    {
	    	if(strstr($tmplate,$plg->element))
	    	{
	    	 $plugname[]=$plg->element;
	    	}
	    }
	    if(isset($plugname[0])){
	    	$plugname = implode(', ',$plugname);
	    	return $plugname;
	    }else{
	    	return JText::_('NO_PLUGINS_ENABLED_OR_INSTALLED');
	    }
	    
	 }
	 
	 /**
	 * Retrieves the data for alertypes different column 
	 * @return array Array of objects containing the data from the database
	 */
	 
	function getData()
	{
		
		$pagination =& $this->getPagination();
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query , $pagination->limitstart, $pagination->limit );
			
		}
      		
		for($i=0;$i<count($this->_data);$i++)
		 {
            
            //no. of subscribe user
             $user = $this->subscribe();
             $this->_data[$i]->subscribe = $user[$i];
           
            //no. of subscribe user
             $user = $this->unsubscribe();
             $this->_data[$i]->unsubscribe = $user[$i];
             
             //no. of subscribe user
             $user = $this->notopted();
             $this->_data[$i]->notopted = $user[$i];
             
		  }   
              //$this->_data[$id]->id;  $value = $value * 2;*/
        
              //print_r($this->_data[3]->isdefault);die;
        
			return $this->_data;
	}
	
	function getTotal()
	{
		if (!$this->_total) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}
	
	function &getPagination() {
		if(!$this->_pagination) {
			jimport('joomla.html.pagination');
			global $mainframe;
			$mainframe = JFactory::getApplication();
			$this->_pagination = new JPagination($this->getTotal(), JRequest::getVar('limitstart', 0), JRequest::getVar('limit', $mainframe->getCfg('list_limit')));
		}

		return $this->_pagination;
	}
	
	function getSearch()
	{
	
		if (!$this->_search)
		 {
			global $mainframe, $option;
			$mainframe = JFactory::getApplication();
			$option = JRequest::getCmd('option');
			$search = $mainframe->getUserStateFromRequest( "$option.search", 'search', '', 'string' );
			$this->_search = JString::strtolower($search);
		}

		return $this->_search;
	}
	
	/**
	 * set the state of alert to published  in the email_alert_type table
	 * @params $cid is id eq-2,3 in array  
	 * @return zero 
	 * */
	 
	function setAlertState($cid, $pb)
	{
		  //select alert string from default table
		  $query="SELECT et.id FROM #__email_alert_type AS et WHERE et.isdefault = 1 ";
		  $this->_db->setQuery($query);
	      $alert = $this->_db->loadResultArray(); 
    	  
		
		for($i=0;$i<COUNT($cid);$i++)
         {  
		
		  if($pb)
		   { 
				$query="UPDATE #__email_alert_type AS e SET e.published = 1 WHERE  e.id = ".$cid[$i]." AND e.published = 0  ";
                    
				$this->_db->setQuery($query);
				$default = $this->_db->loadResultArray();  
			
		   }
	       else
		   {
				$query="UPDATE #__email_alert_type AS e SET e.published = 0 WHERE e.id = ".$cid[$i]." AND e.published = 1   ";
                    
				$this->_db->setQuery($query);
				$default = $this->_db->loadResultArray(); 
         
           }
			
			
	   }
		
	 }
	 
	 /**
	 * set the state of alert for is default in the email_alert_type table
	 * @params $cid is id eq-2,3 in array  
	 * @return zero 
	 * */
     function setAlertdefault($cid)
	 {
		 
	   //select alert id from email_alert_type table
		$query="SELECT et.id FROM #__email_alert_type AS et WHERE et.isdefault = 1 ";
		$this->_db->setQuery($query);
	    $alert = $this->_db->loadResultArray(); 
    			
		for($i=0;$i<COUNT($cid);$i++)
         {  
			 $query="SELECT et.isdefault FROM #__email_alert_type AS et WHERE et.id = ".$cid[$i]." ";
			 $this->_db->setQuery($query);
			 $state = $this->_db->loadResultArray();
		
		   if(!$state[0])
		   { 
			  							
			  /*if(!$alert[0])
              { 
				$alert[0] = $cid[$i];
				array_multisort($alert); 
				$alert = implode(",",$alert ); 
				
			    $query="INSERT INTO #__email_alert_Default (id,alert_id) VALUES('0','$alert') "; 
               
				$this->_db->setQuery($query);
				$default = $this->_db->loadResultArray(); 
			
			  }	
    	      elseif(!in_array($cid[$i],$alert))   
			  {
				array_push($alert,$cid[$i]);
				array_multisort($alert);  
				$alert = implode(",",$alert );  
				
				$query="UPDATE #__email_alert_Default AS el SET el.alert_id = '$alert' ";
             
				$this->_db->setQuery($query);
				$default = $this->_db->loadResultArray();     
			
			   }*/
			   
			   //set is default to 1
				$query="UPDATE #__email_alert_type AS e SET e.isdefault = 1 WHERE  e.id = ".$cid[$i]." AND e.isdefault = 0 ";
                    
				$this->_db->setQuery($query);
				$default = $this->_db->loadResultArray();  
			
		   }
	    
	    else
	    {	
			//update default string
          /* if(in_array($cid[$i],$alert))   
			{  				     
				unset($alert[array_search($cid[$i],$alert)]);
				$alert = implode(",",$alert );
		  
				$query="UPDATE #__email_alert_Default AS el SET el.alert_id = '$alert'  ";
             
				$this->_db->setQuery($query);
				$default = $this->_db->loadResultArray(); 
			    if(empty($alert[0]))
                {
				  $query="DELETE FROM #__email_alert_Default WHERE id=0 ";
              
				  $this->_db->setQuery($query);
				  $default = $this->_db->loadResultArray();
				
		    	}
    	    }*/
    	    
			//set defoult in 
			$query="UPDATE #__email_alert_type AS e SET e.isdefault = 0 WHERE  e.id = ".$cid[$i]." AND e.isdefault = 1  ";
                   
			$this->_db->setQuery($query);
			$default = $this->_db->loadResultArray();      
     	   
     	}
			
			
	   }
	   
		 
		 
	 }
	 
	 /**
	 * count the user in joomla
	 * @return zero 
	 * @return array Array of objects containing the data of number of user
	 * */
	 function user_count()
	 {
		 $data = array();
		  
		$query="SELECT u.id FROM #__users AS u WHERE u.block = 0 ";
		$this->_db->setQuery($query);
		$users = $this->_db->loadResultArray(); 
		$t_user = array(COUNT($users));
     
      	//total alert
      	$query="SELECT id FROM #__email_alert_type ";
    	$this->_db->setQuery($query);
    	$alert = $this->_db->loadResultArray(); 
    	 
    	$data['t_user']=$t_user[0];
    	$data['alert']=$alert;   	
    	$data['users']= $users;   	
    	return($data); 
		 
		 
	 }
	 
	 
	 /**
	 * count the subscrided user for particular alert 
	 * @return array Array of objects containing the data of number of user
	 * */
		
	function subscribe()
	{
      	
      	$data = $this->user_count();
      	
      	$val = array();
		$val_str = array();
		
		
		$alert = $data['alert'];
		
      	//count subscribed user for alert      	
      	for($i=0;$i<count($alert);$i++)
		{
			$query="SELECT COUNT(DISTINCT e.user_id) FROM #__email_alert AS e WHERE e.alert_id =".$alert[$i]." AND e.option <> 0 ";
			$this->_db->setQuery($query);
			$alt_user = $this->_db->loadResultArray(); 	
			
			$val[0]=$alt_user[0];
			$val[1]=$data['t_user'];
			$val_str[$i]=implode("/", $val);
			
		}
		return $val_str;	
	
	}
	
	/**
	 * count the unsubscrided user for particular alert 
	 * @return array Array of objects containing the data of number of user
	 * */
	function unsubscribe()
	{
		
		$data = $this->user_count();
      	$alert = $data['alert'];
      	
      	$val = array();
		$val_str = array();
		
		
		for($i=0;$i<count($alert);$i++)
		{  
			$query="SELECT COUNT(DISTINCT e.user_id) FROM #__email_alert AS e WHERE e.alert_id = ".$alert[$i]." AND e.option = 0";
			$this->_db->setQuery($query);
			$alt_user = $this->_db->loadResultArray(); 	
			
			$val[0]=$alt_user[0];
			$val[1]=$data['t_user'];
			$val_str[$i]=implode("/", $val);
			
		}
		
		return $val_str;	
	}
	
	/**
	 * count the user for particular alert niether subscribed nor unsubscribed
	 * @return array Array of objects containing the data of number of user
	 * */
	
	function notopted()
	{
		
		$data = $this->user_count();
      	$alert = $data['alert'];
      	
      	$val = array();
		$val_str = array();
		$i=0;
		
		for(;$i<count($alert);$i++)
		{    
			$query="SELECT DISTINCT e.user_id FROM #__email_alert AS e WHERE e.alert_id = ".$alert[$i]." ";
			$this->_db->setQuery($query);
			$alt_user = $this->_db->loadResultArray(); 	
		
		    $notopt = array_diff($data['users'],$alt_user); 
		    
		    $notopt[0] = (COUNT($notopt)) ? COUNT($notopt):$notopt[0]=0 ; 
		    
		  	$val[0]=$notopt[0];
			$val[1]=$data['t_user'];
			$val_str[$i]=implode("/", $val);
			
		}
		
		return $val_str;	
	}
	
	
	
}
