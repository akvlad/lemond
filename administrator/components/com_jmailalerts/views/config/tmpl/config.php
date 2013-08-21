<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');

require_once(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."models".DS."emogrifier.php");
require_once(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");

$red_enb_no = $red_enb = '';
$late_enb_no = $late_enb = '';	
$dail_enb_no = $dail_enb = '';
$deb_enb_no = $deb_enb = '';
$jstoolbar_no=$jstoolbar='';

	if ($emails_config['enb_debug'] == '1') 
		$deb_enb = ' selected ';
	else
	  $deb_enb_no = ' selected ';

	if ($emails_config['enb_batch'] == '1') 
		$red_enb = ' selected ';
	else
		$red_enb_no = ' selected ';
	
	if ($emails_config['jstoolbar'] == '1') 
		$jstoolbar = ' selected ';
	else
		$jstoolbar_no = ' selected ';
		
/*	if ($emails_config['enb_latest'] == '1') 
		$late_enb = ' selected ';
	else
	  $late_enb_no = ' selected ';
*/	

//1st Parameter: Specify 'tabs' as appearance 
//2nd Parameter: Starting with third tab as the default (zero based index)
//open one!

echo "<form method='POST' name='adminForm' action='' id='adminForm'>";
//$pane =& JPane::getInstance('tabs', array('startOffset'=>0)); //display a tabbed pane
//echo $pane->startPane( 'pane' );
//echo $pane->startPanel( JText::_('CONFIG'), 'panel1' );//tab 1
	?>
	<table border="0" width="100%" class="adminlist">
	<tr>
		<td align="left" width="10%"><strong><span class="hasTip" title="<?php echo JText::_('OI_PRIVATE_KEY'); ?>::<?php echo JText::_('PRIVATE_KEY_CRON'); ?>"><?php echo JText::_('PRIVATE_KEY_CRON'); ?>:</strong></td>
		<td><input type="text" class="inputbox" name="data[private_key_cronjob]" width="90%" value="<?php echo $emails_config['private_key_cronjob']; ?>"  ></td>
		
	</tr>
	<tr>
		<td align="left" width="10%"><strong><span class="hasTip" title="<?php echo JText::_('ENB_DEBUG'); ?>::<?php echo JText::_('ENB_DEBUG_DES'); ?>"><?php echo JText::_('ENB_DEBUG'); ?></span></strong></td>
		<td width="90%"><select class="inputbox" name="data[enb_debug]">
		<option value="1" <?php echo $deb_enb; ?>> <?php echo JText::_('JMA_YES');?> </option>
		<option value="0" <?php echo $deb_enb_no; ?>> <?php echo JText::_('JMA_NO');?> </option>
		</select>
		</td>
	</tr>

	<tr>
		<td align="left" width="10%"><strong><span class="hasTip" title="<?php echo JText::_('ENB_BTC'); ?>::<?php echo JText::_('ENB_BATCH'); ?>"><?php echo JText::_('ENB_BATCH'); ?></span></strong></td>
		<td width="90%"><select class="inputbox" name="data[enb_batch]">
		<option value="1" <?php echo $red_enb; ?>> <?php echo JText::_('JMA_YES');?> </option>
		<option value="0" <?php echo $red_enb_no; ?>> <?php echo JText::_('JMA_NO');?> </option>
		</select>
		</td>
	</tr>		
	<tr>
		<td align="left" width="20%"><strong><span class="hasTip" title="<?php echo JText::_('PER_MNO'); ?>::<?php echo JText::_('PER_GOES_INV'); ?>"><?php echo JText::_('PER_GOES_INV'); ?>:</strong></td>
		<td><input type="text" name="data[inviter_percent]" value="<?php echo $emails_config['inviter_percent']; ?>"></td>
	</tr>	
	<tr>
		<td align="left" width="20%"><strong><span class="hasTip" title="<?php echo JText::_('FRNT_MSG'); ?>::<?php echo JText::_('INTRO_MSG'); ?>"><?php echo JText::_('INTRO_MSG'); ?>:</span></strong></td>
		<td><textarea name="data[intro_msg]" rows="5" cols="30"><?php echo trim($emails_config['intro_msg']); ?></textarea>

		</td>
	</tr>
	<tr>
		<td align="left" width="20%"><span class="hasTip" title="<?php echo JText::_('CRN_URL'); ?>::<?php echo JText::_('CRON_URL'); ?>"><strong><?php echo JText::_('CRON_URL');?>:</span></strong></td>
		<td>
			<?php
				echo str_replace('administrator/', '', JURI::base()).'index.php?option=com_jmailalerts&view=emails&tmpl=component&task=processMailAlerts&pkey='.$emails_config['private_key_cronjob'];
			?>
		</td>	
	</tr>
	<tr>
		<td align="left" width="10%"><strong><span class="hasTip" title="<?php echo JText::_('JSTOOLBAR'); ?>::<?php echo JText::_('JSTOOLBAR_DESC'); ?>"><?php echo JText::_('JSTOOLBAR'); ?></span></strong></td>
		<td width="90%"><select class="inputbox" name="data[jstoolbar]">
		<option value="1" <?php echo $jstoolbar; ?>> <?php echo JText::_('JMA_YES');?> </option>
		<option value="0" <?php echo $jstoolbar_no; ?>> <?php echo JText::_('JMA_NO');?> </option>
		</select>
		</td>
	</tr>							
	
	</table>
	<?php			

//echo $pane->endPanel();
//echo $pane->endPane( 'pane' );
?>
	<input type="hidden" name="option" value="com_jmailalerts" />		
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="controller" value="config" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
