<?php
/*
	* @package J!MailALerts
	* @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
	* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	* @link     http://www.techjoomla.com
*/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
JHTML::_('behavior.modal', 'a.modal');	
?>

<SCRIPT>
	function validate_form(){
		//Chk if both the userid and the email address is entered... 
    
		if(document.getElementById('user_id_box').value == '' || document.getElementById('send_mail_to_box').value == '')
			return 0;
		else return 1;
	}//validate() ends

	function submit_this_form(simulation_form){
		simulation_form.submit();
	
	}//submit_this_form() ends
	
	function hello()
	{
	  
	  var link= "<?php echo JURI::base().'index.php?option=com_jmailalerts&controller=mailsimulate&task=simulate&tmpl=component&send_mail_to_box=admin@admin.com&flag=1&user_id_box='; ?>"
	  var userid = document.getElementById('user_id_box').value;
	  var sdate = document.getElementById('select_date_box').value;
	  
	  var alertname = document.getElementById('altypename').value;	 
	  link = link+userid+"&select_date_box="+sdate+"&altypename="+alertname;
	  document.getElementById('linkforsimulate').setAttribute('href',link);
	 }
</SCRIPT>
<form action=""  method="POST" name="simulation_form" ENCTYPE="multipart/form-data" id="simulation_form">
	
	<table border="0" width="100%" >	   
	<tbody>	
	
		<tr>
			<td>
					<?php echo JText::_("SELECT_ATYPE"); ?>
			</td>
			<td>
					<?php echo $this->alertname;?>
			</td>
		</tr>
	
		<tr>
			<td>
					<?php echo JText::_("USER_ID"); ?>
			</td>
			<td>
					<input type="text" width="20" size="20" maxlength="20" value="" name = "user_id_box" id="user_id_box"/>	
			</td>
		</tr>
		<tr>
			<td>
					<?php echo JText::_('SEND_MAIL_TO');?>
			</td>
			<td>
					<input type="text" width="20" size="20" maxlength="40" value="" name = "send_mail_to_box" id="send_mail_to_box"/>	
			</td>
		</tr>
		<!-- code for add calender -->
		<tr>
			<td>
					<?php echo JText::_('SELECT_DATE');?>
			</td>
			<td>
     				<?php echo JHTML::_('calendar'
		          						 , date('')
										 , 'select_date_box'
										 , 'select_date_box'
										 , '%Y-%m-%d '
										 , array('class'=>'inputbox', 'size'=>'20', 'maxlength'=>'19','name'=>'select_date_box','id'=>'select_date_box')
										 );?>
					
			</td>
			
		</tr>		
		<tr>
			<td>
				<button type="button" id = "simulate_button"  onclick=" 
										   if(validate_form()){ //if the validation passes, submit form
											   submit_this_form(this.form);
										   }
										   else{
											alert('Make sure user id and the email address is entered.');
										   }
										  
										  "
				> <?php echo JText::_('SIMULATE');?></button>
			</td>	
			<td>
				&nbsp;
				<a id ="linkforsimulate" rel="{handler: 'iframe', size: {x:700, y: 600}}" onclick ="hello();" href= "<?php echo JURI::base();?>" class='modal'><?php echo JText::_('PREVIEW');?></a> 
			 </td>
		</tr>
	</tbody>
	</table>

	<input type="hidden" name="option" value="com_jmailalerts" />		
	<input type="hidden" name="task" value="simulate" />
	<input type="hidden" name="controller" value="mailsimulate" />
</form>
	


