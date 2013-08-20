<?php
/*
 * @package J!MailALerts
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link    http://www.techjoomla.com
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.form.form' );

/*
 * class will contain function to store alerts records
 */
class jmailalertsModelEmails extends JModel
{
    var $_log = null;

    /*
     	Function for retun alert frequency select box checking allowd frequency , default_freq
     */

    function getFreq($altid)
    {
       $user=JFactory::getUser();
    	$query="SELECT alert_name ,allowed_freq,default_freq ,allow_user_select_plugin,description FROM #__email_alert_type WHERE id= $altid";
    	$this->_db->setQuery($query);
    	$resultfrq= $this->_db->loadObjectList();  
    	$allow_frq = $resultfrq[0]->allowed_freq;
    	$allow_frq = explode(',',$allow_frq);
    	
 
    	for($i=0 ;$i<count($allow_frq);$i++)
    	{
    	 
    	  if($allow_frq[$i] == 1)
    	  $rows[] =  array('value'=>'1','text'=>JText::_('FREQUENCY_DAILY'));
     	  elseif($allow_frq[$i] == 7)
    	  $rows[] = array('value'=>'7','text'=>JText::_('FREQUENCY_WEEKLY'));
    	  elseif($allow_frq[$i] == 15)
    	  $rows[] =array('value'=>'15','text'=>JText::_('FREQUENCY_FORTNIGHTLY'));
    	  elseif($allow_frq[$i] == 30)
    	  $rows[] = array('value'=>'30','text'=>JText::_('FREQUENCY_MONTHLY'));
    	
    	}
    	
    	
    	$query ="SELECT `option` FROM #__email_alert WHERE alert_id = $altid AND user_id = $user->id";
    	$this->_db->setQuery($query);
    	$uerselfrq= $this->_db->loadResult();
    	if($uerselfrq)
    	{
    	   $default_freq = $uerselfrq; 	
    	}
    	else
    	{
    	   $default_freq = $resultfrq[0]->default_freq;
    	}
    	
			 $setfrqname = 'c'.$altid;
			$alertfrqdta[]= JHTML::_("select.genericlist", $rows, "$setfrqname", "class='inputbox' ","value", "text",$default_freq);
			$alertfrqdta[] = $resultfrq[0]->alert_name;
			$alertfrqdta[] = $resultfrq[0]->description;
			$alertfrqdta[] = $resultfrq[0]->allow_user_select_plugin;
			return $alertfrqdta;
    }
    
 
        /*
     *function to save alter day's
     *Function called from the controller.php file.
     *This function is called when the user saves the email preferences(Daily, monthly, weekly, etc) from the frontend
     *
     */
    function savePref()
    {
        $mainframe=JFactory::getApplication();
        $user_freq=JRequest::getVar('c');
        $unsubscribe_chk_box_status = JRequest::getVar('unsubscribe_chk_box');
        $my=&JFactory::getUser();

        if($unsubscribe_chk_box_status){
            $user_freq = 0;
        }
        $post=JRequest::get('post');
        //print_r($post);
        $alt=JRequest::getVar('alt');
        //echo  "count".count(JRequest::getVar('ch8'));die;
         
         
        //get all alert id
        $query= "SELECT id FROM #__email_alert_type";
        $this->_db->setQuery($query);
        $delaltuser=$this->_db->loadResultArray();
        

        for($i=0;$i<count($delaltuser);$i++)
        {
            if(!in_array( $delaltuser[$i],$alt))
            {
                $delalt[] = $delaltuser[$i];

            }
        }
    
        
        //query construct for delete
        //$tmpdel=NULL;
        for($i=0;$i<count($delalt);$i++)
        {
            $tmpdel.=" `alert_id` =".$delalt[$i];
            if($i != (count($delalt) - 1))
            $tmpdel.=" OR ";
        }
        if($tmpdel)
     	   $tmpdel= "(".$tmpdel.") ";
        if($tmpdel !="")
        {
  			//changed in 2.4.3
  			//$delquery = " DELETE FROM #__email_alert WHERE user_id = {$my->id} AND $tmpdel";
           	$delquery = "UPDATE `#__email_alert` SET `option`=0 WHERE `user_id`={$my->id} AND $tmpdel";
            $this->_db->setQuery($delquery);
            $this->_db->query();
        }
        
        for($i=0;$i<count($alt);$i++)
        {

             
            $db_plugentry="";
            if(isset($post['alt']))
            {
                $tmp = 'ch'.$alt[$i];

                if(count(JRequest::getVar($tmp))!= 0)
                $user_set_plug=array_values($post["$tmp"]);

                $plug_name=array_keys($post);
                 

                //Code for converting the plugin params to store in the database
                foreach ($plug_name as $plug_name)
                {
                    if(count(JRequest::getVar($tmp))!= 0)
                    {
                        if(in_array($plug_name, $user_set_plug))
                        {
                            foreach($post[$plug_name] as $key=>$val)
                            {
                                if(is_array($val)){
                                    $val=implode(',',$val);
                                }
                                $db_plugentry.=$plug_name.'|'.$key.'='.$val."\n";
                            }
                        }
                    }

                }

            }

             
            else {

                $db_plugentry=" ";//space is important
            }

             
            $db_plugentry= str_replace ("_$alt[$i]", "" , $db_plugentry);

            $today=date('Y-m-d H:i:s');
            $query="SELECT `option` FROM #__email_alert WHERE user_id=".$my->id." AND alert_id = $alt[$i]";
            $this->_db->setQuery($query);
            $result=$this->_db->loadResult();

             

            if($result==null)
            {

                if($db_plugentry == ''){
                    $db_plugentry='';
                }
                else
                {
                   $query="INSERT INTO `#__email_alert`(`user_id`,`alert_id`,`option`,`date`,`plugins_subscribed_to`) VALUES (".$this->_db->Quote($my->id).",".$this->_db->Quote($alt[$i]).",".JRequest::getVar("c$alt[$i]").",".$this->_db->Quote($today).",".$this->_db->Quote($db_plugentry).")";
                }
            }
            else
            {
                $update_query_string='';
                if($db_plugentry!=''){
                    $update_query_string = ", `plugins_subscribed_to` =".$this->_db->Quote($db_plugentry);

                    $query = "UPDATE `#__email_alert` SET `option` = ".JRequest::getVar("c$alt[$i]")."".$update_query_string." WHERE `user_id` = {$my->id} "."AND `alert_id` = $alt[$i]";

                }
                else
                {
                    $query = "DELETE FROM #__email_alert WHERE `alert_id` = $alt[$i] AND `user_id` = {$my->id}";
                     

                }

            }

            //echo $query;
             
            $this->_db->setQuery($query);
            $this->_db->query();

            // if(!$this->_db->query())
            //echo $this->_db->getErrorMsg();


             
        }//for($i=0;$i<count($alt);$i++)
        //die;
        //  if($this->_db->query())
        //{
        $msg="<div class='messa' style='font-size:14px;font-weight:bold;'>".JText::_('SETTINGS_SAVED_SUCCESSFULLY')."</div>";
        $itemid=jmailalertsModelEmails::getItemid();
        //$mainframe->redirect(JRoute::_(JURI::base().'index.php?option=com_jmailalerts&Itemid='.$itemid),$msg);
        $mainframe->redirect(JRoute::_('index.php?option=com_jmailalerts&view=emails&Itemid='.$itemid),$msg);
        //}
    }//savePref() ends

