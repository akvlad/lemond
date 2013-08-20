<?php 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
jimport('joomla.html.pane');
//1st Parameter: Specify 'tabs' as appearance 
//2nd Parameter: Starting with third tab as the default (zero based index)
//open one!
//Create a JSimpleXML object
$xml = JFactory::getXMLParser('Simple'); 
$currentversion = '';
//Load the xml file
$xml->loadFile(JPATH_SITE.'/administrator/components/com_jmailalerts/jmailalerts.xml');
foreach($xml->document->_children as $var)
{
	if($var->_name=='version')
		$currentversion = $var->_data;
}
?>

<script type="text/javascript">

function vercheck()
{
	callXML('<?php echo $currentversion; ?>');
	if(document.getElementById('NewVersion').innerHTML.length<220)
	{
		document.getElementById('NewVersion').style.display='block';
	}
}

function callXML(currversion)
{
	if (window.XMLHttpRequest)
	  {
	 	 xhttp=new XMLHttpRequest();
	  }
	else // Internet Explorer 5/6
	  {
	 	xhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }

	xhttp.open("GET","<?php echo JURI::base(); ?>index.php?option=com_jmailalerts&task=getVersion",false);
	xhttp.send("");
	latestver=xhttp.responseText;

	if(latestver!=null)
	{
		if(currversion == latestver){
			document.getElementById('NewVersion').innerHTML='<?php echo JText::_('COM_JMA_CURRENT_VERSION');?> :'+currversion+' &nbsp;</br><span style="display:inline; color:#339F1D;"> <?php echo JText::_('COM_JMA_LATEST_VERSION');?> :'+latestver;
		}
		else{
			document.getElementById('NewVersion').innerHTML='<?php echo JText::_('COM_JMA_CURRENT_VERSION');?> :'+currversion+' &nbsp;</br><span style="display:inline; color:#FF0000;"> <?php echo JText::_('COM_JMA_LATEST_VERSION');?> :'+latestver;
		}
	}
}
</script>

<div id="cpanel" style="float: left; width: 40%;">
	<div  style="float: left; width: 30%;">
		<div style="float: left;">
			<div class="icon">
				<a href="index.php?option=com_jmailalerts&view=config">
					<img src="<?php echo JURI::base();?>components/com_jmailalerts/images/icons/mail_alert_configure.png" alt="Config"/>
					<span><?php echo JText::_("CONFIG");?></span>
				</a>
			</div>
		</div>
	</div>

	<div style="float: left; width: 30%;">
		<div style="float: left;">
			<div class="icon">
				<a href="index.php?option=com_jmailalerts&view=sync">
					<img src="<?php echo JURI::base()?>components/com_jmailalerts/images/icons/mail_alert_synchronize.png" alt="Config"/>
					<span><?php echo JText::_("SYNC");?></span>
				</a>
			</div>
		</div>
	</div>

	<div style="float: left; width: 30%;">
		<div style="float: left;">
			<div class="icon">
				<a href="index.php?option=com_jmailalerts&view=mailsimulate">
					<img src="<?php echo JURI::base();?>components/com_jmailalerts/images/icons/mail_alert_mail_find.png" alt="Config"/>
					<span><?php echo JText::_("MAILSIMULATE");?></span>
				</a>
			</div>
		</div>
	</div>

	<div style="float:left; width: 30%;">
		<div style="float: left;">
			<div class="icon">
				<a href="index.php?option=com_jmailalerts&view=alertypes">
					<img src="<?php echo JURI::base();?>components/com_jmailalerts/images/icons/alerttypes.png" alt="Config"/>
					<span><?php echo JText::_("ALERTYPES");?></span>
				</a>
			</div>
		</div>
	</div>
	
	<div style="float:left; width: 30%;">
		<div style="float: left;">
			<div class="icon">
				<a href="index.php?option=com_jmailalerts&view=healthcheck">
					<img src="<?php echo JURI::base();?>components/com_jmailalerts/images/icons/healthcheck.png" alt="Config"/>
					<span><?php echo JText::_("HEALTHCHECK");?></span>
				</a>
			</div>
		</div>
	</div>
	<div style="float:left; width: 30%;">
		<div style="float: left;">
			<div class="icon">
				<a href="index.php?option=com_jmailalerts&view=manageuser">
					<img src="<?php echo JURI::base();?>components/com_jmailalerts/images/icons/manageuser.png" alt="Config"/>
					<span><?php echo JText::_("Manageuser");?></span>
				</a>
			</div>
		</div>
	</div>
	
