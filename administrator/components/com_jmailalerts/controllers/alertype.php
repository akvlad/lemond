<?php
/**
 * Alertype Controller for Alertype World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Alertype Alertype Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class jmailalertsControlleralertype extends jmailalertsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'alertype' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
	/*function unpublish()
	{
	   
	}*/

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('alertype');

		if ($model->store($post)) {
			$msg = JText::_( 'Alert Type Saved' );
		} else {
			$msg = JText::_( 'Error Saving Alert Type' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
	
		$link = 'index.php?option=com_jmailalerts&view=alertypes';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('alertype');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Alert Type(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_jmailalerts&view=alertypes', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_jmailalerts&view=alertypes', $msg );
	}
	
	function display()
	{
		
		$view = JRequest::getVar('view');
		
		if (!$view) {
			JRequest::setVar('view', 'alertypes');
		}

		parent::display();
	}
	
	/**
	 * set/unset the alert state to default 
	 * @return change icon image
	 * */
	function toggledefault()
	{
		
		$view = JRequest::getCmd( 'view', 'default' );

		// Get some variables from the request
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		
	    $model =& $this->getModel( 'alertypes' );
		if ($model->setAlertdefault($cid))
		{
			$msg = JText::sprintf( 'Alert Enabled', count( $cid ) );  
		}
		else
		{
				$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_jmailalerts&view=alertypes&layout=default', $msg);
	
	}
	
	
	
	/*function publish()
	{
	    $view = JRequest::getCmd( 'view', 'default' );

		// Get some variables from the request
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
	    $model =& $this->getModel( 'alertypes' );
		if ($model->setAlertdefault($cid, 1))
		{
			$msg = JText::sprintf( 'Alert Enabled', count( $cid ) );  
		}
		else
		{
				$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_jmailalerts&view=alertypes&layout=default', $msg);
	   
	   
	}
	
	function unpublish()
	{
		 $view = JRequest::getCmd( 'view', 'default' );

		// Get some variables from the request
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
	    $model =& $this->getModel( 'alertypes' );
		if ($model->setAlertdefault($cid, 0))
		{
			$msg = JText::sprintf( 'Alert Enabled', count( $cid ) );  
		}
		else
		{
				$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_jmailalerts&view=alertypes&layout=default', $msg);
		
		
		
	}*/
}