    /*
     *
     *
     */
    function getItemid()
    {
        $this->_db->setQuery('SELECT id FROM #__menu WHERE link LIKE "%com_jmailalerts%"');
        $item_id = $this->_db->loadResult();
        return $item_id;
    }
    /*
     * Function to send mails
     * Since this function sends emails, theres a logging code added to log the info abt email sending
     * i.e. timestamp, recipient-info, failure/success of email sending.
     *
     */
    function processMailAlerts()
    {
        jimport('joomla.filesystem.file');
        $logfile_path=JPATH_ADMINISTRATOR.DS."components".DS."com_jmailalerts".DS."log.txt";
        require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");
        $numberofmails=$emails_config["inviter_percent"];
        $enable_batch=$emails_config["enb_batch"];
        $pkey=JREQUEST::getVar('pkey','');
        $today=date('Y-m-d H:i:s');

        $this->log[]='';
        $this->log[]=JText::sprintf("START", $today);

        if($pkey!=$emails_config["private_key_cronjob"]){
            $this->log[]=JText::_("NOT_AUTHO");
        }
        else
        {
            //$msg_body=stripslashes($emails_config["message_body"]);
            $skip_tags=array('[SITENAME]','[NAME]','[SITELINK]','[PREFRENCES]', '[mailuser]');
            //get all tags  from the template with whitespace as it is
           //// $remember_tags=$this->get_original_tmpl_tags($msg_body,$skip_tags);
            //get all tags  from the template removing whitespace in a correct needed array format
           //// $tmpl_tags=$this->get_tmpl_tags($msg_body,$skip_tags);
           
			 
			 $query="SELECT  u.id,u.name,u.email,e.date,e.plugins_subscribed_to,e.alert_id,a.template,a.template_css, a.email_subject ,a.respect_last_email_date
			 FROM #__users AS u, #__email_alert AS e ,#__email_alert_type as a 
			 WHERE u.id = e.user_id
			 AND u.block =0 
			 AND e.option > 0
			 AND e.alert_id = a.id
			 AND a.published=1
			 AND (DATEDIFF(CURDATE(), e.date) >= e.option OR e.date = '0000-00-00 00:00:00')";

            if($enable_batch)//if batch processing is enabled
            {
                $query.=" LIMIT {$numberofmails}";
                $this->log[]=JText::sprintf("SEN_BAT",$numberofmails);
                //$this->log[]=JText::_("User query-").$query;//logger
            }
            $this->_db->setQuery($query);
            $email_users=$this->_db->loadObjectList();
            $this->log[]=JText::_("LOK_USER");

            if($email_users)
            {
                $user_count = count($email_users);
                $this->log[] = JText::sprintf("FOUND_TO_PRO", $user_count);
                $old_log_content=JFile::read($logfile_path);
                if($old_log_content||$old_log_content=='')
                {
                    $file_log = implode("\n",  $this->log);
                    $file_log = $old_log_content ."\n".$file_log ;
                    JFile::write($logfile_path,$file_log);
                }
                echo implode('<br/>',$this->log);
                unset($this->log);
                $this->log[]='';
                
                $send_mail=0;
                $send_no_mail=0;
                foreach($email_users as $email_user)
                {
                    $remember_tags=$this->get_original_tmpl_tags($email_user->template,$skip_tags);
            
            				$tmpl_tags=$this->get_tmpl_tags($email_user->template,$skip_tags);
                    
                    $val = $this->getMailcontent($email_user,2,$tmpl_tags,$remember_tags);
                    if($val == 1){
                        $send_mail++;
                    }
                    elseif($val == 3)
                    {
                        $this->log[] = JText::sprintf("MAIL_SEND_FAIL", $email_user->name, $email_user->id);
                        $send_no_mail++;
                    }
                }
                $this->log[] = JText::sprintf("PRO_OUT_OF", $send_mail, $user_count);
            }
            else{
                $this->log[] = JText::_("NO_USER");
            }
            $this->log[] = JText::_("FINSH");
        }

        echo implode('<br/>',$this->log);
        $old_log_content=JFile::read($logfile_path);
        if($old_log_content||$old_log_content=='')
        {
            $file_log = implode("\n",  $this->log);
            $file_log = $old_log_content ."\n".$file_log ;
            JFile::write($logfile_path,$file_log);
        }
    }//processMailAlerts() ends

