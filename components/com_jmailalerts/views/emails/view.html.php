<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
//This is the html view file for the preferences view in the frontend

jimport('joomla.application.component.view');
jimport('joomla.form.form');
class jmailalertsViewEmails extends JView{

	function display($tpl = null)
	{
		$menu		= & JSite::getMenu();
		$item    	= $menu->getActive();
		$this->assignRef( 'page_title', $item->name);
		
		
		$model = $this->getModel();
		
		//get no of count alert
		$cntalert = $model->countalert();
		$this->assignRef('cntalert' , $cntalert);
		
		if (trim($cntalert) != 0) 
		{
		//creating query for concat from enable plugin for compair to user selected alert 
		$qry_concat = $model->alertqryconcat();
		
		$this->assignRef('qry_concat' , $qry_concat);
		
		//  get the default alert user selected alerts or default alerts
		$defaultoption = $model->defaultalertid();
		$this->assignRef('defaultoption' , $defaultoption);

		// checking user default alert id or not
		$default_setting = $model->isdefaultset();
		$this->assignRef('default_setting' , $default_setting);
		
		//getting all alert created alert ids
		$altid = $model->alertid();
		$this->assignRef('altid' , $altid);
		}
		

		parent::display($tpl);
	}//display() ends
}//class mailalertViewEmails ends
