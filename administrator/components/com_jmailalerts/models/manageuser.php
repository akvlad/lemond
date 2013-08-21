
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

class jmailalertsModelManageuser extends JModel
{
    
    /**
	* Items total
	* @var integer
	*/
	var $_total = null;
	/**
	* Pagination object
	* @var object
	*/
	var $_pagination = null;
	var $_data = null;
    
    var $_search = null;
	var $_query = null;
	
	
	function __construct()
	{
		parent::__construct();

		$mainframe 	=& JFactory::getApplication();
		
		//get the number of events from database
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		
		$limitstart		= JRequest::getVar('limitstart', 0, '', 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);	
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		
		
	}
	
       
 	function manageuser()
 	{ 
		jimport('joomla.filesystem.file');
		require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");	
		//require_once(JPATH_SITE . DS .'components'. DS .'com_jmailalerts'. DS .'models'. DS .'emails.php');		
       
        $db = JFactory::getDBO();
        $data = array(); 
        
        //$data['installed'] = $this->getdata($db);
        return $this->getdata();
	}
    /**
	 * get data from database  
	 * @return array array containing data from database
	 * */
	function getData()
	{
			
		$db = JFactory::getDBO();
		$pagination =& $this->getPagination();
		if (empty($this->_data))
		{ 
 	    $query = $this->_buildQuery();
 	    $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));	
 	    
 	    }
 	    
 	    //for get username
 	    foreach($this->_data as $data)
 	    { 
			
 	   
 	    //echo $j; print_r($data);
 	    for($i=0;$i<count($data);$i++)
 	    {
			
			
		    $user = $data->user_id;
		    $db->setQuery("SELECT name,username FROM #__users WHERE id = ".$user);                     
		
		    $data_db = $db->loadObjectList(); //print_r($data_db[$i]->name);die("in model get data of manage user");
		    
		    $data->name = $data_db[$i]->name;
		    $data->username = $data_db[$i]->username;
		
		
			$alert_id = $data->alert_id;
			
		    $db->setQuery("SELECT id,alert_name FROM #__email_alert_type WHERE id = ".$alert_id);                     
		
		    $data_db = $db->loadObjectList(); 
		    $data->alert_name = $data_db[$i]->alert_name;
		   
		}		
		
	   }
 	    
 	    return $this->_data; 
 	    
 	    
 	
	}
	
	//function for subscribe /unsubscribe user
    function subAlertstate($cid,$pb = 0)
    { 
	   //select alert string from default table
		  $query="SELECT COUNT(al.id) FROM #__email_alert AS al ";
		  $this->_db->setQuery($query);
	      $records = $this->_db->loadResult(); 
	      
	      $this->_db->setQuery('SELECT al.id FROM #__email_alert AS al WHERE al.option = 0 ');
	      $unsub = $this->_db->loadResultArray();
	
		 for($i=0;$i<COUNT($cid);$i++)
         {  
		    
			if(in_array($cid[$i],$unsub) and $pb == 1)
            {
				$this->_db->setQuery("UPDATE #__email_alert AS e SET e.option = 1 WHERE  e.id = ".$cid[$i]." AND e.option = 0 ");
				$default = $this->_db->loadResult();
				
			}
			elseif($pb == 0)
			{
				
				$this->_db->setQuery("UPDATE #__email_alert AS e SET e.option = 0 WHERE  e.id = ".$cid[$i]." ");
				$default = $this->_db->loadResult();
			}
			
			if($pb == 2)
			{
			 
			  $this->_db->setQuery((in_array($cid[$i],$unsub))?"UPDATE #__email_alert AS e SET e.option = 1 WHERE  e.id = ".$cid[$i]." " 
			                                                   :"UPDATE #__email_alert AS e SET e.option = 0 WHERE  e.id = ".$cid[$i]." "); 
			  $default = $this->_db->loadResultArray(); 
            	
				
			}
            
	    }
    }
    
    //function for preview alert of user
    function priview()
    {
	
	    jimport('joomla.filesystem.file');
		require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");	
		require_once(JPATH_SITE . DS .'components'. DS .'com_jmailalerts'. DS .'models'. DS .'emails.php');		

		$nofrm=0; 
		//$today=date('Y-m-d H:i:s');  previous code
		
		//get date selected in simulate
		$today=JRequest::getString('select_date_box');  
		$email_status=0;
		$target_user_id=JRequest::getInt('user_id'); 
		$alert_type_id=JRequest::getInt('alert_id');
		
		$destination_email_address=JRequest::getVar('send_mail_to_box');
		$flag=JRequest::getVar('flag'); //print_r($alert_type_id);die("in priview of model");
		$query="SELECT  u.id,u.name,u.email,a.template, a.email_subject, e.date, e.alert_id ,a.template_css, e.plugins_subscribed_to" 
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
			$query ="SELECT template FROM #__email_alert_type WHERE id =$alert_type_id ";
			$this->_db->setQuery($query);
			$msg_body= $this->_db->loadResult();  //print_r($msg_body);die("in the model on manage user");
			$skip_tags=array('[SITENAME]','[NAME]','[SITELINK]','[PREFRENCES]', '[mailuser]');
			$tmpl_tags=jmailalertsModelEmails::get_tmpl_tags($msg_body,$skip_tags);
			$remember_tags=jmailalertsModelEmails::get_original_tmpl_tags($msg_body,$skip_tags); 		

			return jmailalertsModelEmails::getMailcontent($target_user_data[0],$flag,$tmpl_tags,$remember_tags);
				
	}	
}
//function for set flag
function getalert_nm()
{
	    $mainframe			=& JFactory::getApplication();
		$this->_db->setQuery("SELECT alert_name,id FROM #__email_alert_type ");
		$resultstate= $this->_db->loadResultArray();

		
		$alert_nm 		= $mainframe->getUserStateFromRequest( 'com_jmailalerts.manageuser.alert_nm', 'alert_nm', '', 'word' );
		
		$options = array();
		$options[] = JHTML::_('select.option', $resultstate, JText::_( 'SEL_ALT' ).' -');
		
		foreach($resultstate AS $key=>$val )
		{
		  $key++;
		  $options[]= JHTML::_('select.option',$val,$val);		
	  }
		
     	return JHTML::_('select.genericlist',   $options, 'alert_nm', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $alert_nm );
}
function getUserstate()
{
	    $mainframe			=& JFactory::getApplication();
		$state 		= $mainframe->getUserStateFromRequest( 'com_jmailalerts.manageuser.state', 'state', '', 'int' );	
		$options = array();
		$options[] = JHTML::_('select.option', '', JText::_( 'SEL_ST' ).' -');
		$options[] = JHTML::_('select.option', '1', JText::_( 'SUB' ));	
		$options[] = JHTML::_('select.option', '2', JText::_( 'UNSUB' ));	
		
  	    return  JHTML::_('select.genericlist', $options, 'state', 'class="inputbox" size="1" onchange="document.adminForm.submit( )"' , 'value', 'text', $state);
	
}

