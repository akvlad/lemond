<?php defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.filesystem.file' );
require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."models".DS."emogrifier.php");
//require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");
require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."default_template.php");

$checktask = JRequest::getVar('task');

$red_enb_no = $red_enb = '';
 if (isset($this->alertype->allow_user_select_plugin))
 {
 if ($this->alertype->allow_user_select_plugin == '1') 
		{
		 $deb_enb = ' selected ';
		 $deb_enb_no = '';
		}

	else
	  {
	  $deb_enb = 'selected';
	  $deb_enb_no = '';
	  }
	}
	else
	{
	  $deb_enb_no = '';
	  $deb_enb = 'selected';
	}  
	
if (isset($this->alertype->respect_last_email_date))
 {	  
	if ($this->alertype->respect_last_email_date == '1') 
		{
			$last_email_enb = ' selected ';
			$last_email_no = '';
		}	
		else
		{
			$last_email_no = ' selected ';
			$last_email_enb = '';
		 } 

 }
 else
 {
  	  $last_email_no = ' selected ';
  	  $last_email_enb = '';
  	  
 }	  
$cssfile = JURI::BASE()."components/com_jmailalerts/css/mail_alert_general.css";

$document =& JFactory::getDocument();
//validate
$save_js16="Joomla.submitbutton=function(pressbutton)
	{
	//This is for creating array of pluginnames seperated by comma
		var allow_frq1 = new Array();
		var form = document.adminForm;
		if (pressbutton == 'cancel')
    {
       window.location = \"index.php?option=com_jmailalerts\";

    }
    
    if (pressbutton == 'save') 
		{
			
      var j = 0;
			
			if(document.forms['adminForm'].alert_name.value == '')
			{
				alert('".JText::_('ENTER_ALT_NAME')."');
				return false;
			}			
			
			
			if(document.forms['adminForm'].elements['c[]'])
      for(var i = 0; i < document.forms['adminForm'].elements['c[]'].options.length; i++)
			{
				if(document.forms['adminForm'].elements['c[]'].options[i].selected)
				{
					allow_frq1[j] = document.forms['adminForm'].elements['c[]'].options[i].value;//.value;
					//alert(document.forms['adminForm'].elements['c'].options[i].value);
					j++;
		
				}
			}
		
			//alert(document.forms['adminForm'].default_freq.value);
			for(var i=0;i<allow_frq1.length;i++)
			{
				if(allow_frq1[i] == document.forms['adminForm'].default_freq.value)
				{
				var tmp='yes';
				break;
				}
			}
			

		     if(allow_frq1.length==0)
		      {
			alert('".JText::_('SEL_ALLOW_FREQ')."');
			 return false;
		      }
		      if(!tmp)
		      {
				alert('".JText::_('SEL_ONLY_ALLOW_FREQ')."');
			return false;
		      }

		 //End This is for creating array of pluginnames seperated by comma			
	   Joomla.submitform( pressbutton );
		
    return;
		}

	}
";
$save_js15="function submitbutton(pressbutton)
	{
	
	//This is for creating array of pluginnames seperated by comma
		var allow_frq1 = new Array();
		var form = document.adminForm;
		if (pressbutton == 'cancel')
    {
       window.location = \"index.php?option=com_jmailalerts\";

    }
    if (pressbutton == 'save') 
		{
		
			if(document.forms['adminForm'].alert_name.value == '')
			{
				alert('".JText::_('ENTER_ALT_NAME')."');
				return false;
			}			
			
      var j = 0;
						
			if(document.forms['adminForm'].elements['c[]'])
      for(var i = 0; i < document.forms['adminForm'].elements['c[]'].options.length; i++)
			{
				if(document.forms['adminForm'].elements['c[]'].options[i].selected)
				{
					allow_frq1[j] = document.forms['adminForm'].elements['c[]'].options[i].value;//.value;
					//alert(document.forms['adminForm'].elements['c'].options[i].value);
					j++;
		
				}
			}
		
			//alert(document.forms['adminForm'].default_freq.value);
			for(var i=0;i<allow_frq1.length;i++)
			{
				if(allow_frq1[i] == document.forms['adminForm'].default_freq.value)
				{
				var tmp='yes';
				break;
				}
			}
			

		     if(allow_frq1.length==0)
		      {
			 alert('".JText::_('SEL_ALLOW_FREQ')."');
			 return false;
		      }
		      if(!tmp)
		      {
			alert('".JText::_('SEL_ONLY_ALLOW_FREQ')."');
			return false;
		      }
         	
		 //End This is for creating array of alertypeid seperated by comma			
	   submitform( pressbutton );
		
    return;
		}

	}