    /*
     * Function to actually send the content of the mail
     * It is called by the function processMailAlerts() above and also from the backend model simulate
     *
     */
    function getMailcontent($userdata, $flag,$tmpl_tags,$remember_tags)
    {
        $logfile_path =  JPATH_ADMINISTRATOR.DS."components".DS."com_jmailalerts".DS."log.txt";
        require(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."config.php");
        $today=date('Y-m-d H:i:s');
        if(!empty($userdata))
        {
            $app=&JFactory::getApplication();
            $frommail=$app->getCfg('mailfrom');
            $site=$app->getCfg('sitename');
           //$message_body=stripslashes($emails_config["message_body"]);
            $message_body= stripslashes($userdata->template);
       
            //$message_subject=stripslashes($emails_config["message_subject"]);
            $message_subject=stripslashes($userdata->email_subject);
            $emailofuser=trim($userdata->email);
            $user_plug = jmailalertsModelEmails::getUserPlugData($userdata->plugins_subscribed_to);
            $final_trigger_tags=jmailalertsModelEmails::get_final_trigger_tags($tmpl_tags,$user_plug,$userdata->id);
            $count=0;
            foreach($final_trigger_tags as $ftt)
            {
                if(isset($ftt['plug_trigger'])){
                   // $user_subscribed_array[$count][0]=$ftt['plug_trigger'];
                    $usa[$count]=$ftt['plug_trigger'];;//needed for log
                }
                //$user_subscribed_array[$count][1]=$ftt['tag_to_replace'];
                $count++;
            }
            /*if($emails_config["enb_debug"] && $flag == 2 )	//if verbose debug is ON
            {
                $this->log[] = JText::sprintf("PRO_FOR",$userdata->name,$userdata->id);
                $plug=implode(',',$usa);
                $this->log[] = JText::sprintf("APPLICABLE_PLUG",$plug);
            }*/
            if($emails_config["enb_debug"] && $flag == 2 )	//if verbose debug is ON
            {
                $this->log[] = "*** ".JText::sprintf("PRO_FOR",$userdata->name,$userdata->id);
                if(count($usa)){
                    $plug=implode(',',$usa);
                    $this->log[] = JText::sprintf("APPLICABLE_PLUG",$plug);
                }else{
                    $this->log[] = JText::sprintf("APPLICABLE_PLUG","No applicable plugin found");//@TODO add lang. string 
                }
                    
            }
            
            
           // $plugins_data=jmailalertsModelEmails::getPlugins($userdata->id, $userdata->date, $final_trigger_tags, $emails_config["enb_latest"]);
             if(isset($userdata->respect_last_email_date)){$respect_last_email_date=$userdata->respect_last_email_date;} 
            else{$respect_last_email_date = 0;}
            $plugins_data=jmailalertsModelEmails::getPlugins($userdata->id, $userdata->date, $final_trigger_tags,$respect_last_email_date);
           /* foreach ($plugins_data as $pd){
                $plugins_data_name[]=$pd[0];
            }*/
            
            //rebuild array for tag repalcement in the same order as the plugins were triggered
            $count=0;
            $user_subscribed_array=array();
            if($plugins_data)
            {
                foreach($plugins_data as $pd)
                {
                    $plugins_data_name[]=$pd[0];
                    if(isset($pd[0])){
                        $user_subscribed_array[$count][0]=$pd[0];//plug_trigger
                    }
                    if(isset($pd[3])){
                        $user_subscribed_array[$count][1]=$pd[3];//tag_to_replace
                    }
                    $count++;
                }
            }
            
            $sitelink 		="<a href = '".JURI::root()."'>".JText::_("CLICK")."</a>";
            $pref_sitelink 	='<a href="'.JURI::root().'index.php?option=com_jmailalerts&amp;view=emails">'.JText::_("CLICK").'</a>';
            $find 			=array ('[SITENAME]','[NAME]','[SITELINK]','[PREFRENCES]', '[mailuser]');
            $replace 		=array($site,$userdata->name,$sitelink,$pref_sitelink,$emailofuser);
            $message_body	=str_replace($find, $replace, $message_body);
            $message_subject=str_replace( '[SITENAME]', $site, $message_subject );
            $no_mail = 0;
            $cssdata = '';
            $i=0;
            foreach($user_subscribed_array as $plug)
            {
                if(isset($plugins_data[$i]))
                {
                    $message_body=str_replace($plug[1],$plugins_data[$i][1], $message_body);
                    $cssdata .= $plugins_data[$i][2];
                    if(!($plugins_data[$i][1] == '')){
                        $no_mail = 1;
                    }
                }
                $i++;
            }
            //replace all tags that are not part of user preferences directly with ''
            //@TODO need to take care of when processing special plugins
            foreach($remember_tags as $rt)
            {
                $message_body=str_replace($rt,'', $message_body);
            }
            if(!($no_mail == 0))
            {
                //separated CSS in 2.4
                //$cssfile = JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."mail_alert_style.css";
                //$cssfile = JPATH_SITE.DS."components".DS."com_jmailalerts".DS."emails".DS."default_template.css";
                //$cssdata .= JFile::read($cssfile);
                $cssdata .= $userdata->template_css;
                $common_plugin_css_file = JPATH_SITE.DS."components".DS."com_jmailalerts".DS."css".DS."common_plugin.css";
                $cssdata .= JFile::read($common_plugin_css_file);
                
                $mail_data = jmailalertsModelEmails::getEmogrify($message_body,$cssdata);
                //flag=1 => mail simulation
                if($flag == 1){
                    echo $mail_data ;
                    jexit();
                }
                //send email
                $status=JUtility::sendMail($frommail,$site,$emailofuser,$message_subject,$mail_data,true);//2.4
                if(isset($status->code) && $status->code==500)
                {
                    $this->log[] = $status->message." ".JText::sprintf("MAIL_SEND_FAILED", $emailofuser, $today);
                    return 4;
                }
                elseif($status)
                {
                    $this->log[] = JText::sprintf("MAIL_SEND_SUCCESS", $emailofuser, $today);
                    //flag=2 => actual sending of email
                    if($flag == 2)
                    {
                        $query = "UPDATE `#__email_alert` SET `date` ='$today' WHERE `user_id` = $userdata->id AND alert_id = $userdata->alert_id";
                        $this->_db->setQuery($query);
                        $this->_db->query();
                    }
                    return 1;
                }
            }
            else{//when there is no content to send in the mail
            
            		//flag=2 => actual sending of email
                if($flag == 2)
                {
                    $query = "UPDATE `#__email_alert` SET `date` ='$today' WHERE `user_id` = $userdata->id AND alert_id = $userdata->alert_id";
                    $this->_db->setQuery($query);
                    $this->_db->query();
                }
                
                
                return 3;
            }
        }


    }//getMailcontent() ends

