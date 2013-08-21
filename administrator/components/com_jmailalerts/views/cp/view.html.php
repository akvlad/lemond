<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class jmailalertsViewcp extends JView
{
	function display($tpl = null)
	{
		$this->_setToolBar();
		parent::display($tpl);
	}
	function _setToolBar()
	{
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/mail_alert_general.css'); 
		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
    if(JVERSION < '1.6.0')
		JToolBarHelper::title( JText::_( 'MENU_TITLE15' ), 'cp' );
    else
    JToolBarHelper::title( JText::_( 'MENU_TITLE16' ), 'cp' );
	}
}

