<?php 
defined('_JEXEC') or die('Restricted access'); 
JHTML::_('behavior.modal', 'a.modal');	

JToolBarHelper::publishList();
JToolBarHelper::unpublishList();	

$model = $this->getModel();
$data  = $this->user_data;

$cid	= JRequest::getVar( 'cid','' );

$i = 0;
$rec =0;

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
	
	function hello(uid,sdate,aid,rid)
	{
	  var flag = 0;	
	  var link= "<?php echo JURI::base().'index.php?option=com_jmailalerts&controller=manageuser&task=priview&tmpl=component&send_mail_to_box=admin@admin.com&flag=1&userid='; ?>"
	  
	  //var alertname = document.getElementById('altypename').value;	 
	  link = link+"&user_id="+uid+"&select_date_box="+sdate+"&alert_id="+aid;
	//alert(rid);
	// console.log(link);
	  document.getElementById(rid).setAttribute('href',link);
	 }
</SCRIPT>

<form action="" method="post" name="adminForm" id="adminForm">
<div id="editcell">

<table class="adminform">
	<tr>
		<td align="left" width = '50%'>
			<label><?php echo JText::_( 'ENT_USR_NM' ); ?> :</label>
    		<input type="text" name="search" id="search" value="<?php echo $this->search; ?>" class="inputbox" onchange="document.adminForm.submit();" />
    		<button onclick="this.form.submit();">Go</button>
    	</td>
		<td style="text-align:right" width = "50%">
		<?php echo $this->alert_nm; ?>
		<?php echo $this->state; ?>
		</td>
	</tr>
</table>
	<table class="adminlist">
	<thead>
		<tr>
			
			
			<th width="1%" >
			         <input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo COUNT($this->user_data); ?>);" />
			</th>
				
			<th width="10">
				<?php echo JText::_( 'NUM' ); ?>
			</th>		
			<th align="left" width="20">
				<?php echo JText::_( 'USER_D' ); ?>
			</th>
			
			<th width="30">
			
			<?php echo JText::_( 'ALERT_D' ); ?>
			</th>
			
			<th nowrap="nowrap" width="30">
		    
		     <?php echo JText::_( 'SUB_STATE' ); ?>
		     
		    </th>
			<th nowrap="nowrap" width="30">
		    
		     <?php echo JText::_( 'FREQUENCY' ); ?>
		     
		    </th>
			<th nowrap="nowrap" width="20">
		     
			 <?php echo JText::_( 'LAST_DATE' ); ?>
		    
		    </th>
		    
			<th width="10">
				<?php echo JText::_( 'PREVIEW' ); ?>
			</th>
			
						
		</tr>
	</thead>
	<?php
	 $j = 0;
	 $cnt = 1;
	 $ln = 0;
	for($i=0;$i<COUNT($data);$i++)
	{
	  
		for($k=0;$k<1;$k++)
		{   
			//$published 	= JHTML::_('grid.id', $data, $k );
			$link 		= JRoute::_( 'index.php?option=com_jmailalerts&controller=manageuser&task=edit&cid[]='. $data[$i]->alert_id );
			
		?>
		<tr class="<?php echo "row$j"; ?>">
			
			<td align="center">
			     
			   <?php  //echo $checked;
			          echo JHTML::_('grid.id',$ln,$data[$i]->id ); ?>
	     	</td>
			
			<td align="center">
				<?php echo $cnt; ?>
			</td>
			
			<td >
				<?php echo $data[$i]->name; ?><br /><?php echo $data[$i]->username; ?><br /><?php echo $data[$i]->user_id; ?>
			</td>
		    
		    <td align="center"> 
			   
			            <?php echo $data[$i]->alert_name ; ?>
			    	<br />
						<?php echo $data[$i]->alert_id ; ?>
					<br />
			</td>
			<td align="center">
			   
			   <?php  $flag = ($data[$i]->option) ? 1 : 0;			          
					 if(JVERSION < '1.6.0')
					 { ?>
				
			         <a value ="<?php echo $data[$i]->id; ?>" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $ln;?>','togglestate')"><img src="images/<?php echo ($flag) ? 'tick.png' : 'publish_x.png';?>" width="16" height="16" border="0" /></a>
				    <?php
				    		echo JHTML::_( 'grid.id' , $i , $flag ,'togglestate' ,'togglestate' );
				     }		
					 else
					 {
						    echo JHTML::_( 'grid.boolean' , $i , $flag,'togglestate' ,'togglestate' );
					  }		   
			   ?>
			 </td>
			<td align="center">
			
		          <?php echo $data[$i]->option; ?>
			
			</td>
			<td align="center">
							
			    <?php echo $data[$i]->date ; ?>
			
			</td>
			<td align="center">
		      <?php  
		            if($flag)
		            {   ?> 
						&nbsp;
						<a id ="<?php echo $data[$i]->id; ?>" rel="{handler: 'iframe', size: {x:700, y: 600}}" onclick="hello(<?php echo $data[$i]->user_id;?>,'<?php echo $data[$i]->date;?>',<?php echo $data[$i]->alert_id;?>,<?php echo $data[$i]->id;?>);" href="<?php echo JURI::base();?>" class='modal'><?php echo JText::_('PREVIEW');?></a>
			  <?php  } ?>																																									     					   
			</td>

		</tr>
		<?php
		
	    $cnt++;
	    $j = 1 - $j;
	    $ln++;
	}
}
	?>
	
	<tfoot>
	<tr>
		<td colspan="9"><?php  echo $this->pagination->getListFooter(); 
		 ?></td>
	</tr>
</tfoot>
	</table>
</div>

<input type="hidden" name="option" value="com_jmailalerts" />
<input type="hidden" name="view" value="manageuser" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="manageuser" />
</form>

