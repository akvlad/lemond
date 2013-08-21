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
$document->addStyleSheet(JURI::base().'components/com_jmailalerts/css/mail_alert_general.css');
$save_js16="Joomla.submitbutton=function(pressbutton)
	{
	//This is for creating array of pluginnames seperated by comma
		var plugins_array1 = new Array();
		var form = document.adminForm;
		if (pressbutton == 'cancel')
    {
       window.location = \"index.php?option=com_jmailalerts\";

    }
    if (pressbutton == 'save') 
		{
			
      var j = 0;
			/*if(document.forms['adminForm'].elements['plugin[name][]'])
      for(var i = 0; i < document.forms['adminForm'].elements['plugin[name][]'].options.length; i++)
			{
				if(document.forms['adminForm'].elements['plugin[name][]'].options[i].selected)
				{
					plugins_array1[j++] = document.forms['adminForm'].elements['plugin[name][]'].options[i].value;//.value;
		
				}

			}*/
			if(document.forms['adminForm'].elements['altypename[]'])
      for(var i = 0; i < document.forms['adminForm'].elements['altypename[]'].options.length; i++)
			{
				if(document.forms['adminForm'].elements['altypename[]'].options[i].selected)
				{
					plugins_array1[j++] = document.forms['adminForm'].elements['altypename'].options[i].value;//.value;
					
		
				}

			}
			
     
     if(plugins_array1.length==0)
      {
         alert('".JText::_('SEL_AT_ONE_ALERT_TYPE')."');
         return false;
      }
      //document.forms['adminForm'].pluginname.value=plugins_array1;
      document.forms['adminForm'].alertypeid.value=plugins_array1;
		 //End This is for creating array of pluginnames seperated by comma			
	   Joomla.submitform( pressbutton );
		
    return;
		}

	}
";


$save_js15="function submitbutton(pressbutton)
	{
	//This is for creating array of pluginnames seperated by comma
		var plugins_array1 = new Array();
		var form = document.adminForm;
		if (pressbutton == 'cancel')
    {
       window.location = \"index.php?option=com_jmailalerts\";

    }
    if (pressbutton == 'save') 
		{
			
      var j = 0;
			/* if(document.forms['adminForm'].elements['plugin[name][]'])
      for(var i = 0; i < document.forms['adminForm'].elements['plugin[name][]'].options.length; i++)
			{
				if(document.forms['adminForm'].elements['plugin[name][]'].options[i].selected)
				{
					plugins_array1[j++] = document.forms['adminForm'].elements['plugin[name][]'].options[i].value;//.value;
		
				}

			}*/
			if(document.forms['adminForm'].elements['altypename[]'])
      for(var i = 0; i < document.forms['adminForm'].elements['altypename[]'].options.length; i++)
			{
				if(document.forms['adminForm'].elements['altypename[]'].options[i].selected)
				{
					plugins_array1[j++] = document.forms['adminForm'].elements['altypename'].options[i].value;//.value;
					
		
				}

			}
			
   
     if(plugins_array1.length==0)
      {
         alert('".JText::_('SEL_AT_ONE_ALERT_TYPE')."');
         return false;
      }
      
      document.forms['adminForm'].alertypeid.value=plugins_array1;
     

		 //End This is for creating array of alertypeid seperated by comma			
	   submitform( pressbutton );
		
    return;
		}

	}
