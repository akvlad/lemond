<?php
/**
 * @version $Id: header.php 248 2008-05-23 10:40:56Z elkuku $
 * @package		Jmailalerts
 * @subpackage
 * @author		EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author		Tekdi Web Solutions {@link http://www.nik-it.de}

 */
//no direct access
defined( '_JEXEC' ) or die('Not Authorized');

echo JText::_('<br/><span style="font-weight:bold;">Installing User Plugin:</span>');
jimport('joomla.installer.installer');
jimport( 'joomla.filesystem.file' );

$db = & JFactory::getDBO();
$install_status = new JObject();
$install_status->plugins = array();
$install_source = $this->parent->getPath('source');

//install Jmailalert user plugin and publish it
if(JVERSION >= '1.6.0')
{
    $installer = new JInstaller;
    $result = $installer->install($install_source.DS.'plug_usr_J!mailalert_16');
    $query = "UPDATE #__extensions SET enabled=1 WHERE element='plug_usr_mailalert ' AND folder='user'";
    $db->setQuery($query);
    $db->query();
}
else
{
    $installer = new JInstaller;
    $result = $installer->install($install_source.DS.'plug_usr_J!mailalert');//different plugin for joomla 1.5.x
    $query = "UPDATE #__plugins SET published=1 WHERE element='plug_usr_mailalert ' AND folder='user'";
    $db->setQuery($query);
    $db->query();
}
echo ($result)?JText::_('<br/><span style="font-weight:bold; color:green;">J!MailAlerts User - Registration installed and published</span>'):JText::_('<br/><span style="font-weight:bold; color:red;">J!MailAlerts User - Registration not installed</span>');

function row2text($row,$dvars=array())
{

    reset($dvars);
    while(list($idx,$var)=each($dvars))
    unset($row[$var]);
    $text='';
    reset($row);
    $flag=0;
    $i=0;
    while(list($var,$val)=each($row))
    {
        if($flag==1)
        $text.=",\n";
        elseif($flag==2)
        $text.=",\n";
        $flag=1;

        if(is_numeric($var))
        if($var{0}=='0')
        $text.="'$var'=>";
        else
        {
            if($var!==$i)
            $text.="$var=>";
            $i=$var;
        }
        else
        $text.="'$var'=>";
        $i++;

        if(is_array($val))
        {
            $text.="array(".$this->row2text($val,$dvars).")";
            $flag=2;
        }
        else
        $text.="\"".addslashes($val)."\"";
    }

    return($text);
}

