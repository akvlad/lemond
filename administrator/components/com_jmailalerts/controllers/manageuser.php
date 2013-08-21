<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
defined('_JEXEC') or die('Restricted access');
require_once( JPATH_COMPONENT.DS.'views'.DS.'config'.DS.'view.html.php' );

jimport('joomla.application.component.controller');

class jmailalertsControllermanageuser extends jmailalertsController
{
	/**
	 * Calls the model method to return email address
	 */
	
	 function priview()
	 {
		
	    $model =& $this->getModel( 'manageuser' );
	    $model->priview();
	 }
	 
	  function togglestate()
	 {
		
		$view = JRequest::getCmd( 'view', 'manageuser' );
		// Get some variables from the request
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
       //print_r($cid); die("in control of manage user");
        $model =& $this->getModel( 'manageuser' );
		
		if(!$cid){  //die("asdfa");  
					$model->subAlertstate(0,$pb = 2); }
		
		if ($model->subAlertstate($cid,$pb = 2))
		{
			$msg = JText::sprintf( 'Alert Subscribed', count( $cid ) );  
		}
		else
		{
				$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_jmailalerts&view=manageuser&layout=manageuser');
	
	 }
	 
	 //function for delete data
	 function remove()
	 {
		//die("in remove of control of manage user");
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$total = count( $cid );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('manageuser');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$msg = $total.' '.JText::_( 'ALERT_RECORD_DELETED');

		$cache = &JFactory::getCache('com_jmailalerts');
		$cache->clean();

		$this->setRedirect( 'index.php?option=com_jmailalerts&view=manageuser', $msg );
		 
		 
	 }
	 function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}
	function save()
	{
		$model = $this->getModel('subscribe');
		$store = $model->store();
		
		//print_r($store);die("in save() manageuser ");
		
		if ($store==1) 
		{
			$msg = JText::_( 'New Subscribe Saved' );
			$link = 'index.php?option=com_jmailalerts&view=manageuser';
			$this->setRedirect($link, $msg);
			
		} else
		{   
			$msg = $store;
			$link = 'index.php?option=com_jmailalerts&view=manageuser';
			$this->setRedirect($link,$msg);
		}

		// Check the table in so it can be edited.... we are done with it anyway
	
		//$link = 'index.php?option=com_jmailalerts&view=manageuser';
		//$this->setRedirect($link, $msg);
	}
	
	//function for cancel new alert subscription
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_jmailalerts&view=manageuser', $msg );
	}
	
	//function for edit alert subscription
	function edit()
	{
		JRequest::setVar( 'view', 'subscribe' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	} 
	 
	 function display()
	{

		$view = JRequest::getVar('manageuser');
        //print_r($view);die("in the disply of manageuseraa");				
		if (!$view) 
		{
			JRequest::setVar('view', 'manageuser');
		}

		parent::display();
	}
	//die("inn control of manageuser");

}?>