";
?>
<script>
	/*
		 ajaxFunction
		 arguments:
				flag : int
					->if 0, ajaxfunction is making the first request; if 1, first server response is recvd
					  the flag is used to get the no of users to be synced only in from the server
				val : int
					->batch-size				
	*/
	var percent = 0, total_users = 0, count = 0, return_cnt = 0;
	//return_cnt var is used to keep track of how many times the the ajax function makes requests to the server
	var plugins_array = new Array();
	var plugins_array1 = new Array();

	var return_data_array = new Array();//used to store the comma-seperatd data from the server
	var frequency;
	var	checked;

	
	function admin_confirm(thischk,msg){http://192.168.1.200/~yogesh/joomla1_5_23/administrator/index.php?option=com_jmailalerts&view=sync
		if(thischk.checked=="1"){
			var ans=confirm(msg);
			if (!ans)
				thischk.checked=0;
		}
	}
	function ajaxFunction(flag, val){
		var j = 0;	
		var xmlhttp;
		if (window.XMLHttpRequest) {
		  // code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		}
		else{
		  // code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4){
			  	var server_data = xmlhttp.responseText;
			    		if(server_data == "No rows"){	
					progress_stop();//stop the progress bar and hide it	
					alert("Done.");
					return;
				}
				else if(server_data == "Insertion error"){	
					progress_stop();//stop the progress bar and hide it	
					alert("Some error occured while inserting data into the jos_email_alert table. Retry.");
					return;
				}	
				else{	
					if(return_cnt == 0){
						return_cnt ++;
						return_data_array = server_data.split(',');
						if(return_data_array.length == 2)
							total_users = return_data_array[1];
					}
					count = parseInt(count) + parseInt(val);
			
					if(total_users){
						if(count > parseInt(total_users))
							document.getElementById('percent_label').innerHTML  = "100%";
						else{
							percent = (parseInt(count) / parseInt(total_users)) * 100;
							document.getElementById('percent_label').innerHTML  = Math.round(percent)+"%";
						}
					}//if ends 
					ajaxFunction(return_data_array[0], val);
				}
			}//if ends
		}
		var tmp=document.forms['adminForm'].alertypeid.value;
		var plugname=document.forms['adminForm'].plugname.value;
		
	//alert("ajax");
	//xmlhttp.open("GET","index.php?option=com_jmailalerts&view=ajaxsync&format=raw&duration="+frequency+"&plugins="+plugins_array+"&flag="+flag+"&batch_size="+val+"&chk="+checked+"&alertid"+plugins_array1,true);
	 xmlhttp.open("GET","index.php?option=com_jmailalerts&view=ajaxsync&format=raw&flag="+flag+"&batch_size="+val+"&chk="+checked+"&rsync="+resynced+"&alertid="+tmp+"&plugname="+plugname,true);
		xmlhttp.send(null);
	}	
	//This function will pull the listbox and combobox selected items into the array and a variable
	function pullDataIntoArrays(){
		var j = 0;
		/*
		for(var i = 0; i < document.forms['adminForm'].elements['plugin[name][]'].options.length; i++){
			if(document.forms['adminForm'].elements['plugin[name][]'].options[i].selected){
				plugins_array[j++] = document.forms['adminForm'].elements['plugin[name][]'].options[i].value;//.value;
			}
		}
		*/
	
		for(var i = 0; i < document.forms['adminForm'].elements['altypename[]'].options.length; i++)
			{
				if(document.forms['adminForm'].elements['altypename[]'].options[i].selected)
				{
					plugins_array1[j++] = document.forms['adminForm'].elements['altypename'].options[i].value;//.value;
					
				}

			}
			
		document.forms['adminForm'].alertypeid.value=plugins_array1;
		
		
		//frequency = document.getElementById("c").options[document.getElementById("c").selectedIndex].value;
		checked = document.getElementById("chk_user").checked;
		if(checked == "0")
		{checked=0;
		
		}else{
			checked=1;
		}
		
		resynced = document.getElementById("chk_resync").checked;
		if(resynced == "0")
		{resynced=0;
		
		}else{
			resynced=1;
		}
	}

	/*Progress bar data and functions*/
	var progressEnd = 11; // set to number of progress <span>'s.
	var progressColor = 'orange'; // set to progress bar color
	var progressInterval = 1000; // set to time between updates (milli-seconds)

	var progressAt = progressEnd;
	var progressTimer;
	function progress_clear() {
		for (var i = 1; i <= progressEnd; i++) document.getElementById('progress'+i).style.backgroundColor = 'transparent';
		  progressAt = 0;
	}

	function progress_update() {
		document.getElementById('sync_button').disabled = true;
		document.getElementById('progress_bar_div').style.display = 'block';
		progressAt++;
		if (progressAt > progressEnd) progress_clear();
		else document.getElementById('progress'+progressAt).style.backgroundColor = progressColor;
		progressTimer = setTimeout('progress_update()',progressInterval);
	}

	function progress_stop() {
		clearTimeout(progressTimer);
		progress_clear();
		document.getElementById('progress_bar_div').style.display = 'none';
		document.getElementById('sync_button').disabled = false;
	}
	/*Progress bar data and functions*/

	function getConfigValue(){
		 return document.getElementById('config_box').value;
	}//getConfigValue() ends	

	function validate_form(){
		
		var chek_alert = new Array();
		var j=0;
		if(document.forms['adminForm'].elements['altypename[]'])
      for(var i = 0; i < document.forms['adminForm'].elements['altypename[]'].options.length; i++)
			{
				if(document.forms['adminForm'].elements['altypename[]'].options[i].selected)
				{
					chek_alert[j++] = document.forms['adminForm'].elements['altypename'].options[i].value;//.value;
					
		
				}

			}
			if(chek_alert.length==0)
      {
       return 0;  
			}
			
		return 1;
	}//validate_form() ends

