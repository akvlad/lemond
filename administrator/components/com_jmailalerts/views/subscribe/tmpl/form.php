<?php defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.filesystem.file' );
require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."models".DS."emogrifier.php");
//require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");
require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."default_template.php");

$checktask = JRequest::getVar('task');
//print_r($this->subscript->id);

$cssfile = JURI::BASE()."components/com_jmailalerts/css/mail_alert_general.css";

$document =& JFactory::getDocument();
//validate
$save_js16="Joomla.submitbutton=function(pressbutton)
	{
	
	//This is for creating array of pluginnames seperated by comma
		var alerts ;
		var frequency ;
		var form = document.adminForm;
		if (pressbutton == 'cancel')
    {
       window.location = \"index.php?option=com_jmailalerts\";

    }
    if (pressbutton == 'save') 
		{   
		
			if(document.forms['adminForm'].user_id.value == '')
			{  
				alert('".JText::_('USR_WRNG')."');
				return false;
			}			
	 
           alerts = document.forms['adminForm'].elements['alert_nm'].value;
		   frequency = 	document.forms['adminForm'].elements['frequency'].value;

//alert(frequency);
			
		     if(alerts.length==0)
		      {
				alert('".JText::_('SEL_ALT')."');
				return false;
		      }
		      
		      if(frequency.length==0)
		      {
				alert('".JText::_('SEL_ALLOW_FREQ')."');
				return false;
		      }
         			 
		 //End This is for creating array of alertypeid seperated by comma			
	   submitform( pressbutton );
		
    return;
		}

	}
";

$save_js15="function submitbutton(pressbutton)
	{
	
	//This is for creating array of pluginnames seperated by comma
		var alerts ;
		var frequency ;
		var form = document.adminForm;
		if (pressbutton == 'cancel')
    {
       window.location = \"index.php?option=com_jmailalerts\";

    }
    if (pressbutton == 'save') 
		{   
		//alert(document.forms['adminForm'].user_id.value);
			if(document.forms['adminForm'].user_id.value == '')
			{  
				alert('".JText::_('USR_WRNG')."');
				return false;
			}			
	 
           alerts = document.forms['adminForm'].elements['alert_nm'].value;
		   frequency = 	document.forms['adminForm'].elements['frequency'].value;

//alert(alerts);
			
		     if(alerts=='Array')
		      {
				alert('".JText::_('SEL_ALT')."');
				return false;
		      }
		      
		      if(frequency.length==0)
		      {
			alert('".JText::_('SEL_ALLOW_FREQ')."');
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
				<label for="user_id">
					<?php echo JText::_( 'ENT_UID' ); ?>
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="user_id" id="user_id" size="20" maxlength="25" value="<?php if(isset($this->subscript->user_id))echo $this->subscript->user_id;?>" />
				
			</td>
		
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="Alerts">
					<?php echo JText::_( 'ALT_NAME' ); ?>:
				</label>
			</td>
			<td>
							
				<?php 
				
				   echo $this->alert_nm;  
				 
				?>						
			</td>
		</tr>
		
		<tr>
			<td align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'SEL_FREQ' ); ?>:
				</label>
			</td>
			<td>
				<?php
									
				echo $this->rows;
				?>	
				
			</td>
		</tr>
		
	
		</table>
		
		
			
			
	
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_jmailalerts" />
<input type="hidden" name="id" value="<?php echo $this->subscript->id; ?>" />
<input type="hidden" name="plugname" value="<?php //echo $plugnamesend;?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="manageuser" />
</form>
