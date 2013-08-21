<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/invitex.css');

require_once(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");
?>


	<table border="0" width="100%" class="adminlist">
	   <thead>
	     <tr>
		<th class='title'> <?php echo JText::_('ID');?></th>
		<th class='title'> <?php echo JText::_('NAME');?></th>
		<th class='title'> <?php echo JText::_('EMAIL_ADD');?></th>
		<th class='title'> <?php echo JText::_('DATE');?></th>
		<th class='title'> <?php echo JText::_('PLUGINS');?></th>
	     </tr>
	<thead>
	<tbody>	
<?php	foreach($this->mail_queue_array as $mail_queue_element){ 
		/*stdClass Object--->sample $mail_queue_element looks like this
		(
		    [id] => 62
		    [name] => Administrator
		    [email] => test@test.com
		    [date] => 2010-02-16 21:27:40
		    [plugins_subscribed_to] => jsgroups,jsdocs,jsntwrk
		)*/

?>
		<tr>
			<td align="center"><?php echo $mail_queue_element->id; ?></td>
			<td align="center"><?php echo $mail_queue_element->name;?></td>
			<td align="center"><?php echo $mail_queue_element->email;?></td>
			<td align="center"><?php echo $mail_queue_element->date;?></td>
			<td align="center"><?php echo $mail_queue_element->plugins_subscribed_to;?></td>
		</tr>			
		
<?php }?>
	</tbody>

	<tfoot>	
	</tfoot>
	
	</table>
	