    /*
     Function to call plugins with array of type [param_name]=>param_value and return the output

     This function is called from the get_data() function above
     */
    function getPlugins($userid,$last_email_date,$final_plugin_params_data,$latest)
    {
        $results=array();
        $i=0;
        $special_plugins=array();
        $count=0;
        $flag=0;//important
        $aresults = array();
        foreach($final_plugin_params_data as $plug)
        {
            if(isset($plug['plug_trigger']))//check if plugin is to be triggered
            {
                if(isset($plug['is_special']))//check if pluign is special
                {
                    //if yes add it in new array to process after proceessing normal plugins
                    $special_plugins[$count]=$plug;
                    $count++;
                }
                else//normal plugin
                {    
                    $dispatcher=&JDispatcher::getInstance();
                    $plug['plug_trigger'];
                    JPluginHelper::importPlugin('emailalerts',$plug['plug_trigger']);
                    //triger the plugins
                    //parameters passed are userid,last email date,final plugin trigger data,fetch only latest
                    $results=$dispatcher->trigger('onEmail_'.$plug['plug_trigger'],array($userid,$last_email_date,$plug,$latest));
                    if($results)
                    {
                        if(!$flag && $results[0][1]!=''){
                            //set flag even if a result is outputted by any of the normal plugin
                            $flag=1;
                        }
                        $results[0][]=$plug['tag_to_replace'];
                        $aresults[$i] = $results[0];
                        $i++;
                    }
                }
            }
        }
        if($flag)//if content is outputted by normal plugins
        {
            foreach($special_plugins as $plug)
            {
                if(isset($plug['plug_trigger']))
                {
                    $dispatcher = &JDispatcher::getInstance();
                    JPluginHelper::importPlugin('emailalerts',$plug['plug_trigger']);
                    //triger the plugins
                    //parameters passed are userid,last email date,final plugin trigger data,fetch only latest
                    $plug['plug_trigger'];
                    $results = $dispatcher->trigger( 'onEmail_'.$plug['plug_trigger'],array($userid,$last_email_date,$plug,$latest));
                    if($results)
                    {
                        $results[0][]=$plug['tag_to_replace'];
                        $aresults[$i] = $results[0];
                        $i++;
                    }
                }
            }
        }
        return $aresults;
    }//getPlugins() ends

    /*
     Function to get the default alert user selected alerts or default alerts
     */
     
     function defaultalertid()
     {
     
		/*changed in 2.4.3*/
		/*
		$user=JFactory::getUser();
		$query = "SELECT alert_id FROM #__email_alert WHERE user_id = ".$user->id;
		$this->_db->setQuery($query);
		$option=$this->_db->loadResultArray();
		if(!$option)
		{
			//Get the options from default configuration

				$query="SELECT  alert_id  FROM #__email_alert_Default";
				$this->_db->setQuery($query);
			  	$option=$this->_db->loadResult();
			   	$option = explode(',',$option);
		   
		  }
		return $option;
		*/
		
		$user=JFactory::getUser();
        $query = "SELECT `alert_id`,`option` FROM `#__email_alert` WHERE `user_id` = ".$user->id." AND `option`>0";
        $this->_db->setQuery($query);
        $temp_data=$this->_db->loadObjectList();
        
        $option=array();
	    foreach($temp_data as $td)
	    {
	        $opt['option']=$td->option;
	        $option[$td->alert_id]=$opt;
	    }
        if(!$option)
        {
            //Get the options from default configuration
            //$query="SELECT alert_id  FROM #__email_alert_Default";
            
            $query="SELECT id  FROM #__email_alert_type WHERE isdefault = 1 AND published=1 "; 
            $this->_db->setQuery($query);
            $temp_data=$this->_db->loadResultArray();
            //$temp_data = explode(',',$temp_data);
            $option=array();
		    foreach($temp_data as $td)
		    {
	            $opt['option']=0;
	            $option[$td]=$opt;
		    }
        }
        return $option;
    //o/p
    //Array ( [2] => Array ( [option] => 0 ) [3] => Array ( [option] => 0 ) ) in site model jma    
    //Array ( [2] => Array ( [option] => 0 ) [3] => Array ( [option] => 0 ) [4] => Array ( [option] => 0 ) [5] => Array ( [option] => 0 ) ) in site model jma
     }
     
      /*
     Function for checking user default alert id or not
     */
     function isdefaultset()
     {
     	$user=JFactory::getUser();
  	$query = "SELECT alert_id FROM #__email_alert WHERE user_id = ".$user->id;
        $this->_db->setQuery($query);
        $option=$this->_db->loadResultArray();
        if(!$option)
        {
        	$default_setting = 1;
        }
        else
        {
        	$default_setting = "";
        }
        return $default_setting;
     }
     
     
     
     
     /*
     	Function for retun all alert id
     */
     
