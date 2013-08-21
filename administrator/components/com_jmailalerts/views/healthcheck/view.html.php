<?php
/**
 * Alertypes View for Alertype World Component
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
 * healthcheck View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class jmailalertsViewHealthcheck extends JView
{
	/**
	 * healthcheck view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		//GET THE CSS
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/mail_alert_general.css'); 
		
		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
		//JToolBarHelper::title(JText::_('MAILSIMULATE'), 'mailsimulate');
		JToolBarHelper::title(   JText::_( 'HEALTHCHECK' ), 'healthcheck' );
		
	
	
		//Get the model
		$model = $this->getModel();
		//Get the plugin names under email-alerts
		$data = $model->healthcheck();
		//Assign a ref	to the array
		$this->assignRef('data', $data);		
		
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
	/*public function getPublish( &$row , $type , $ajaxTask )
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
	}//code at feb14-11:00*/
	
	

}
