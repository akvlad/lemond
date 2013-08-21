<?php 
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/ 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class jmailalertsController extends JController
{	 
	function display()
	{ 		
    	$mainframe=JFactory::getApplication();

		$vName = JRequest::getCmd('view', 'cp');

		$controllerName = JRequest::getCmd( 'controller', 'cp' );
		$cp		=	'';
		$statistics	=	'';
		$user		=	'';
		$config		=	'';
		
		$sync		=	'';
		$mailsimulate	=	'';
		$alertypes	=	'';
		$alertype	=	'';
		$healthcheck=   '';
		$manageuser =   '';
		$subscribe  =   '';
		$mName		=	''; 
		$vLayout	=	'';
		switch($vName)
		{  
			case 'cp':
				$cp	=	true;
			break;
			case 'config':
				$config	=	true;
			break;
			
			case 'sync':
				$sync = true;
			break;	
			case 'mailsimulate':
				$mailsimulate = true;
			break; 
				
			case 'alertypes':
				$alertypes = true;
			break;		
			case 'alertype':
				$alertype = true;	
			break;
			case 'healthcheck':
				$healthcheck = true;	
			break;
			case 'manageuser':
				$manageuser = true;	
			break;	
			case 'subscribe':
				$subscribe = true;	
			break;							
		}
		JSubMenuHelper::addEntry(JText::_('CP'), 'index.php?option=com_jmailalerts',$cp);
		JSubMenuHelper::addEntry(JText::_('CONFIG'), 'index.php?option=com_jmailalerts&view=config', $config);
		JSubMenuHelper::addEntry(JText::_('ALERTYPES'), 'index.php?option=com_jmailalerts&view=alertypes', $alertypes );
		JSubMenuHelper::addEntry(JText::_('SYNC'), 'index.php?option=com_jmailalerts&view=sync', $sync );
		JSubMenuHelper::addEntry(JText::_('MAILSIMULATE'), 'index.php?option=com_jmailalerts&view=mailsimulate', $mailsimulate );
		//JSubMenuHelper::addEntry(JText::_('ALERTYPES'), 'index.php?option=com_jmailalerts&view=alertypes', $alertypes );
		JSubMenuHelper::addEntry(JText::_('MANAGEUSER'), 'index.php?option=com_jmailalerts&view=manageuser', $manageuser );
		JSubMenuHelper::addEntry(JText::_('HEALTHCHECK'), 'index.php?option=com_jmailalerts&view=healthcheck', $healthcheck );
		
		switch ($vName)
		{
			case 'config':
				$mName = 'config';
				$vLayout = JRequest::getCmd( 'layout', 'config' );
			break;
			
			case 'sync':
				$mName = 'sync';
				$vLayout = JRequest::getCmd( 'layout', 'sync' );
						
			break;
			case 'mailsimulate':
				$mName = 'mailsimulate';
				$vLayout = JRequest::getCmd( 'layout', 'mailsimulate' );
			break;
			case 'alertypes':
				$mName = 'alertypes';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
			break;	
			case 'alertype':
				$mName = 'alertype';
				$vLayout = JRequest::getCmd( 'layout', 'alertype' );
			break;	
			case 'healthcheck':
				$mName = 'healthcheck';
				$vLayout = JRequest::getCmd( 'layout', 'healthcheck' );
			break;
			case 'manageuser':
				$mName = 'manageuser';
				$vLayout = JRequest::getCmd( 'layout', 'manageuser' );
			break;
			case 'subscribe':
				$mName = 'subscribe';
				$vLayout = JRequest::getCmd( 'layout', 'subscribe' );
			break;
			case 'cp':
			default:
				//$vName = 'cp';
				$mName = 'cp';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
			break;
			
		}
		//$mName = 'cp';
		$document = &JFactory::getDocument();
		$vType		= $document->getType();

		// Get/Create the view
		$view = &$this->getView( $vName, $vType);

		// Get/Create the model
		if ($model = &$this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
	
		// Set the layout
	    $view->setLayout($vLayout);

		// Display the view
		$view->display();
		
		$data=JRequest::get( 'post' );	
	}
	
	/**
	 * this function used for set publish state to alert
	 * @return change icone image
	 * */
	function publish()
	{
		$view = JRequest::getCmd( 'view', 'default' );
		// Get some variables from the request
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
	

		if($view=="manageuser")
		{   
			$model =& $this->getModel( 'manageuser' );
			if ($model->subAlertstate($cid,$pb = 1 )) {
				$msg = JText::sprintf( 'Alert published and subscribed', count( $cid ) );  
			}
			else {
				$msg = $model->getError();
				}
			
			$this->setRedirect('index.php?option=com_jmailalerts&view=manageuser&layout=manageuser', $msg);
			
		}	
		
		JArrayHelper::toInteger($cid);
		
		if($view=="alertypes")
		{
			$model =& $this->getModel( 'alertypes' );
			if ($model->setAlertState($cid,1)) {
				$msg = JText::sprintf( 'Alert Enabled', count( $cid ) );  
			}
			else {
				$msg = $model->getError();
				}
			$this->setRedirect('index.php?option=com_jmailalerts&view=alertypes&layout=default', $msg);
		}
		/*else
		{
		$model =& $this->getModel( 'alertypes' );
		
			if ($model->setAlertState($cid, 1)) {
				$msg = JText::sprintf( 'Alert Published', count( $cid ) );
			} else {
				$msg = $model->getError();
			
			$this->setRedirect('index.php?option=com_jmailalerts&view=alertypes&layout=default', $msg);
		}
			
		}*/
		
	}
	
	 /**
	 * this function used for set unpublish state to alert
	 * @return change icon image
	 * */
	function unpublish()
	{
		
		$view = JRequest::getCmd( 'view', 'default' );

		// Get some variables from the request
			
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		
		//for the manage user
		if($view=="manageuser")
		{
			$model =& $this->getModel( 'manageuser' );
			if ($model->subAlertstate($cid,$pb = 0)) {
				$msg = JText::sprintf( 'Alert published and subscribed', count( $cid ) );  
			}
			else {
				$msg = $model->getError();
				}
			
			$this->setRedirect('index.php?option=com_jmailalerts&view=manageuser&layout=manageuser', $msg);
			
		}	
		
		
		//for the alertypes
		if($view=="alertypes")
		{
			$model =& $this->getModel( 'alertypes' );
			if ($model->setAlertState($cid, 0)) {
				$msg = JText::sprintf( 'Alert  Disabled', count( $cid ) );
			} else 
			{
				$msg = $model->getError();
			
			
			}
				$this->setRedirect('index.php?option=com_jmailalerts&view=alertypes&layout=default', $msg);
		}
		/*else
		{
		$model =& $this->getModel( 'alertypes' );
		
			if ($model->setAlertState($cid, 0)) {
				$msg = JText::sprintf( 'Alert UnPublished', count( $cid ) );
			} else 
			{
				$msg = $model->getError();
			
			}
			$this->setRedirect('index.php?option=com_jmailalerts&view=alertypes&layout=default', $msg);
		}*/
		
	}
	
	

	function getVersion()
	{	
		echo $recdata = file_get_contents('http://techjoomla.com/vc/index.php?key=abcd1234&product=jmailalerts');
		jexit();
	}
	
}
?>
