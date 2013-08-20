<?php
/*
 * @package J!MailALerts
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */
defined('_JEXEC') or die('Restricted access');
require_once( JPATH_COMPONENT.DS.'views'.DS.'sync'.DS.'view.html.php' );
jimport('joomla.application.component.controller');

class jmailalertsControllerSync extends jmailalertsController
{
    /*
     * 
     * 
     */
    function save()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $cache=&JFactory::getCache('com_jmailalerts');
        $cache->clean();
        $post=JRequest::get('post');
        $model=&$this->getModel('sync');

        if ($model->store()){
            $msg = JText::_('MENU_ITEM_SAVED' );
        } else {
            $msg = JText::_('ERROR_SAVING_MENU_ITEM');
        }
    }
}
?>