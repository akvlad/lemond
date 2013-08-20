<?php
$k=array();
$i=1;
$model = $this->getModel();
$qry_concat = $this->qry_concat;
$option = $this->defaultoption;
$default_setting = $this->default_setting;  
$altid = $this->altid;

for($s=0;$s<count($qry_concat);$s++)//for loop for alert types
{
	$plugin_data = $model->getPluginData($qry_concat[$s]);
	//checking alert id with default selected alert for checking checkbox
	/*changed in 2.4.3*/
	//if(in_array($altid[$s],$option))
	if(isset($option[$altid[$s]]) && $option[$altid[$s]]['option']>0)
	{
		$plugin_name =$model->getData($altid[$s]);
		$alertchk= "checked";
		$bstyle='class="subscribed_alert"';
		$sub_status_msg=JText::_('JMA_UNCHECK_SUB_MSG');
		$show_plugins="block";
		$check_hidden_plugin=0;
	}
	else
	{
		$plugin_name =$model->getData($altid[$s]);
		$alertchk= "";
		$bstyle='class="unsubscribed_alert"';
		$sub_status_msg=JText::_('JMA_CHECK_SUB_MSG');
		$show_plugins='none';
		$check_hidden_plugin=1;
	}

	//get frequency
	$altdata = $model->getFreq($altid[$s]);
	if($altdata[3]== 1)
		$allowuser="display";
	else
		$allowuser="none";
	
	echo '
		<div>
			<div class="box-container-t">
				<div class="box-tl"></div>
				<div class="box-tr"></div>
				<div class="box-t"></div>
			</div>									
			<div class="content_cover">
				<input type="checkbox" name="alt[]" value="'.$altid[$s].'" onclick="divhide(this);" '.$alertchk.'/>
				<b '.$bstyle.'>'.$altdata[1].'</b> <span class="sub_status_msg">'.$sub_status_msg.'</span>
	      		<div>
		        	<div class="jma_alert_desc">'.$altdata[2].'</div>
			  		<div id="'.$altid[$s].'" style="display:'.$show_plugins.'">
				  		<div style="display:'.$allowuser.'">
			  				<div class="alert_frequncy"><b>'.JText::_("CURRENT_SETTING").'</b></div>
			  				<div>'.$altdata[0].'</div>';
		
	if($plugin_data)
	{
		foreach($plugin_data as $single_plugin_name)//for loop for all plugins inside one alert type
		{
			//Get the plugin names under email-alerts
			//$checkit = $model->alertdata($single_plugin_name->id);
			$plugtitlex= explode("\n",$single_plugin_name->params);
			$plugtitle=explode("=",$plugtitlex[0]);
			foreach($plugin_name as $key=>$v)
			{
				$pathToXML_File = JPATH_SITE . DS . "plugins". DS ."emailalerts". DS .$single_plugin_name->element.".xml";
				$flag=0;
				if($single_plugin_name->element==$key)
				{
					$params=implode("\n",$v);
					$params=str_replace(',','|',$params);
					$params = new JParameter($params, $pathToXML_File);
					$disp='';
					$chk=$v[count($v)-1];
					if($chk=="checked=''")
						$checked='';
					else 
						$checked="checked";
					$flag=1; 
					break;
				}
				else
				{
					$params = new JParameter("foo=bar", $pathToXML_File); 
					$disp='style="display:none"';
					$flag=0;
					$checked="";
				}
			}//end for
			if($flag==1)
			{
				if(!in_array($single_plugin_name->element,$k))
				{ 
					$k[]=$single_plugin_name->element;
					?>
							<div class="jmail-blocks">
								<div class="box-container-t">
									<div class="box-tl"></div>
									<div class="box-tr"></div>
									<div class="box-t"></div>
								</div>									
								
								<div class="content_cover">
									<?php
									//if($default_setting ==1)		
									if($check_hidden_plugin)
										$checked = "checked";
									echo '
									<div class="jma_alert" >
										<input type="checkbox"  name="ch'.$altid[$s].'[]" value="'.$single_plugin_name->element.'_'.$altid[$s].'" onclick="divhide(this);"'.$checked.'/><b>'.$plugtitle[1].'</b>
									</div>';
									//below code try passing extra param with _alertid
									$test = $single_plugin_name->element.'_'.$altid[$s];
									echo '
									<div class="jmail-expands" id="'.$single_plugin_name->element.'_'.$altid[$s].'"'.$disp.' >'.
										$params->render($test, 'legacy')
									.'</div>
								</div> 
								<div class="content_bottom">
									<div class="box-bl"></div>
									<div class="box-br"></div>
									<div class="box-b"></div>
								</div>
							</div>';	
				}
			}//end if
			unset($plugtitlex);
			unset($plugtitle);
	  	}//end for loop for all plugins inside one alert type
	 
		echo '
							</div>
						</div>
					</div>
				</div> 
				<div class="content_bottom">
					<div class="box-bl"></div>
					<div class="box-br"></div>
					<div class="box-b"></div>
				</div>
				<br/>';
		}//end if(plugin data)
  		else{
			echo JText::_('NO_PLUGINS_ENABLED_OR_INSTALLED');
		}			
  	unset($k);
  	unset($plugin_name);
	$k=array();$i=1;
	echo '
			</div>
		</div>';
}//end outermost for loop for alert types 