</div>	


<div style="float:right; width:60%; position:relative">
<?php
$pane =& JPane::getInstance('tabs', array('startOffset'=>0)); 
echo $pane->startPane( 'pane' );
echo $pane->startPanel( JText::_('JMA_ABOUT_PANEL'), 'panel1' );
?>

	<h1 style="color:#0B55C4"><?php echo JText::_('JMA_BACKEND_INTRO_HEAD');?></h1>
	<fieldset>
	<p><b>J!MailAlerts</b> is a automated periodic Email Alerts system</p>
	<p>Using J!MailAlerts you too can send your users periodic email alerts of whats happening on your site or social network.</p>
	<p>Users can select if they want to receive weekly, fortnightly or monthly updates.</p>
	<p>Its a great way to keep your users in touch with whats going on on your website & keep them coming back.</p>
	</fieldset>
<?php
echo $pane->endPanel();
echo $pane->startPanel( JText::_('JMA_INSTRUCTIONS_PANEL'), 'panel2' );
?>
	<p>To avail the features of J!MailAlerts,you require to install J!MailAlert plugins. Please make sure to install and enable them as per your required combination.</p>	
	<fieldset>
	<p>1. Do the basic settings for the extensions in the config. </p>
	<p>2. Design the email using the Mail Editor. Each plugin that you have installed is available as a TAG, which is replaced dynamically when the email is sent.</p>
	<p>3. Sync all existing users with some default settings for J!MailAlerts. You can also override current settings of users.</p>
	<p> If you still have issue in setting up the Component, please refer to the documentation here : <a href="http://techjoomla.com/table/jmailalerts.-cms-delivered-via-email/#documentation" target="_blank">Click Here</a> </p>
	<p>Community based Support is available via the forums. <a href="http://techjoomla.com/component/option,com_kunena/Itemid,94/catid,8/func,showcat/" target="_blank">Click Here</a> </p>
	If you have a Support subscription for the component. You can request Fast support direct from the developers by adding a ticket at http://techjoomla.com
	</fieldset>
<?php
echo $pane->endPanel();
echo $pane->endPane();
?>
</div>

<?php
$logo_path='<img src="'.JURI::base().'components/com_jmailalerts/images/techjoomla.png" alt="TechJoomla" style="vertical-align:text-top;"/>';
?>
<table style="margin-bottom:5px; width:100%; border-top:thin solid #e5e5e5;table-layout:fixed;">
	<tbody>
		<tr>
			<td style="text-align:left; width:33%;">
				<a href="http://techjoomla.com/index.php?option=com_billets&view=tickets&layout=form&Itemid=18" target="_blank"><?php echo JText::_('COM_JMA_TECHJOOMLA_SUPPORT_CENTER'); ?></a>
				<br/>
				<a href="http://extensions.joomla.org/extensions/content-sharing/newsletter/12536" target="_blank"><?php echo JText::_('COM_JMA_LEAVE_JED_FEEDBACK'); ?>
				</a>
			</td>
		
			<td style="text-align:center; width: 33%;">
				<?php echo JText::_("COM_JMA_COPYRIGHT"); ?>
				<div><br/>
				<span class="latestbutton" onclick="vercheck();">
					<?php echo JText::_('COM_JMA_CHECK_LATEST_VERSION');?>
				</span>
				<br/><br/>
				<div id='NewVersion' style='display:none; padding-top:5px; color:#000000; font-weight:bold; padding-left:5px;'></div>
				</div>
			</td>
		
			<td style="text-align:right; width: 33%;">
				<a href='http://techjoomla.com/' taget='_blank'>
				<?php echo $logo_path;?>
				</a>
			</td>
		</tr>
		
		<tr>
			<td style="text-align:left; width:33%;">
				<!-- twitter button code -->
				<a href="https://twitter.com/techjoomla" class="twitter-follow-button" data-show-count="false">Follow @techjoomla</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				<br/>
				<!-- facebook button code -->
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-like" data-href="https://www.facebook.com/techjoomla" data-send="true" data-layout="button_count" data-width="250" data-show-faces="false" data-font="verdana"></div>
			</td>
		</tr>
	</tbody>
</table>