function _buildQuery()
	{
	 
		if(!$this->_query) 
		{
			
			$where		= $this->_buildQueryWhere();	
			$db			=& $this->getDBO();	
		    $this->_query = "SELECT * FROM #__email_alert AS e "; 
		    $this->_query	.= $where;
        }         
                 
		     return $this->_query;
		
	}
	
	//function for where clouse
	function _buildQueryWhere()
	{
		$mainframe			=& JFactory::getApplication();
		$db					=& $this->getDBO();
		
		$alert_nm 	= $mainframe->getUserStateFromRequest( 'com_jmailalerts.manageuser.alert_nm', 'alert_nm', '', 'string' );
		$state 		= $mainframe->getUserStateFromRequest( 'com_jmailalerts.manageuser.state', 'state', '', 'int' );	
	
		$search 			= $mainframe->getUserStateFromRequest( 'com_jmailalerts.manageuser.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );
		
		$where = array();
	
		if ( $state )
		{ 
			if ( $state == '1' )
			{
				$where[] = 'e.option <> 0';
			}
			else if ($state == '2' )
			{
				$where[] = 'e.option = 0';
			}
			
		}
		
		if($search !='' )
		{
			$where[] = "e.user_id IN ("."SELECT u.id FROM #__users as u WHERE u.username LIKE '%{$search}%' OR u.name LIKE '%{$search}%'".") ";
		}
		
		if($alert_nm != 'Array')
		{
		    $where[] = " e.alert_id IN ("."SELECT t.id FROM #__email_alert_type AS t WHERE t.alert_name LIKE '%{$alert_nm}%' ".") ";
		}

		//$where[] 	= ' `ispending` = ' . $db->Quote('0');
			$where 		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' ;

		return $where;
	}
	
	/**
	 * Method to return the total number of rows
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		if (!$this->_total)
	    {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
			
		}
		return $this->_total;
	}
	/**
	 * Method to get a pagination object for the events
	 *
	 * @access public
	 * @return integer
	 */
	function &getPagination()
	 {
		if(!$this->_pagination)
		 {
			jimport('joomla.html.pagination');
			global $mainframe;
			$mainframe = JFactory::getApplication();
			$this->_pagination = new JPagination($this->getTotal(), JRequest::getVar('limitstart', 0), JRequest::getVar('limit', $mainframe->getCfg('list_limit')));
		
	     }

		return $this->_pagination;
	}	
//function for search filter
function getSearch()
	{
	
		if (!$this->_search)
		 {
			global $mainframe, $option;
			$mainframe = JFactory::getApplication();
			$option = JRequest::getCmd('option');
			$search = $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
			$this->_search = JString::strtolower($search);
		}

		return $this->_search;
	}
	
//function for delete record
function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$result = false;

		for($i=0;$i<count( $cids );$i++)
		{ 
			$this->_db->setQuery( "DELETE FROM #__email_alert WHERE id = ".$cids[$i] );

			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	

	
}//class mailalertModelManageuser ends
?>