";


if(JVERSION >= '1.6.0')
     $document->addScriptDeclaration($save_js16);	
		else
      $document->addScriptDeclaration($save_js15);	

?>

<link rel="stylesheet" type="text/css" href="<?php echo $cssfile; ?>" />
   

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
		<td  align="right" class="key">
				<label for="alertname">
					<?php echo JText::_( 'ALERT_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="alert_name" id="alert_name" size="32" maxlength="250" value="<?php if(isset($this->alertype->alert_name))echo $this->alertype->alert_name;?>" />
				
				
				
			</td>
		
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'DESCRIPTION' ); ?>:
				</label>
			</td>
			<td>
				
				<textarea cols="30" rows="5" id="description" name="description"><?php if(isset($this->alertype->description))echo $this->alertype->description;?></textarea>
							
			</td>
		</tr>
		
		<tr>
			<td align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'ALLOWED_FREQ' ); ?>:
				</label>
			</td>
			<td>
				<?php
				
 				//$maplist[] = JHTML::_('select.option', '0', JText::_('SELECT_FREQUENCY'), 'value', 'text');
				//$result = array_merge($maplist, $this->rows);//Merge the arrays $rows and $maplist such that elements of $rows are appended to the $maplist array 
				//echo $selllist = JHTML::_('select.genericlist', $result, 'c[]', 'size="5" multiple','value', 'text',$this->allowfreq);
				echo $selllist = JHTML::_('select.genericlist', $this->rows, 'c[]', 'size="4" multiple','value', 'text',$this->allowfreq);
				?>			
			</td>
		</tr>
		
		<tr>
			<td align="right" class="key">
				<label for="defaultfreq">
					<?php echo JText::_( 'DEFAULT_FREQ' ); ?>:
				</label>
			</td>
			<td>
				<?php
				if(isset($this->alertype->default_freq))
				{
					$default_freq = $this->alertype->default_freq;
				}
				else
				{
					$default_freq ='';
				}
				
 				echo $selllist = JHTML::_('select.genericlist', $this->rows, 'default_freq','' ,'value', 'text',$default_freq);
				?>			
			</td>
		</tr>
		
		
		
		
		<tr>
			<td  align="right" class="key" >
				<label for="allowuserplugin">
					<?php echo JText::_( 'ALLOW_USER_CONF_ALERT' ); ?>:
				</label>
			</td>
			<td>
				<select class="inputbox" name="allow_user_select_plugin" id="allow_user_select_plugin">
					<option value="1" <?php echo $deb_enb; ?>> <?php echo JText::_('Yes');?> </option>
					<option value="0" <?php echo $deb_enb_no; ?>> <?php echo JText::_('No');?> </option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td  align="right" class="key" >
				<label for="resepectlastemail">
					<?php echo JText::_( 'ENB_LATEST' ); ?>:
				</label>
			</td>
			<td>
				<select class="inputbox" name="respect_last_email_date" id="respect_last_email_date">
					<option value="1" <?php echo $last_email_enb; ?>> <?php echo JText::_('Yes');?> </option>
					<option value="0" <?php echo $last_email_no; ?>> <?php echo JText::_('No');?> </option>
				</select>
			</td>
		</tr>
		
		</table>
		
		<table border="0" width="100%" class="adminlist">		
			<tr>
				<td align="left" width="10%"><strong><?php echo JText::_('E_SUBJECT'); ?>:</strong></td>
				<td width="90%">
				<?php if($checktask == 'edit'){ ?>
				<input type="text" size="50" name="email_subject"  id="email_subject" value="<?php echo $this->alertype->email_subject; ?>" />
				<?php } else { ?>
				<input type="text" size="50" name="email_subject"  id="email_subject" value="<?php echo $emails_config['message_subject']; ?>" />
				<?php } ?>
				</td>
			</tr>
		</table>
		
		<table class="admintable">
		<tr>
		<td colspan="2" align="left" ><strong><?php echo JText::_('E_BODY'); ?>:</strong></td>		
		</tr>
		
		<tr>
			<td><?php $editor      =& JFactory::getEditor();
			 $cssfile = JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."default_template.css";
			$cssdata = JFile::read($cssfile);
			if($checktask == 'edit')
			{
				if(!function_exists('mb_convert_encoding'))		// condition to check if mbstring is enabled
				{
					echo JText::_("MB_EXT");
					$emorgdata=$this->alertype->template;
				}
				else
				{
					$emogr=new Emogrifier($this->alertype->template,$this->alertype->template_css);
					$emorgdata=$emogr->emogrify();
				}
				echo $editor->display("template",$emorgdata,670,600,40,20,true);
			}
			else
			{
			
				//Code to Read CSS File
			
				if(!function_exists('mb_convert_encoding'))		// condition to check if mbstring is enabled
				{
					echo JText::_("MB_EXT");
					$emorgdata=$emails_config['message_body'];
				}
				else
				{
					//$cssfile = JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."mail_alert_style.css";
		     
					//End Code to Read CSS File

					$emogr=new Emogrifier($emails_config['message_body'],$cssdata);
					$emorgdata=$emogr->emogrify();
				}
			
				echo $editor->display("template",stripslashes($emorgdata),670,600,40,20,true);
			}
			?>	
			</td>
			<td valign="top" margin-left="10">
			
			<table>
			<?php
			if($checktask == 'edit')
			{?>
			<tr>
					<td><strong><?php echo JText::_('JMA_CSS_EDITOR_MSG') ?> </strong>
						<textarea name="template_css" rows="10" cols="70"><?php echo trim($this->alertype->template_css); ?></textarea>
					</td>
				</tr>
			<?php }
			else
			{
			?>	
			<tr>
					<td><strong><?php echo JText::_('JMA_CSS_EDITOR_MSG') ?> </strong>
						<textarea name="template_css" rows="10" cols="70"><?php echo trim($cssdata); ?></textarea>
					</td>
				</tr>
			<?php }?>	
			
			<tr>
				<td><div class= "jma_div1">[NAME]</div>- Name of person receiving email </td>
			</tr>
			<tr>
				<td><div class= "jma_div1" >[SITENAME]</div>	- Name of site </td>
			</tr>
			<tr>
				<td><div class= "jma_div1">[SITELINK]</div> - Site link </td>
			</tr>
			<tr>
				<td><div class= "jma_div1">[PREFRENCES]</div> - Prefrences link for JMail Alerts </td>
			</tr>
			<tr>
				<td><div class= "jma_div1">[mailuser]</div> 	- Email of subscriber </td>
			</tr>
			
			<?php
			 $plugnamesend = implode(',',$this->email_alert_plugin_names);
			
			//This code echoes the plugin 'tags' on the right side of the config
			 $i = 0; //set index to 0
			
			foreach($this->email_alert_plugin_names as $email_alert_plugin_name){
				//@TODO Description of the plugins to be added...
				//echo '<div class= "jma_div1">['.$email_alert_plugin_name.']&nbsp;</div>';
				echo '<tr><td><div class= "jma_div1">['.$email_alert_plugin_name.']&nbsp;</div>- '.$this->plugin_description_array[$i++].'</td></tr>';
			}	


				?>
				
			
			</table>
			
			</td>
		<tr>
		</table>	
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_jmailalerts" />
<input type="hidden" name="id" value="<?php echo $this->alertype->id; ?>" />
<input type="hidden" name="plugname" value="<?php echo $plugnamesend;?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="alertype" />
</form>
