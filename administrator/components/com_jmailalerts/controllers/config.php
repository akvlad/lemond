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

class jmailalertsControllerConfig extends jmailalertsController
{
	/**
	 * Saves a menu item
	 */

	function save()
	{ 
		// Check for request forgeries
		//print_r($_POST);die;
    JRequest::checkToken() or jexit( 'Invalid Token' );

		$cache = & JFactory::getCache('com_jmailalerts');
		$cache->clean();

		$model	=& $this->getModel( 'config' );

		$post	= JRequest::get('post');
		
		$post['name'] = JRequest::getVar( 'name', '', 'post', 'string', JREQUEST_ALLOWHTML );
		$model->setState( 'request', $post );

		if ($model->store()) {
			$msg = JText::_( 'MENU_ITEM_SAVED' );
		} else {
			$msg = JText::_( 'ERROR_SAVING_MENU_ITEM' );
		}

		$item =& $model->getItem();
		
switch ( $this->_task ) {
			case 'apply':
				$this->setRedirect( 'index.php?option=com_menus&menutype='.$item->menutype.'&task=edit&cid[]='.$item->id.'' , $msg );
				break;

			case 'save':
			default:
				$this->setRedirect( 'index.php?option=com_menus&task=view&menutype='.$item->menutype, $msg );
				break;
		}
	}

}?>