     function alertid()
     {
     	$query = "SELECT id FROM #__email_alert_type WHERE published=1";
        $this->_db->setQuery($query);
        $altid=$this->_db->loadResultArray();
        return $altid;
     }
     
     /*
     	Function for retun no of alerts
     */
     
     function countalert()
     {
     		$query = "SELECT count( * )FROM `#__email_alert_type` WHERE published=1";
		      $this->_db->setQuery($query);
		      $altid=$this->_db->loadResult();
		      return $altid;  
     
     }
      
    
    
   /*
     	Function for return query concat 
     */ 
     
     function alertqryconcat()
     {
     if(JVERSION < '1.6.0')
     	$this->_db->setQuery('SELECT name, element,params FROM #__plugins WHERE folder=\'emailalerts\' AND published = 1');
     	else
     	$this->_db->setQuery('SELECT name, element,params FROM #__extensions WHERE folder=\'emailalerts\' AND enabled = 1');
     	
			$test= $this->_db->loadObjectList();//return the plugin data array
			//var_dump( $test);
			//tring to get elements
			$query="SELECT template FROM #__email_alert_type WHERE published=1";
			$this->_db->setQuery($query);

		 	$test2= $this->_db->loadObjectList();//return the plugin data array

			//get the plugin name first
			if(JVERSION < '1.6.0')
					 	$this->_db->setQuery('SELECT element FROM #__plugins WHERE folder = \'emailalerts\'  AND published = 1');
					else
						$this->_db->setQuery('SELECT element FROM #__extensions WHERE folder = \'emailalerts\'  AND enabled = 1');
					   $plugnamecompair = $this->_db->loadResultArray();//Get the plugin names and store in an array
					//return the array 
				
		
					foreach($test2 as $key)
					{
						for($i=0;$i<count($plugnamecompair);$i++)
												{
										if (strstr($key->template,$plugnamecompair[$i]))
										$plugin_name_string[] =$plugnamecompair[$i];
													
												}
									$tmp="";
						 for($i=0;$i<count($plugin_name_string);$i++)
						 {
						 	$tmp.=" element LIKE '".$plugin_name_string[$i]."' ";
									if($i != (count($plugin_name_string) - 1))
									$tmp.=" OR ";
						 	
						 
						 }   
						 $qry_concat[] =  $tmp;  		
						unset($plugin_name_string);
	
					}	
	  	return $qry_concat;
     
    }
     
      
    function getData($aid)
    {
        
        $option=array();
        $user=JFactory::getUser();
        if($user->id)
        {
            //Get the option saved related to the user-id from the email_alert table
            if($aid != "")
            {
            $query = "SELECT `option`,`plugins_subscribed_to` FROM #__email_alert WHERE user_id = ".$user->id ." AND alert_id = $aid";
            $this->_db->setQuery($query);
            $option=$this->_db->loadRow();
            }
        
             
            if($option[1]!='')
            {
                //@TODO check function call
                $opt=$this->get_frontend_plugin_data($option[1]);
                if($opt){
		              foreach($opt as $kk=>$vv)
		              {
		                  foreach($vv as $k=>$v){
		                      $opt1[$kk][]=$k.'='.$v;
		                  }
		              }
                }
                if(isset($opt1)){
                    $option[1]=$opt1;
                }
            }
            if(!$option)
            {
                //just installed and not yet synced
                //@TODO needs test , chk function call
                $opt=$this->get_frontend_plugin_data("");
                //$opt=$this->getUserPlugData($option[1]);
                foreach($opt as $kk=>$vv)
                {
                    foreach($vv as $k=>$v){
                        if($kk=='jma_latestnews_js' && $k=='category')
                        $k ='catid';
                        
                        $opt1[$kk][]=$k.'='.$v;
                    }
                    
                }
                if(isset($opt1)){
                    $option[1]=$opt1;
                }
            }
            return $option[1];
        }//if ends
        
    }//getData() ends

    /*
     * Function will return the user settings for the plugins in format of plugin_name {[para_name1]=para_value1,[para_name2]=para_value2,..}
     * Function is called from the function getData() and from getMailcontent()
     *
     */
    function getUserPlugData($data)
    {
        $newline_plugins_array = array();
        $newline_plugins_array = explode("\n", $data);
        foreach ($newline_plugins_array as $line)
        {
            if (!trim($line)){
                continue;
            }
            $pcs=explode('|',$line);
            $userconfig=explode('=',$pcs[1]);
            $userdata[$pcs[0]][$userconfig[0]]=$userconfig[1];
        }
        $i=0;
        foreach($userdata as $key=>$u)
        {
            $u['plug_trigger']=$key;
            $u_plugs[$i]=$u;
            $i++;
        }
        return $u_plugs;
    }


    /*
     *
     * Function to get the inline css html code from the emogrifier
     *
     */
    function getEmogrify($html, $css)
    {
        require_once(JPATH_SITE.DS."components".DS."com_jmailalerts".DS."models".DS."emogrifier.php");
        if(!function_exists('mb_convert_encoding'))// condition to check if mbstring is enabled
        {
            echo JText::_("MB_EXT");
            return $html;
        }
        $emogr = new Emogrifier($html,$css);
        $html_css = $emogr->emogrify();
        return $html_css;
    }

     
    /*
     *
     * Function to get the plugin data(names, elements) related to emailalert
     *
     */
    function getPluginData($qry_concat)
    {
		JPluginHelper::importPlugin( 'emailalerts' );
		$dispatcher =& JDispatcher::getInstance();
		if(JVERSION < '1.6.0')
			$query= "SELECT name, element,params FROM #__plugins WHERE folder = 'emailalerts' AND  (".$qry_concat.") AND published = 1";
		else
			$query= "SELECT name, element,params FROM #__extensions WHERE folder = 'emailalerts' AND  (".$qry_concat.") AND enabled = 1";

		$this->_db->setQuery($query);
		$plugin_data = $this->_db->loadObjectList();

		if($plugin_data){
			return $plugin_data;
		}else{
			return false;
		}
    }//getPluginData() ends