//preserve existing configuration file
function com_install()
{
    jimport('joomla.filesystem.file');
    $filename=JPATH_SITE.DS.'components'.DS.'com_jmailalerts'.DS.'emails'.DS.'config.php';
    $filename_default = JPATH_SITE.DS.'components'.DS.'com_jmailalerts'.DS.'emails'.DS.'config_default.php';

    $db= &JFactory::getDBO();
    //checking column exisist or not 
    //$exisitqry="select column_name from INFORMATION_SCHEMA.columns where table_name = '#__email_alert_Default' and column_name = 'plugins'";
   
   //get version of current component of JMA and check match of version
   $xml =JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_jmailalerts' . DS .'jmailalerts.xml';
   $match = 1;
   $jmaversion[]='2.4.5';
   
   if(JFile::exists($xml))
    {
  	  $xmldata = simplexml_load_file($xml); 
	  $obj = $xmldata->version;
	  
	        foreach ( (array) $obj as $index => $node )
			{		
		    	$jmaversion[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
			}
	
	$lim = '/2.4.5/';
	$match =preg_match($lim,$jmaversion[0],$matches,PREG_OFFSET_CAPTURE);
    }
			
	//alert typetable
	$typeqry= "CREATE TABLE IF NOT EXISTS `#__email_alert_type` (
  `id` int(11) NOT NULL auto_increment,
  `alert_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `allow_user_select_plugin` tinyint(1) NOT NULL,
  `allowed_freq` varchar(255) NOT NULL,
  `default_freq` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `isdefault` tinyint(1) NOT NULL DEFAULT 0,
  `email_subject` varchar(255) NOT NULL,
  `template` text NOT NULL,
  `template_css` text NOT NULL,
  `respect_last_email_date` tinyint(1) NOT NULL,
   PRIMARY KEY  (`id`)
)  ENGINE=MyISAM DEFAULT CHARSET=utf8;";

    $db->setQuery($typeqry);
    $db->query();
	
    //check old version and do require changes     
    $chk_version = '2.4.5'; 
    $jmaversion[0] =substr($jmaversion[0],0,5);
    $val =strcmp($jmaversion[0],$chk_version);
		//if($val == 0 or $val == -2 and $jmaversion[0] != '2.4.5' and $jmaversion[0] != '2.4.4')
	  if($val == -1 and $jmaversion[0] != '2.4.4' and $jmaversion[0] == '2.4.1')
	   {
		include($filename);
    	$subject=$emails_config['message_subject'];
    	$emailbody=$emails_config['message_body'];
    	$last_email=$emails_config['enb_latest'];
    	$tmp_css="body{background-color: #626262;}
								.rounded{margin: 0;padding: 0;line-height: 8px;}
								#content .subTitle{font-size: 13px;}
								a,a:link,a:visited {color: #336699;font-weight: normal;text-decoration: underline;}";
								
		//default frequency	start
		$qry= "SELECT `option` FROM #__email_alert_Default";
		$db->setQuery($qry);
		$defaltfrq= $db->loadResult();
		
		if(!$defaltfrq)
		{
			$defaltfrq = 1;
		}
			
		$insertqry="INSERT INTO #__email_alert_type(id,alert_name,description,allow_user_select_plugin,allowed_freq,default_freq,email_subject,template,template_css,	respect_last_email_date) ";
								
		$insertqry.=" VALUES(1,'alert type 1' ,'Description goes here',1, '1,7,15,30',$defaltfrq,'{$subject}','{$emailbody}','$tmp_css',0)";	
		//die('here');				
	    $db->setQuery($insertqry);
      	$db->query();
      
		//alter table default
		// $altqry[]="ALTER TABLE `#__email_alert_Default` CHANGE `plugins` `plugins` TEXT NOT NULL" ;
		$altqry[]="ALTER TABLE `#__email_alert_Default` DROP `option`" ;
		$altqry[]="ALTER TABLE `#__email_alert_Default` DROP `plugins`" ;
		$altqry[]="ALTER TABLE `#__email_alert_Default` ADD `alert_id` VARCHAR( 255 ) NOT NULL AFTER `id`";
		$altqry[]="ALTER TABLE `#__email_alert` DROP INDEX user_id";
		$altqry[]="ALTER TABLE `#__email_alert` ADD `alert_id` INT NOT NULL AFTER `user_id`";
		$altqry[]="UPDATE `#__email_alert` SET `alert_id` =1 WHERE id > 0";
		$altqry[]="UPDATE `#__email_alert_Default` SET `alert_id` =1 WHERE id = 0";

		for($i=0;$i<count($altqry);$i++)
		{
			 $db->setQuery($altqry[$i]);
			 $db->query();
		}
		  //drop table for new version
		/*$query = " DROP TABLE #__email_alert_Default ";
		$db->setQuery($query);
		$db->query();*/
	  
       }
    	//get data from table and update db table   
    	if($val == -1 and $jmaversion[0] != '2.4.4' and JFile::exists($xml))
		{
    	$exisitqry="SELECT COUNT(*) FROM `#__email_alert_Default` ";
    	$db->setQuery($exisitqry);
    	$db->query();
   
		$exitcolumn= $db->query();
    	}
    else
    { 
	    $exitcolumn = 0;
	} 		
    
    
    if($exitcolumn)
    {
    				
			//update query
		/*	$updateqry="UPDATE  `#__email_alert` SET `alert_id` =1 WHERE id > 0";
			$db->setQuery($updateqry);
			$db->query();
		*/	
			//alert table------/*/*/* alter table jos_easyblog_acl add priority tinyint(3) default 1 after ordering
			
		//add new coloumn in #__email_alert_type table after defaoult_freq for replace of email_alert_Default table
		
		$query= "SELECT d.alert_id FROM #__email_alert_Default AS d  WHERE d.id=0";
		$db->setQuery($query);
		$alertid = $db->loadResult();
		$default = explode(',',$alertid);
		
		$query= "SELECT t.id FROM #__email_alert_type AS t ";
		$db->setQuery($query);
		$alerts = $db->loadResultArray();
		
        //used to avoid duplicate error 
		if($jmaversion[0] == '2.4.3' or $jmaversion[0] == '2.4.2')
		{
		$db->setQuery("ALTER TABLE #__email_alert_type add published TINYINT(3) default 0 AFTER default_freq ");
		$db->query();
		$db->setQuery("ALTER TABLE #__email_alert_type add isdefault TINYINT(3) default 0 AFTER published ");
		$db->query();
	     }
	     
		for($i=0;$i<count($alerts);$i++)
		{			
			 if(in_array($alerts[$i],$default) AND $default[0] !='')
			 {
			   $db->setQuery("UPDATE #__email_alert_type AS et SET et.published = 1 WHERE et.id = ".$alerts[$i]." ");
			   $db->query();
			   $db->setQuery("UPDATE #__email_alert_type AS et SET et.isdefault = 1 WHERE et.id =".$alerts[$i]." ");
			   $db->query();
		     }
		     else
		     {
			   $db->setQuery("UPDATE #__email_alert_type AS et SET et.published = 1 WHERE et.id = ".$alerts[$i]." ");
			   $db->query();
			   $db->setQuery("UPDATE #__email_alert_type AS et SET et.isdefault = 0 WHERE et.id = ".$alerts[$i]." ");
			   $db->query();
			 }
			 
		}
		//remove table #__email_alert_Default
		$query = " DROP TABLE #__email_alert_Default ";
		$db->setQuery($query);
		$db->query();
			
    }
    /*
    if(!JFile::exists($filename))//if config file does not exists
    {
        JFile::move($filename_default,$filename);
        include($filename);
        $emails_config_new=array();
        $emails_config_new['private_key_cronjob']	= md5(time().JURI::root());
        $emails_config_new['enb_debug']		        = $emails_config["enb_debug"];
        //$emails_config_new['enb_daily']		        = $emails_config["enb_daily"];
        //$emails_config_new['enb_latest']		    = $emails_config["enb_latest"];
        $emails_config_new['enb_batch']		        = $emails_config["enb_batch"];
        $emails_config_new['inviter_percent']		= $emails_config["inviter_percent"];
        $emails_config_new['intro_msg']	            = trim($emails_config["intro_msg"]);
        

        //$emails_config_new['message_subject']	    = $emails_config["message_subject"];
        //$emails_config_new['message_body']	        = $emails_config["message_body"];
    
        $emails_config_file_contents="<?php \n\n";
        $emails_config_file_contents.="\$emails_config=array(\n".row2text($emails_config_new)."\n);\n";
        $emails_config_file_contents.="\n?>";
        JFile::delete($filename);
        JFile::write($filename,$emails_config_file_contents);
    }
    elseif(JFile::exists($filename_default))//if config file exists
    {
        JFile::delete($filename_default);
    }

    return true;
    */
    /*
    if(!JFile::exists($filename))//if config file does not exists
    {
        JFile::move($filename_default,$filename);
    }
    else//if old config file exists
    {
        include($filename);
        $emails_config_new=array();
        $emails_config_new['private_key_cronjob']	= md5(time().JURI::root());
        $emails_config_new['enb_debug']		        = $emails_config["enb_debug"];
        //$emails_config_new['enb_daily']		        = $emails_config["enb_daily"];
        //$emails_config_new['enb_latest']		    = $emails_config["enb_latest"];
        $emails_config_new['enb_batch']		        = $emails_config["enb_batch"];
        $emails_config_new['inviter_percent']		= $emails_config["inviter_percent"];
        $emails_config_new['intro_msg']	            = trim($emails_config["intro_msg"]);
        
        
		if (array_key_exists('jstoolbar', $emails_config)) {
			$emails_config_new['jstoolbar']	        = trim($emails_config["jstoolbar"]);
		}else{
			$emails_config_new['jstoolbar']	= 0;
		}
        
        //$emails_config_new['message_subject']	    = $emails_config["message_subject"];
        //$emails_config_new['message_body']	        = $emails_config["message_body"];
    
        $emails_config_file_contents="<?php \n\n";
        $emails_config_file_contents.="\$emails_config=array(\n".row2text($emails_config_new)."\n);\n";
        $emails_config_file_contents.="\n?>";

        JFile::delete($filename);
        JFile::write($filename,$emails_config_file_contents);
        JFile::delete($filename_default);
    }
    */
    if(!JFile::exists($filename))//if config file does not exists
    {
        JFile::move($filename_default,$filename);
        include($filename);
        $emails_config_new=array();
        $emails_config_new['private_key_cronjob']	= md5(time().JURI::root());
        $emails_config_new['enb_debug']		        = $emails_config["enb_debug"];
        //$emails_config_new['enb_daily']		        = $emails_config["enb_daily"];
        //$emails_config_new['enb_latest']		    = $emails_config["enb_latest"];
        $emails_config_new['enb_batch']		        = $emails_config["enb_batch"];
        $emails_config_new['inviter_percent']		= $emails_config["inviter_percent"];
        $emails_config_new['intro_msg']	            = trim($emails_config["intro_msg"]);
        
        if (array_key_exists('jstoolbar', $emails_config)) {
			$emails_config_new['jstoolbar']	        = trim($emails_config["jstoolbar"]);
		}else{
			$emails_config_new['jstoolbar']	= 0;
		}

        //$emails_config_new['message_subject']	    = $emails_config["message_subject"];
        //$emails_config_new['message_body']	        = $emails_config["message_body"];
    
        $emails_config_file_contents="<?php \n\n";
        $emails_config_file_contents.="\$emails_config=array(\n".row2text($emails_config_new)."\n);\n";
        $emails_config_file_contents.="\n?>";
        JFile::delete($filename);
        JFile::write($filename,$emails_config_file_contents);
    }
    elseif(JFile::exists($filename_default))//if config file exists
    {
        JFile::delete($filename_default);
    }
    return true;
}