</script>
<?php 
if(JVERSION >= '1.6.0')
     $document->addScriptDeclaration($save_js16);	
		else
      $document->addScriptDeclaration($save_js15);	
?>
<form action='index.php'  method="POST" name="adminForm" ENCTYPE="multipart/form-data" id="adminForm">
			<?php echo "<h3>".JText::_('SYNC_NOTE')."</h3><br /><br />";?>
	<table border="0" width="100%" >
	<tbody>
		
<?php		
if(!empty($this->plugin_data)){//if there are plugins found in the `plugins` table, only then add the HTMl controls; else, display message
?>
		<tr>
			<td>
					<?php //echo JText::_('INSTALLED_PLUGINS');
						echo JText::_('SELECT_ATYPE');
					?>
			</td>
			<td>
		
				<?php echo $this->alertname;?>
			</td>
		</tr>	
		<tr>
			<td>
				<span class="hasTip" title="<?php echo JText::_('SYNC_BTCH'); ?>::<?php echo JText::_('SYNC_BATCHSIZE'); ?>"><?php echo JText::_('SYNC_BATCHSIZE');?></span>
			</td>
			<td>
				<input type="text" width="5" size="4" maxlength="5" value="400" id="config_box" name="config_box"/>	
			</td>
		</tr>
		<tr>
			<td>
				<span class="hasTip" title="<?php echo JText::_('SAVE_PLG'); ?>::<?php echo JText::_('SAVE_PLUG_CHK'); ?>"><?php echo JText::_('SAVE_PLUG_CHK');?></span>
			</td>
			<td>
				<input type="checkbox" id = "chk_user" value="1" unchecked onclick="admin_confirm(this,'<?php echo JText::_('OVERWRI_USER');?>')"/>
			</td>
			
		</tr>
		<tr>
			<td>
				<span class="hasTip" title="<?php echo JText::_('RESYNC'); ?>::<?php echo JText::_('RESYNC_USR_CHK'); ?>"><?php echo JText::_('RESYNC_USR_CHK');?></span>
			</td>
		   <td>
				<input type="checkbox" id = "chk_resync" value="1" unchecked onclick="admin_confirm(this,'<?php echo JText::_('RESYNC_USER');?>')"/>
		   </td>
		</tr>
		<tr>
			<td>
				<button type="button" id = "sync_button"  onclick=" 
										   if(validate_form()){ //if the validation passes, submit form
											
											var val = getConfigValue();
											if(parseInt(val) > 0){
												
										   		progress_update();
												   pullDataIntoArrays();
										   		ajaxFunction(0, val);//0->flag, val->batch-size
											}
											else
												alert('Incorrect batch-size value.');
										   }
										   else{
											alert('<?php echo JText::_('SEL_OPT');?>');
										   }
										  
										  "
				> <?php echo JText::_('SYNC');?></button>
			</td>	
			<td>
				<div id= "progress_bar_div" style="display:none">
			     	<div id="showbar" style="font-size:8pt;padding:2px;border:solid black 1px;width:130px;">
						<span id="progress1">&nbsp; &nbsp;</span>
						<span id="progress2">&nbsp; &nbsp;</span>
						<span id="progress3">&nbsp; &nbsp;</span>
						<span id="progress4">&nbsp; &nbsp;</span>
						<span id="progress5">&nbsp; &nbsp;</span>
						<span id="progress6">&nbsp; &nbsp;</span>
						<span id="progress7">&nbsp; &nbsp;</span>
						<span id="progress8">&nbsp; &nbsp;</span>
						<span id="progress9">&nbsp; &nbsp;</span>
						<span id="progress10">&nbsp; &nbsp;</span>
						<span id="progress11">&nbsp; &nbsp;</span>
			        </div>
					<label value="asdasd"  id="percent_label">0%</label>
					<?php echo JText::_('SYNCING_WAIT');?>
				</div>
			 </td>
		</tr>
<?php		}//if ends
		else{ ?>		
		<tr>
			<td>
				<b><?php echo JText::_('NO_PLUGINS');?></b>
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
<?php		}

$plugnamesend = implode(',',$this->email_alert_plugin_names);

?>
	</tbody>
	</table>
	<input type="hidden" name="option" value="com_jmailalerts" />	
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="controller" value="sync" />
	<input type="hidden" name="pluginname" value="" />
	<input type="hidden" name="plugname" value="<?php echo $plugnamesend;?>" />
	<input type="hidden" name="alertypeid" value="" />
	
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	