    /*
     *
     * ///////////////////////////////////////////////////////
     * All fuctions below are added in 2.4 version by manoj
     * ///////////////////////////////////////////////////////
     *
     */
    function get_default_plugin_params_j15($plugin_name)
    {
        $plugin=JPluginHelper::getPlugin('emailalerts',$plugin_name); 
        if(!$plugin){
            return false;
        }
        $pluginParams=new JParameter( $plugin->params );
        $pluginParamsDefault=$pluginParams->_raw;
        $newlin=explode("\n",$pluginParamsDefault);
        $default_plugin_params=array();
        foreach($newlin as $v)
        {
            if(!empty($v))
            {
                $v=str_replace('|',',',$v);
                $v=explode("=",$v);
                $default_plugin_params[$v[0]]=$v[1];
            }
        }
        return $default_plugin_params;
    }

    function get_default_plugin_params_j16($plugin_name)
    {
        $query= "select params from #__extensions where element='".$plugin_name."' && folder='emailalerts'";
        $this->_db->setQuery($query);
        $plug_params=$this->_db->loadResult();
        if(!$plug_params){
            return false;
        }
        if(preg_match_all('/\[(.*?)\]/',$plug_params,$match))
        {
            foreach($match[1] as $mat)
            {
                $match=str_replace(',','|',$mat);
                $plug_params= str_replace($mat,$match,$plug_params);
            }
        }
        $newlin = explode(",",$plug_params);

        foreach($newlin as $v)
        {
            $entry="";
            if(!empty($v))
            {
                $v=str_replace('{','',$v);
                $v=str_replace(':','=',$v);
                $v=str_replace('"','',$v);
                $v=str_replace('}','',$v);
                $v=str_replace('[','',$v);
                $v=str_replace(']','',$v);
                $v=str_replace('|',',',$v);
                $v=explode("=",$v);
                $default_plugin_params[$v[0]]=$v[1];
            }
        }
        return $default_plugin_params;
    }
    /**
     *This functions returns an array of all tags from the JMA email-template with whitespace as it is
     *For example it can detect all tags like [jma_plugin_js|cat=1,2 | sec=5,  6, 8] or [jma_plugin_js|cat=1,2]
     *
     * @param string $data a string having user preferences as stored in email_alert table
     * @return array $final_frontend_userdata an array of user preferences as per his ACL for enabled plugins
     */

    function get_frontend_plugin_data($data)
    {
      $userdata=array();
        $newline_plugins_array=array();
        $newline_plugins_array=explode("\n", $data);
        
        $newline_plugins_array;
        foreach ($newline_plugins_array as $line)
        {
            if(!trim($line)){
                continue;
            }
            $pcs = explode('|', $line);
            $userconfig = explode('=', $pcs[1]);
            $userdata[$pcs[0]][$userconfig[0]] = $userconfig[1];
        }
        //var_dump($userdata);die;
        $user=JFactory::getUser();
        if(JVERSION < '1.6.0')
        {
            if($user->id){
                $access=$user->aid;
            }
            $query= "SELECT element FROM #__plugins
			WHERE folder='emailalerts' AND published=1 AND access <=".(int)$access;
        }
        else
        {
            //@TODO acl is remaining for 1.6 onwards
            $query= "SELECT element FROM #__extensions
			WHERE type='plugin' AND folder='emailalerts' AND enabled=1";//AND access <=".(int)$access;
        }
        $this->_db->setQuery($query);
        $data=$this->_db->loadObjectList();

        $temp_data=array();
        $final_frontend_userdata=array();
        if($data)
        {
            foreach($data as $d)
            {
                if($userdata)
                {
                    if(!array_key_exists($d->element,$userdata))
                    {
                    	
                    
                        if(JVERSION < '1.6.0')
                        {
                            $p=$this->get_default_plugin_params_j15($d->element);
                         
                            $p['checked']="''";
                           // print_r($p);
                            if(!(isset($p['is_special']) && $p['is_special'])){
                                $temp_data[$d->element]=$p;
                            }
                        }
                        else
                        {
                            $p=$this->get_default_plugin_params_j16($d->element);
                            $p['checked']="''";
                            if(!(isset($p['is_special']) && $p['is_special'])){
                                $temp_data[$d->element]=$p;
                            }
                        }
                    }//end if array_key_exists
                    else//if key exists
                    {
                        if(JVERSION < '1.6.0')
                        {
                            $p=$this->get_default_plugin_params_j15($d->element);
                            if(!(isset($p['is_special']) && $p['is_special'])){
                                $temp_data[$d->element]=$userdata[$d->element];
                            }
                        }
                        else
                        {
                            $p=$this->get_default_plugin_params_j16($d->element);
                            if(!(isset($p['is_special']) && $p['is_special'])){
                                $temp_data[$d->element]=$userdata[$d->element];
                            }
                        }
                    }
                }//end of userdara
                else//if not userdata
                {
                    if(JVERSION < '1.6.0')
                    {
                        $p=$this->get_default_plugin_params_j15($d->element);
                        $p['checked']="''";
                        if(!(isset($p['is_special']) && $p['is_special'])){
                            $temp_data[$d->element]=$p;
                        }
                    }
                    //@TODO needs testing
                    else
                    {
                        $p=$this->get_default_plugin_params_j16($d->element);
                        $p['checked']="''";
                        if(!(isset($p['is_special']) && $p['is_special'])){
                            $temp_data[$d->element]=$p;
                        }
                    }//end else
                }//end else if no userdata

            }
            //code below is added to show plugins at frontend according to ACL of corresponding user
            //$data has appropriate plugin names as per users' ACL
            //$final_frontend_userdata=array();
            foreach($data as $d)
            {
                if(array_key_exists($d->element,$temp_data)){
                    $final_frontend_userdata[$d->element]=$temp_data[$d->element];
                }
            }
        }
        return $final_frontend_userdata;
       
    }

