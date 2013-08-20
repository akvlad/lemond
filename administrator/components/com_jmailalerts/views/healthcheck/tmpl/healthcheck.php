<?php defined('_JEXEC') or die('Restricted access'); 

		$model = $this->getModel();
	    $data = $model->healthcheck();
		$cid	= JRequest::getVar( 'cid','' );
?>

<script type="text/javascript">
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="editcell">

<table class="adminlist">
	<thead>
		<tr>
			
			<th width="50">
				<?php echo JText::_( 'ID' ); ?>
			</th>		
			<th align="center" width="300">
				<?php echo JText::_( 'CHECK' ); ?>
			</th>
			
			<th width="300">
			
			<?php echo JText::_( 'DESC' ); ?>
			</th>
			
			<th width="300">
		    
		     <?php echo JText::_( 'STATUS' ); ?>
		     
		    </th>
			
			<th nowrap="nowrap" >
		     
			 <?php echo JText::_( 'ACT_NEED' ); ?>
		    
		    </th>
		    
			
		</tr>
	</thead>
	
   <tr >
			
			<td align="center">
			
			   <?php echo "1" ?>
	     	</td>
			
			<td>
				<?php echo JText::_( 'QUESTION1' ); ?>
			</td>
			
			<td>
				
			 </td>    
		    
			 <td> 	
		       <?php  $installed =(!empty($data['installed'])) ? 'Yes' :'No' ; ?>
				<font color="green">	
				    <?php  if($installed == 'Yes')
				            { 
							  echo($installed); 
				    		  }else{
								   ?><font color="red"> <?php echo($installed);  
								    } ?>	
							   </font>	
		    
		   
		    
			   
			</td>
			<td align="left">
		    <?php  $installed =(!empty($data['installed'])) ? 1 : 0 ; 
					
			  if($installed == 1)
				            { 
							   echo JText::_( 'NO_ACT' ); 
				    		 }
				    		 else{
								    echo JText::_( 'ACT1' );  
								     
							      } ?>	
		
			</td>
			
		</tr>
		
		<tr >
			
			<td align="center">
			
			   <?php echo "2" ?>
	     	</td>
			
			<td>
				<?php echo JText::_( 'QUESTION2' ); ?>
			</td>
			
			<td>
				
			 </td>    
		    
			 <td> 	
		       <?php  $enable =(!empty($data['enable'])) ? 'Yes' :'No' ; ?>
				<font color="green">	
				    <?php  if($enable == 'Yes')
				            { 
							  echo($enable); 
				    		  }else{
								   ?><font color="red"> <?php echo($enable);  
								    } ?>	
							   </font>	
		    
		   
		    
			   
			</td>
			<td align="left">
		    <?php  $enable =(!empty($data['installed'])) ? 1 : 0 ; 
					
			  if($enable == 1)
				            { 
							   echo JText::_( 'NO_ACT' ); 
				    		 }
				    		 else{	?>							    
									<a href="index.php?option=com_plugins" target="_blank">Plugin Manager</a>
						   <?php } ?>	
		
			</td>
			
		</tr>
		
		<tr >
			
			<td align="center">
			
			   <?php echo "3" ?>
	     	</td>
			
			<td>
				<?php echo JText::_( 'QUESTION3' ); ?>
			</td>
			
			<td>
				<?php echo JText::_( 'DESC_Q3' ); ?>
			 </td>    
		    
			 <td> 	
		          
		    
		    <?php
		           $warn=0;
					$plugins_name =$model->getPlugnames();
								
								foreach($plugins_name as $plgname)
								{  
									$plgnm = (JVERSION < '1.6.0') ? $plgname->published : $plgname->enabled ;
									if(!$plgnm)
									{ 
									?>
								    	<font color="red">	
									        <?php  echo($plgname->name); $warn=1; ?>	
									    </font><br />
							 <?php	}
							        else
							        {  
										echo($plgname->name);
										echo "<br />";
									}
									
		                           }
			?>	
			</td>
			<td align="left">
			 
		    <?php    $cnt = 0;
					foreach($plugins_name as $plgname)
					{   
						 $plgnm = (JVERSION < '1.6.0') ? $plgname->published : $plgname->enabled ;
						 $cnt =($plgnm == 0) ? $cnt+1 : $cnt ;
						 
					} 
		    
		      $plgname =(COUNT($plugins_name)==$cnt) ? 0 : 1 ; 
					
			  if($plgname == 1 and $warn == 0 )
				            { 
							   echo JText::_( 'NO_ACT' ); 
				    		 }
				    		 else{	
								    echo JText::_( 'ACT3' ); 
								    ?>
								    <br /><a href="index.php?option=com_jmailalerts&view=alertypes" target="_blank">Manage Alerts</a>
								    <br /><a href="index.php?option=com_plugins" target="_blank">Plugin Manager</a>
						    <?php    } ?>	
		
			</td>
			
		</tr>
		
		<tr >
			
			<td align="center">
			
			   <?php echo "4" ?>
	     	</td>
			
			<td>
				<?php echo JText::_( 'QUESTION4' ); ?>
			</td>
			
			<td>
			
			 </td>    
		    
			 <td> 	
		       <?php  $alerts =(!empty($data['alerts'])) ? 'Yes' :'No' ; ?>
				<font color="green">	
				    <?php  if($alerts == 'Yes')
				            { 
							  echo($alerts);
							  echo"</font>";
							   echo ", Total   ".$data['alerts']."      alert(s) found.";  
				    		  }else{
								   ?><font color="red"> <?php echo($alerts);  
								    } 
								  
								     ?>	
							   </font>	
                    		    
			</td>
			<td align="left">
		    <?php  $alerts =(!empty($data['alerts'])) ? 1 : 0 ; 
					
			  if($alerts == 1)
				            { 
							   echo JText::_( 'NO_ACT' ); 
				    		 }
				    		 else{	?>							    
									<a href="index.php?option=com_jmailalerts&view=alertypes" target="_blank">Manage Alerts</a>
						   <?php } ?>	
		
			</td>
			
		</tr>
		
		<tr class="<?php echo "row$k"; ?>">
			
			<td align="center">
			
			   <?php echo "5" ?>
	     	</td>
			
			<td>
				<?php echo JText::_( 'QUESTION5' ); ?>
			</td>
			
			<td>
			
			 </td>    
		    
			 <td> 	
		       <?php  $published =(!empty($data['published'])) ? 'Yes' :'No' ; ?>
				<font color="green">	
				    <?php  if($published == 'Yes')
				            { 
							  echo($published);
							  echo"</font>";
							  echo ", Total   ".$data['published']."     published ."; 
				    		  }else{
								   ?><font color="red"> <?php echo($published);  
								    } ?>	
							   </font>	
		    
			</td>
			<td align="left">
		    <?php  $published =(!empty($data['published'])) ? 1 : 0 ; 
					
			  if($published == 1)
				            { 
							   echo JText::_( 'NO_ACT' ); 
				    		 }
				    		 else{	?>							    
									<a href="index.php?option=com_jmailalerts&view=alertypes" target="_blank">Manage Alerts</a>
						   <?php } ?>	
		
			</td>
			
		</tr>
		
		<tr >
			
			<td align="center">
			
			   <?php echo "6" ?>
	     	</td>
			
			<td>
				<?php echo JText::_( 'QUESTION6' ); ?>
			</td>
			
			<td>
			     <?php echo JText::_( 'DESC_Q6' ); ?>
			 </td>    
		    
			 <td> 	
		       <?php  $defaults =(!empty($data['defaults'])) ? 'Yes' :'No' ; ?>
				<font color="green">	
				    <?php  if($defaults == 'Yes')
				            { 
							  echo($defaults);
							  echo"</font>";
							  echo ", Total   ".$data['defaults']."     alert(s)  set as default alert types for new users ."; 
				    		  }else{
								   ?><font color="red"> <?php echo($defaults);  
								    } ?>	
							   </font>	
		    
			</td>
			<td align="left">
		    <?php  $defaults =(!empty($data['defaults'])) ? 1 : 0 ; 
					
			  if($defaults == 1)
				            { 
							   echo JText::_( 'NO_ACT' ); 
				    		 }
				    		 else{	?>							    
									<a href="index.php?option=com_jmailalerts&view=alertypes" target="_blank">Manage Alerts</a>
						   <?php } ?>	
		
			</td>
			
		</tr>
		
		<tr class="<?php echo "row$k"; ?>">
			
			<td align="center">
			
			   <?php echo "7" ?>
	     	</td>
			
			<td>
				<?php echo JText::_( 'QUESTION7' ); ?>
			</td>
			
			<td>
			
			 </td>    
		    
			 <td> 	
		       <?php  $synced =(!empty($data['synced'])) ? 'Yes' :'No' ; ?>
				<font color="green">	
				    <?php  if($synced == 'Yes')
				            { 
							  echo($synced);
							  echo"</font>";
							  echo ", Total   ".$data['synced']."     alert(s) synced ."; 
				    		  }else{
								   ?><font color="red"><?php echo($synced);  
								    } ?>	
							   </font>	
		    
			</td>
			<td align="left">
		    <?php  $synced =(!empty($data['synced'])) ? 1 : 0 ; 
					
			  if($synced == 1)
				            { 
							   echo JText::_( 'NO_ACT' ); 
				    		 }
				    		 else{	?>							    
									<a href="index.php?option=com_jmailalerts&view=sync" target="_blank">Sync Mail</a>
						   <?php } ?>		
		
			</td>
			
		</tr>
	<tfoot>
	<tr>
		<td colspan="9"><?php  //echo $this->pagination->getListFooter(); 
		 ?></td>
	</tr>
</tfoot>
	</table>
</div>

<input type="hidden" name="option" value="com_jmailalerts" />
<input type="hidden" name="view" value="healthcheck" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="healthcheck" />
</form>
