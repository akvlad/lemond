
<?php

defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.formvalidation');
require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");
$doc =& JFactory::getDocument();
$doc->addStyleSheet("components".DS."com_jmailalerts".DS."css".DS."jmailalerts.css");
$doc->addStyleDeclaration('.ui-accordion-header {margin: 1px 0px !important}');

$document =& JFactory::getDocument();
$js= '
	function divhide(thischk){

		if(thischk.checked){
			document.getElementById(thischk.value).style.display="block";
		}
		else{
			document.getElementById(thischk.value).style.display="none";
		}
	}
	
	function divhide1(thischk){
//alert(thischk.value);
		if(thischk.value==0){
			document.getElementById("ac").style.display="none";
		}
		else{
			document.getElementById("ac").style.display="block";
		}
	}
	
	function chk_frequency(preferences_form){
		//Check if the "Select Frequency" is selected and "Unsubscribe" chkbox is not chked. This means the user hasnt entered any frequency not does he want to unsubscribe. So we are making him select a frequency before submitting the form	
		if(document.manualform.c[0].selected){
			if(!document.manualform.unsubscribe_chk_box.checked){
				alert("'.JText::_('SELECT_TYPE').'");
				return;
			}
		}//if ends
		preferences_form.submit();
	}//chk_frequency() ends
';
$document->addScriptDeclaration($js);

/*added in 2.4.3*/
//newly added for JS toolbar inclusion
if(JFolder::exists(JPATH_SITE . DS .'components'. DS .'com_community') && $emails_config["jstoolbar"]== '1')
{                                        
	require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'toolbar.php');                
	$toolbar    =& CFactory::getToolbar();        
	$tool = CToolbarLibrary::getInstance();

	?>
	<style>
	<!--
		   #jma-wrap ul { margin: 0;padding: 0;}
	-->
	</style>
	<div id="jma-wrap">
		   <?php
		   echo $tool->getHTML();
}
//eoc for JS toolbar inclusion

//Get the logged in user
$user	=	JFactory::getUser();
if(!$user->id) {
	echo '<div style="margin:25px;"><h1 align="center">'.JText::_("YOU_NEED_TO_BE_LOGGED_IN").'</h1></div>';
	return false;	
}
?>
<div class="col100" id="e-mail_alert">
<form action="" class="form-validate" method="POST" name="manualform" ENCTYPE="multipart/form-data" >
	<div class="componentheading"><?php echo $this->page_title;?></div>
<?php if ($emails_config['intro_msg'] != ''){?>
<div class="jma_email_intro">
	<div class="box-container-t">
		<div class="box-tl"></div>
		<div class="box-tr"></div>
		<div class="box-t"></div>
	</div>									
	<div class="content_cover">	
		<span class="alert_preferences_intro_msg" style="font-size:14px;font-weight:bold;">										
		<?php echo $emails_config['intro_msg'];?>	
		</span>
	</div> 
	<div class="content_bottom">
		<div class="box-bl"></div>
		<div class="box-br"></div>
		<div class="box-b"></div>
	</div>
</div>
<?php } ?>
<br/>

	<?php 
	$disp_none = " ";
	if (trim($this->cntalert) == 0) 
		{$disp_none = "display:none";
			
		}
		?>

	<table border="0" cellpadding="" cellspacing="" width="100%">
		<tr>
			<td>
				<?php //JHTML::_('select.radiolist', $align, 'align', 'class="inputbox" onclick="alert(1)"', 'value', 'text', 'left', 'align' );
			$maplist[] = JHTML::_('select.option', '0', JText::_('N0_FREQUENCY'), 'value', 'text');?>
				
			</td>
		</tr>
		<tr>
		<td>			

    <div id="ac" style="<?php echo $disp_none ;?>" >
			<?php 
				if (trim($this->cntalert) != 0) 
				{
				    if(JVERSION  >= '1.6.0'){     
				    	echo $this->loadTemplate('joomla16');
				  	}
				  	else{

				       echo $this->loadTemplate('joomla15');
							}
				}			
?> 					
		</div>
		</td>
		</tr>
	</table>
	<div id="manual_div" align="left" style="display:block; padding-top: 10px;">
		<input type="hidden" name="option" value="com_jmailalerts">					
		<input type="hidden" id="task" name="task" value="savePref">
		<?php 
				if (trim($this->cntalert) != 0) 
				{
			?>	
		<button class="button validate" type="submit" onclick="chk_frequency(this.form);"><?php echo JText::_('BUTTON_SAVE'); ?></button>
		<?php }?>
	</div>
</form>							
</div>