    /**
     *This functions returns an array of all tags from the JMA email-template with whitespace as it is
     *For example it can detect all tags like [jma_plugin_js|cat=1,2 | sec=5,  6, 8] or [jma_plugin_js|cat=1,2]
     *
     * @param string $msg_body this is a JMA email template string
     * @param array $skip_tags this array contains all tags that are not related to any JMA plugin
     * @return array $remember_tags an array of all detected tags
     */
    function get_original_tmpl_tags($msg_body,$skip_tags)
    {
        //pattern for finding all tags with pipe EXAMPLE [jma_plug |cat=1,2| sec=5,  6, 8]
        $pattern ='/\[[A-Za-z_|=, ][A-Za-z_|=0-9, ]*\]/';
        preg_match_all($pattern,$msg_body,$matches);
        $count=0;
        $remember_tags=array();
        foreach($matches[0] as $match)
        {
            if(!in_array($match,$skip_tags))
            {
                $remember_tags[$count]=$match;
                $count++;
            }
        }
        return $remember_tags;
    }

    /**
     *This functions returns an array of all tags from the JMA email-template removing whitespace from tag names
     *For example it can detect all tags like [jma_plugin_js|cat=1,2 | sec=5,  6, 8] or [jma_plugin_js|cat=1,2]
     *
     * @param string $msg_body this is a JMA email template string
     * @param array $skip_tags this array contains all tags that are not related to any JMA plugin
     * @return array $final_tmpl_tags an array of all detected tags
     */
    function get_tmpl_tags($msg_body,$skip_tags)
    {
        //pattern find all tags with pipe EXAMPLE [jma_plug |cat=1,2| sec=5,  6, 8]
        $pattern ='/\[[A-Za-z_|=, ][A-Za-z_|=0-9, ]*\]/';
        preg_match_all($pattern,$msg_body,$matches);
        $tmpl_tags[]=array();
        $count=0;
        foreach($matches[0] as $match)
        {
            //remove whitespace from a tag name
            $tag=preg_replace('/\s+/','', $match);
            if(!in_array($match,$skip_tags))
            {
                $tmpl_tags[$count]=$tag;
                $count++;
            }
        }
        $tag_counter=0;
        $final_tmpl_tags=array();
        foreach($tmpl_tags as $tmpl_tag)
        {
            $tag_to_replace=$tmpl_tag;//important
            //remove square brackets [] from tags like [jma_news|count=6]
            $tmpl_tag=preg_replace('/(\[)|(\])/','',$tmpl_tag);
            //create array from strings like jma_news|count=6|sec=1,3,4
            $tag=explode('|',$tmpl_tag);
            if(count($tag)>1)//it's a data tag
            {
                //the first(actually 0th) element of array is the name of the plugin
                //we need to make an array with first element as plugin name AND paramaters as other array elements
                $temp_params=array();
                for($count=0;$count<count($tag);$count++)//start processing all params for a single plugin
                {
                    //create array from strings like catid=1,2,3
                    $single_param_array=explode('=',$tag[$count]);
                    //@TODO this for is unused
                    //for($ic=1;$ic<count($tag);$ic++) //$ic count is used to process $single_param_array
                    //{
                    if(count($single_param_array)>1){//example catid=1,2,3
                        $temp_params[$tag_counter][$single_param_array[0]]=$single_param_array[1];
                    }
                    else{ //example jma_latest_news
                        $temp_params[$tag_counter]['plug_trigger']=$single_param_array[0];
                    }
                    //}
                }//end of proceessing all params for a single tag

                $temp_params[$tag_counter]['tag_to_replace']=$tag_to_replace;
                $final_tmpl_tags[$tag_counter]=$temp_params[$tag_counter];
            }//end of if it is a data tag
            else//it is a normal tag
            {
                $temp_params=array();
                for($count=0;$count<count($tag);$count++)
                {
                    //create array from strings like count=6
                    $single_param_array=explode('=',$tag[$count]);
                    //@TODO this for is unused
                    //for($ic=0;$ic<count($tag);$ic++)
                    //{
                    if(count($single_param_array)>1){//example catid=1,2,3
                        $temp_params[$tag_counter][$single_param_array[0]]=$single_param_array[1];
                    }
                    else{//example jma_latest_news
                        $temp_params[$tag_counter]['plug_trigger']=$single_param_array[0];
                    }
                    //}
                }
                $temp_params[$tag_counter]['tag_to_replace']=$tag_to_replace;
                $final_tmpl_tags[$tag_counter]=$temp_params[$tag_counter];
            }
            $tag_counter++;
        }//end of foreach
        return $final_tmpl_tags;
    }

