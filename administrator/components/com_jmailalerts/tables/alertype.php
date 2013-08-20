<?php
/**
 * Hello World table class
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Hello Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableAlertype extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	var $alert_name = null;
	

	/**
	 * @var string
	 */
	var $description = null;
	var $allow_user_select_plugin = null;
	var $allowed_freq = null;
	var $template_css = null;
	//var $plugin = null;
	var $template = null;
	var $default_freq = null;
	var $email_subject = null;
	var $respect_last_email_date = null;
	

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableAlertype(& $db) {
		parent::__construct('#__email_alert_type', 'id', $db);
	}
}
