<?php
/**
 * manageuser View for manageuser  World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * User and Alerts View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class jmailalertsViewmanageUser extends JView
{
	/**
	 * manageuser view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		//GET THE CSS
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/mail_alert_general.css'); 
		
		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
		JToolBarHelper::title(   JText::_( 'MANAGEUSER' ), 'manageuser' );
		
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::deleteList();
		
		
		// Get data from the model
		$alert_nm		= & $this->get( 'alert_nm');
		$this->assignRef('alert_nm',$alert_nm);
		$state		= & $this->get( 'Userstate');
		$this->assignRef('state',$state );
		
		$user_data		= & $this->get( 'Data');
		$this->assignRef('user_data',$user_data);
		$pagination =& $this->get('pagination');
		$search = $this->get('search');
		$this->assignRef('pagination', $pagination); //print_r($pagination);die("in view manage user");
		$this->assign('search', $search);
	//die("in view");	
		parent::display($tpl);
	}
	
	/**
	 * Vishal
	 * Method to get the publish status HTML
	 *
	 * @param	object	Field object
	 * @param	string	Type of the field
	 * @param	string	The ajax task that it should call
	 * @return	string	HTML source
	 **/
	public function getPublish( &$row , $type , $ajaxTask )
	{
  
		$imgY	= 'tick.png';
		$imgX	= 'publish_x.png';

		$image	= $row->$type ? $imgX : $imgY;

		$alt	= $row->$type ? JText::_('COM_COMMUNITY_PUBLISHED') : JText::_('COM_COMMUNITY_UNPUBLISH');

		$href = '<a class="jgrid" href="javascript:void(0);" onclick="azcommunity.togglePublish(\'' . $ajaxTask . '\',\'' . $row->id . '\',\'' . $type . '\');">';

		if(C_JOOMLA_15==0)
		{
			$state = $row->$type ? 'unpublish' : 'publish';
			$href .= '<span class="state '.$state.'"><span class="text">'.$alt.'</span></span></a>';
		}
		else
		{
			$href  .= '<span><img src="images/' . $image . '" border="0" alt="' . $alt . '" /></span></a>';
		}

		return $href;
	}//code at feb14-11:00
	
	
}