    /**
     *This functions returns an array of all tags each corresponding to one JMA plugin trigger
     *
     * @param array $tmpl_tags it is an array having all tags from email template along with corresponding paramters
     * @param array $user_plug  it is an array having all tags from user preferences along with corresponding paramters
     * @param int $uid the user id for user to whom mail will be sent. Needed for ACL
     * @return array $final_trigger_tags array of all tags each corresponding to one JMA plugin trigger
     */
    function get_final_trigger_tags($tmpl_tags,$user_plug,$uid)
    {
        if(JVERSION < '1.6.0')
        {
            $acl=&JFactory::getACL() ;
            $grp=$acl->getAroGroup($uid);
            if($acl->is_group_child_of($grp->name,'Registered')||$acl->is_group_child_of($grp->name,'Public Backend')){
                $aid = 2 ;
            }else{
                $aid = 1 ;
            }
            //only enabled plugins should be processed as per user's acl
            $query="SELECT element FROM #__plugins
					WHERE folder='emailalerts' AND published=1 
					AND access<=".(int)$aid;
            $this->_db->setQuery($query);
        }
        else
        {
            //@TODO aid is remaining for 1.6
            $this->_db->setQuery("SELECT element FROM #__extensions WHERE folder='emailalerts' AND enabled = 1");
        }
        $enabled_plugins=$this->_db->loadResultArray();

        $i=0;
        foreach($tmpl_tags as $tt)
        {
            //actual plugin name
            $tags[$i][0]=$tt['plug_trigger'];
            //actual tag/data tag  in email template.
            //This is needed when replacing tags in email with data outputed by corresponding plugin
            $tags[$i][1]=$tt['tag_to_replace'];
            $i++;
        }
        $final_trigger_tags=array();
        $tag_counter=0;
        foreach($tags as $tag)
        {
            if(in_array($tag[0],$enabled_plugins))//if plugin is enabled
            {
                //this foreach is needed
                //because user preferences array will be having only one instance of a one plugin
                //but in template we may use same tag 3-4 times as a data tag
                //so we need to process each data tag against all user plugins(actually matching corresponding plugin)
                foreach($user_plug as $u)
                {
                    if($tag[0]==$u['plug_trigger'])
                    {
                        $single_plugin_params=jmailalertsModelEmails::get_single_plugin_params($tmpl_tags[$tag_counter],$u);
                        $final_trigger_tags[$tag_counter]=$single_plugin_params;
                    }
                    elseif(isset($tmpl_tags[$tag[0]]) && isset($user_plug[$tag[0]])){
                        $final_trigger_tags[$tag_counter]=$single_plugin_params;
                    }
                }
            }
            $final_trigger_tags[$tag_counter]['tag_to_replace']=$tag[1];
            if(isset($final_trigger_tags[$tag_counter]['tag_to_replace']) && !isset($final_trigger_tags[$tag_counter]['plug_trigger']))
            {
                
                if(JVERSION < '1.6.0'){
                    $dp=jmailalertsModelEmails::get_default_plugin_params_j15($tag[0]);
                }else{
                    $dp=jmailalertsModelEmails::get_default_plugin_params_j16($tag[0]);
                }
                
                if(isset($dp['is_special']) && ($dp['is_special']))
                {
                	$single_plugin_params=jmailalertsModelEmails::get_single_plugin_params($tmpl_tags[$tag_counter],$dp);
                	$final_trigger_tags[$tag_counter]=$single_plugin_params;
	                $final_trigger_tags[$tag_counter]['plug_trigger']=$tag[0];
	                $final_trigger_tags[$tag_counter]['tag_to_replace']=$tag[1];
	            }
	            
            }
            $tag_counter++;
        }
        return $final_trigger_tags;
    }

    /**
     *Compare a single "template tag array" with corresponding "user tag array"
     *and return a new "tag array" preserving all array indices(actually plugin parameters) for both arrays
     *See example given below
     *
     * @param array $tmpl_tag
     * @param array $user_tag
     * @return array $new_final_tag
     */
    function get_single_plugin_params($tmpl_tag,$user_tag)
    {
        if(!isset($user_tag)){
        	$user_tag=array();
        }
        $new_final_tag=array();
        $merged=array_merge($tmpl_tag,$user_tag);
        $params=array_keys($merged);//get all parameter names
        foreach($params as $param)//process each parameter
        {
            if(isset($tmpl_tag[$param]) && isset($user_tag[$param]))//if a parameter is specified in a template tag(i.e. data tag)
            {
                $p=jmailalertsModelEmails::get_single_param($tmpl_tag[$param],$user_tag[$param]);
                $new_final_tag[$param]=$p;
                if($p){
                    //if common values found
                    $new_final_tag[$param]=$p;
                }
                else{
                    //if nothing is common , respect template parameter
                    //@TODO might need to check
                    $new_final_tag[$param]=$tmpl_tag[$param];
                }
                //@TODO important to preseve count preference set by user.
                //Need to remove this option from user preferences for every plugin
                if($param=='no_of_users' || $param=='count'){
                    $new_final_tag[$param]=$user_tag[$param];
                }
            }
            else//preserve paramters not specified in data tags but are there in user preferences
            {
                if(isset($user_tag[$param])){
                    $new_final_tag[$param]=$user_tag[$param];
                }
            }
        }
        return $new_final_tag;
    }

    /**
     *Compares "template tag-paramter value" and "user tag-paramater value" and returns common values(intersection of both)
     *For example $p1=1,3,5; $p2=1,2,3,4,6,7; it should return $p3=1,3;
     *
     * @param string $tmpl_tag_param_value string like "1,3,5"
     * @param string $user_tag_param_value string like "1,2,3,4,6,7"
     * @return array $common_param_value "1,3"
     */
    function get_single_param($tmpl_tag_param_value,$user_tag_param_value)
    {
        $tmpl_param_val=jmailalertsModelEmails::get_exploded($tmpl_tag_param_value);
        $user_param_val=jmailalertsModelEmails::get_exploded($user_tag_param_value);
        $common_param_value=array_intersect($tmpl_param_val,$user_param_val);
        $common_param_value=implode(",",$common_param_value);
        return $common_param_value;
    }

    /**
     * converts "1,2,3" like strings into an array
     *
     * @param string $str string like "1,2,3,4" or "1"
     * @return array $pieces
     */
    function get_exploded($str)
    {
        $pieces=explode(",",$str);
        return $pieces;
    }
}
